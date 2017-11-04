# Make/Accessible

Lightweight PHP package that let you test singleton classes and inaccessible (private or protected) instance members.

You can read about problems we solve [here](https://github.com/e200/MakeAccessible/tree/master/docs/problems).

## Table of contents

- [Installation](#installation)
- [Features](#features)
- [Usage](#usage)
- [Best practices](#best-practices)
- [Support](#support)
- [Contribute](#contribute)
- [Credits](#credits)
- [License](#license)

## Installation

    composer require make/accessible

## Features

- Call inaccessible methods
- Set values into inaccessible properties
- Get values from inaccessible properties
- Instantiate singleton classes

## Usage

```php
<?php

class Inaccessible
{
    private $privateProperty = true;

    public setPrivateProperty($value)
    {
        $this->privateProperty = $value;
    }
}
```

```php
<?php

use Inaccessible;
use e200\MakeAccessible\Make;
use PHPUnit\Framework\TestCase;

class InaccessibleTest extends TestCase
{
    public function testSetPrivateProperty()
    {
        $instance = new Inaccessible();
        $accessibleInstance = Make::accessible($instance);

        $expected = 'Lorem Ipsum';

        $instance->setPrivateProperty($expected);
        $this->assertEquals($expected, $accessibleInstance->privateProperty);
    }
}

```

```
PHPUnit 6.3.1 by Sebastian Bergmann and contributors.

                                                                  1 / 1 (100%)

Time: 42 ms, Memory: 4.00MB

OK (1 test, 1 assertion)
```

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

## Contribute

Feel free to contribute forking, making changes and pull requests.

## Credits

 - [Eleandro Duzentos](https://github.com/e200) and contributors.

## License

The MakeAccessible is licensed under the MIT license. See the [license](https://github.com/e200/MakeAccessible/blob/master/license.md) file for more information.
