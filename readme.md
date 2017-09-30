# Make/Accessible

A very lightweight PHP package that let you easily access and test singleton classes and inaccessible instance members like private or protected methods and properties.

## Table of contents

 - [Installation](#installation)
 - [Usage](#usage)
 - [Features](#features)
 - [Best practices](#best-practices)
 - [Support](#support)
 - [Contribute](#contribute)
 - [Credits](#credits)
 - [License](#license)

## Installation
To install you must have [composer](https://getcomposer.org/) installed, then run:

    composer require make/accessible

using a **terminal/prompt** in your project folder.

## Usage

**Problem:**

Suppose you have the following class:

```php
<?php

class PeopleGreeter
{
    protected $peopleToGreet = [];

    public function greetEveryone()
    {
        foreach ($this->peopleToGreet as $person) {
            echo $this->greet($person);
        }
    }

    public function addPersonToGreet($person)
    {
        $this->peopleToGreet[] = $person;
    }

    protected function greet($person)
    {
        if ($person->isWoman()) {
            return 'Hi Mrs. ' . $person->name();
        } else {
            return 'Hello Mr.' . $person->name();
        }
    }
}
```

This class is correct, it does'nt exposes its inner elements (encapsulation) and was designed to do what it is supposed to do.

But, how are you going to **test** this class? **Test** if the `addPersonToGreet()` method is really adding `$person` into `$peopleToGreet` and `greet()` method is really distinguishing mens from womens?

You can just make all your class members **public**, but that way you're breaking [encapsulation](https://en.wikipedia.org/wiki/Encapsulation_(computer_programming)), thats a bad pratice.

So, what can be done?

**Solutions:**

One way to **test** `addPersonToGreet()` would be by directly accessing `$peopleToGreet` and check if `$person` is really there:

```php
$person = new Person('John Doe');

$peopleGreeter = new PeopleGreeter();

$peopleGreeter->addPersonToGreet($person);

$this->assertTrue(in_array($person, $peopleGreeter->peopleToGreet));
```

One way to test `greet()` would be by directly accessing it:

```php
$person = new Person('John Doe');

$this->assertEquals("Hello Mr. John Doe", (new PeopleGreeter())->greet($person));
```

But of course these methods will fail, its will result in:

```
Error: Cannot access protected property PeopleGreeter::$peopleToGreet.
Error: Cannot access protected method PeopleGreeter::greet().
```

because we can't access **protected** and **private** members outside of they **parent context**.

Another way that works only with **protected members** is by **extending** their parent to another **class** that exposes its methods:

```php
class ExtendedPeopleGreeter extends PeopleGreeter
{
    public function greet($person)
    {
        return parent::greet($person);
    }

    public function getPeopleToGreet()
    {
        return parent::$peopleToGreet;
    }
}
```
```php
$person = new Person('John Doe');

$this->assertEquals("Hello Mr. John Doe", (new ExtendedPeopleGreeter())->greet($person));
```
```php
$person = new Person('John Doe');

$peopleGreeter = new ExtendedPeopleGreeter();

$peopleGreeter->addPersonToGreet($person);

$this->assertTrue(in_array($person, $peopleGreeter->getPeopleToGreet()));
```
##### Pros:
- You can test protected members.

##### Cons:
- Don't works with private members.
- Makes really hard write simples tests.
- You need to write a fake class every time you want to test a real class.
- You need to write a method for each protected member you want to test.

But of course there's another solution: Using [PHP Reflection](php.net/manual/en/book.reflection.php):

```php
$person = new Person('John Doe');

$peopleGreeter = new PeopleGreeter();

$peopleGreeter->addPersonToGreet($person);

$reflectedClass = new \ReflectionClass($peopleGreeter);

$propertyName = 'peopleToGreet';

if ($reflectedClass->hasProperty($propertyName)) {
    $property = $reflectedClass->getProperty($propertyName);

    $property->setAccessible(true);

    $this->assertTrue(in_array($person, $property->getValue($peopleGreeter)));
}
```

```php
$person = new Person('John Doe');

$reflectedClass = new \ReflectionClass($peopleGreeter);

$propertyName = 'peopleToGreet';

$peopleGreeter = new PeopleGreeter();

$reflectedClass = new \ReflectionClass($peopleGreeter);

$methodName = 'greet';

if ($reflectedClass->hasMethod($methodName)) {
    $method = $reflectedClass->getMethod($methodName);

    $method->setAccessible(true);

    $returnedValue = $method->invokeArgs($peopleGreeter, ['person' => $person]);

    $this->assertEquals("Hello Mr. John Doe", $returnedValue);
}
```

##### Pros:
- Works for both protected and private members.

##### Cons:
- You need to write a lot of extra code to make simple tests.
- You need to write code that also need to be tested.
- Its hard to reuse this code in other test either in other projects.

We're testing only 2 methods, now think about 30 or 50??? No, No, Forget About It.

### Using MakeAccessible:

```php
$person = new Person('John Doe');

$peopleGreeter = new PeopleGreeter();

$peopleGreeter->addPersonToGreet($person);

$accessiblePeopleGreeter = Make::accessible($peopleGreeter);

$this->assertTrue(in_array($person, $accessiblePeopleGreeter->peopleToGreet));
```

```php
$person = new Person('John Doe');

$peopleGreeter = new PeopleGreeter();

$accessiblePeopleGreeter = Make::accessible($peopleGreeter);

$this->assertEquals("Hello Mr. John Doe", accessiblePeopleGreeter->greet($person));
```

Did you saw that??? Just one line of code and we made our **tests**!!!

We gain access to our `PeopleGreeter` inaccessible members in a common and friendly way! ;)

##### Pros:
- User friendly.
- Just one line of code.
- Makes tests really easy.
- Works for both private and protected members.
- Don't require write fake classes or methods for each test.
- Encourages the usage of encapsulation in projects providing a more isolated and flexible environment.

##### Cons:
- :sweat_smile:

## Features
- Call inaccessible methods.
- Get values from inaccessible properties.
- Set values into inaccessible properties.
- Instantiate classes with inaccessible constructors (Singletons).

## Best practices

We highly recommend the use of this package for tests purposes only.

Avoid use this package to gain access to encapsulated classes, since it's like break the door of someone's house that does'nt want you to get inside.

If you're **testing** the same class the same way, we recommend create a function `getAccessibleInstance()` at the bottom of your **test** class and there you make the instantiation, mocks, everything you need to instantiate the class you need to **test**:

```php
class PeopleGreeterTest extends TestCase
{
    public function testGreet()
    {
        $person = new Person('John Doe');

        $peopleGreeter = $this->getAccessibleInstance();

        $this->assertEquals("Hello Mr. John Doe", $peopleGreeter->greet($person));
    }

    Others test functions...

    public function getAccessibleInstance()
    {
        $instance = new PeopleGreeter();

        return Make::accessible($instance);
    }
}

```

For more tips about best practices, please, read our [best practices](https://github.com/e200/MakeAccessible/) documentation.

## Support

Need a new feature? Found a bug? Please open a [new issue](https://github.com/e200/MakeAccessible/issues/new) or send me an [email](mailto://eleandro@inbox.ru) and we'll fix it as soon as possible.

## Contributing

Feel free to contribute forking, making changes and pull requests.

## Credits

 - [Eleandro Duzentos](https://github.com/e200) and contributors.

## License

The MakeAccessible is licensed under the MIT license. See the [license](https://github.com/e200/MakeAccessible/blob/master/license.md) file for more information.
