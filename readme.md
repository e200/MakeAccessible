# e200/MakeAccessible

A PHP package that let you easely access or test inaccessible instance members (private, protected methods and properties).

## Table of contents

 - [Installation](#installation)
 - [Usage](#usage)
 - [Contribute](#contribute)
 - [Credits](#credits)
 - [License](#license)

## Installation
To install you must have [composer](https://getcomposer.org/) installed, then run:

    composer require e200/makeaccessible

using a **terminal/prompt** in your project folder.

## Usage

### Problem:

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

$accessiblePeopleToGreet = Make::accessible($peopleGreeter);

$this->assertTrue(in_array($person, $accessiblePeopleToGreet->peopleToGreet));
```

```php
$person = new Person('John Doe');

$accessiblePeopleToGreet = Make::accessible($peopleGreeter);

$this->assertEquals("Hello Mr. John Doe", accessiblePeopleToGreet->greet($person));
```

Did you saw that??? Just one line of code and we made our tests!!!

We gain access to our `PeopleGreeter` inaccessible members! ;)

## Support

Need a new feature? Found a bug? Please open a [new issue](https://github.com/e200/MakeAccessible/issues/new) or send me an [email](mailto://eleandro@inbox.ru) and we'll fix it as soon as possible.

## Contributing

Feel free to contribute forking, making your changes and making a pull request.

## Credits

 - [Eleandro Duzentos](https://github.com/e200) and contributors.

## License

The MakeAccessible is licensed under the MIT license. See the [license](https://github.com/e200/MakeAccessible/blob/master/license.md) file for more information.
