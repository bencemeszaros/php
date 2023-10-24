# Implementing structs in PHP

This is a simple struct implementation in PHP. Since PHP doesn't have typed arrays or any built in mechanism to enforce values for class properties we need to manually organize and keep the integrity of our data. This implementation aims to simplify this process.

## Example usage
```php
//Define a struct
class People extends Struct {
    public string $name;
    public int $age;
    public int $height;
}

//Create a struct instance
$alice = new People(name: "Alice", age: 25, height: 160);
```

## Requirements

This library requires PHP 8.0.0 or greater to work (support for named arguments). To enable automatic type checking, strict types should be enabled. There are no additional requirements or dependencies.

## Usage

### Defining a struct

To create a struct just define a simple PHP class that extends Struct. Property modifiers (with the exception of readonly), type declarations and default values are all supported.

### Initialization

Structs automatically receive a memberwise initializer. When initializing structs
- always use named arguments, simple argument lists are not supported
- all non-optional properties have to be set
- all optional properties become null if not set
- if strict types are enabled, all values are type checked

### Caveats and limitations

1. Creating properties dynamically is deprecated in PHP 8 and will be removed from future versions. This implementation already throws an error when attempting to create properties dynamically. This includes:
    - initializing with a simple argument list (initializer fails to match numeric argument keys to struct properties)
    - initializing with more arguments than defined
    - trying to create property after initialization
    - making a typo in an argument

```php
class People extends Struct {
    public string $name;
    public int $age;
    public int $height;
}

$alice = new People(name: "Alice", age: 25, height: 160);
$alice->name = 20; //PHP Fatal error:  Uncaught TypeError: Cannot assign int to property People::$name of type string
```

2. Creating constant struct properties is not possible due to limitations in PHP:
    - using the const keyword is class level and not instance level
    - readonly property in child class cannot be initialized in parent class __construct()

3. Extending structs is not currently not supported. The initializer will only check properties of the current class.

4. Adding a custom constructor is currently not allowed

5. Default PHP errors show incorrect line number and file name. They are triggered inside the constructor in this library which is not ideal.