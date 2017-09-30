# e200/MakeAccessible

A PHP package that let you easely access or test inaccessible instance members like private, protected methods and properties.

## Table of contents

 - [Installation](#installation)
 - [Usage](#usage)
 - [Contribute](#contribute)
 - [Features](#features)
 - [Best pretices](#bestpratices)
 - [Support](#support)
 - [Credits](#credits)
 - [License](#license)

## Installation
To install you must have [composer](https://getcomposer.org/) installed, then run:

    composer require make/accessible

using a **terminal/prompt** in your project folder.

## Usage

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

This class is correct, it doesn't exposes its inner elements and was designed to do what it is supposed to do.

But, how are you going to **test** this class? Test if the `addPersonToGreet()` method is really adding `$person` into `$peopleToGreet` and `greet()` method is really distinguishing mens from womens?

One of the ways to **test** `addPersonToGreet()` would be by directly accessing `$peopleToGreet` and check if `$person` is really there:

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

But of course these methods will not work, its will result in:

```
Error: Cannot access protected property PeopleGreeter::$peopleToGreet.
Error: Cannot access protected method PeopleGreeter::greet().
```

Because we can't access **protected** and **private** members outside of they **parent context**.

But of course there's a solution: Using [PHP Reflection](php.net/manual/en/book.reflection.php):

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
    $method = $reflectedClass->hasMethod($methodName);

    $method->setAccessible(true);

    $returnedValue = $method->invokeArgs($peopleGreeter, ['person' => $person]);

    $this->assertEquals("Hello Mr. John Doe", $returnedValue);
}
```
Yes, you need to write a lot of code just to make these simple **tests** works!!!

We're testing only 2 methods, now think about 30 or 50??? No, No, Forget About It.

Now see how easy to test it will be using **MakeAccessible**:

```php
$person = new Person('John Doe');

$peopleGreeter = new PeopleGreeter();

$peopleGreeter->addPersonToGreet($person);

$accessiblePeopleGreeter = Make::accessible($peopleGreeter);

$this->assertTrue(in_array($person, $accessiblePeopleGreeter->peopleToGreet));
```

```php
$person = new Person('John Doe');

$accessiblePeopleGreeter = Make::accessible($peopleGreeter);

$this->assertEquals("Hello Mr. John Doe", accessiblePeopleGreeter->greet($person));
```

Did you saw that??? Just one line of code and we made our **tests**!!!

We gain access to our `PeopleGreeter` inaccessible members! ;)

## Features

- Get values from inaccessible properties.
- Set values into inaccessible properties.
- Call inaccessible methods.

##### Coming soon:

- Instantiate classes with inaccessible constructors (Singletons).
- Clone classes with inaccessible clones.

## Best pratices

We highly recomend the use of this package for tests purposes only.

Avoid use this package to gain access to encapsulated classes, since it's like break the door of someone's house that doesn't want you inside.

If you're testing the same class the same way, we recommend create a function `getAccessibleInstance()` at the bottom of your test class and there you make the instantiation, mocks, everything you need to instantiate your inaccessible class:

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

## Support

Need a new feature? Found a bug? Please open a [new issue](https://github.com/e200/MakeAccessible/issues/new) or send me an [email](mailto://eleandro@inbox.ru) and we'll fix it as soon as possible.

## Contributing

Feel free to contribute forking, making your changes and making a pull request.

## Credits

 - [Eleandro Duzentos](https://github.com/e200) and contributors.

## License

The MakeAccessible is licensed under the MIT license. See the [license](https://github.com/e200/MakeAccessible/blob/master/license.md) file for more information.
