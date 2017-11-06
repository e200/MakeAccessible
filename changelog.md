# Changelog

All notable changes to **Make/Accessible** will be documented in this file.

## 2.2.2 - 07/10/2017

Internal improvements.

## 2.2.1 - 02/10/2017

Internal improvements.

## 2.2.0 - 01/10/2017

Added new functionality:

Now you can also make a singleton instances accessible by using:

```php
$instance = Make::accessibleInstance(Singleton::class);
```

Some internal improvements.

## 2.1 - 30/09/2017

Added new functionality:

Now you can also test singleton instances easily by using:

```php
$instance = Make::instance(Singleton::class);
```

## 2.0 - 30/09/2017

Renamed package from `e200/makeaccessible` to `make/accessible`.

Changed the way we check if a member is public or not.

## 1.0 - 29/09/2017

Changed method `MakeAccessible::make()` to `Make::accessible()`.

## 0.1 - 29/09/2017

First release.
