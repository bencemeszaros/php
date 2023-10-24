# Implementing structs in PHP

This is a simple struct implementation in PHP. Since PHP doesn't have typed arrays or any built in mechanism to enforce values for class properties we need to manually organize and keep the integrity of our data. This implementation aims to simplify this process.

## Example usage

You can define a struct using regular class syntax. Make sure to extend the `Struct` class and always initialize your structs using named arguments.

```php
//Define a struct
class People extends Struct {
    public string $name;
    public int $age;
    public int $height;
}

//Create an instance
$alice = new People(name: "Alice", age: 25, height: 160);

//Modify a property
$alice->age = 42;
```

## Requirements

This library requires PHP 8.0.0 or greater to work (needs support for named arguments). There are no additional requirements or dependencies.

## Usage

### Types

Type checking in structs relies on the built-in type checking in PHP. Use the strict types declaration at the beginning of your script to enable automatic type checking.

```php
declare(strict_types = 1);

class People extends Struct {
    public string $name;
}

$alice = new People(name: 42); //Uncaught TypeError: Cannot assign int to property People::$name of type string
```

Without the strict types declaration, values are coerced into the defined types.

```php
$bob = new People(name: 42);
echo $bob->name; //"42"
```

$~$
### Default values

We can omit properties with default values from the initializer.

```php
class People extends Struct {
    public string $name;
    public bool $registered = false;
}

$alice = new People(name: "Alice");
```

$~$
### Optional values

We can omit optional properties from the initializer. The initializer will set the value of all omitted optional properties to null.

```php
class People extends Struct {
    public string $name;
    public ?int $age;
}

$alice = new People(name: "Alice");
```

$~$
### Dynamic properties

Creating struct properties dynamically is not allowed. PHP 8 already deprecated the creation of dynamic properties and it will be removed in a future version. This struct library already throws a dynamic property error.

```php
class People extends Struct {
    public string $name;
}

$alice = new People(name: "Alice");
$alice->age = 42; //Uncaught ErrorException: "Creation of dynamic property People::$age is not allowed"
```

They are not allowed in the initializer either.

```php
$bob = new People(name: "Bob", age: 42); //Uncaught ErrorException: "Creation of dynamic property People::$age is not allowed"
```

$~$
### Named arguments

Always use named arguments when initializing a struct. The initializer iterates over all arguments and tries to match their keys to struct properties. If we use a simple argument list, the initializer won't find numeric properties and will assume that we are trying to set a dynamic property.

```php
class People extends Struct {
    public string $name;
}

$alice = new People("Alice"); //Uncaught ErrorException: "Creation of dynamic property People::$0 is not allowed"
```

$~$
### Missing arguments

If we leave out an argument during initialization, the initializer will try to set the corresponding struct property to null. If the property is non-optional, the initializer will throw a TypeError. Make sure to declare strict types at the beginning of your script to catch these errors.

```php
class People extends Struct {
    public string $name;
}

$alice = new People(); //Uncaught TypeError: Cannot assign null to property People::$name of type string
```

$~$
### Making a typo

If we misspell the name of an argument, the initializer will assume that we want to set a dynamic property and will throw a dynamic property error.

```php
class People extends Struct {
    public string $name;
}

$alice = new People(namee: "Alice"); //Uncaught ErrorException: "Creation of dynamic property People::$namee is not allowed"
```

$~$
### Caveats and limitations

- Creating constant struct properties is not possible due to limitations in PHP:
    - using the const keyword is class level and not instance level
    - readonly property in child class cannot be initialized in parent class __construct()
- Extending structs is currently not supported. The initializer will only check properties of the current class.
- Adding a custom constructor is currently not allowed.
- Default PHP errors show incorrect line number and file name, they are triggered inside the constructor in this library which is not ideal.
