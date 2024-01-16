# Hypothetik

[EN]

Home made OOP "Monad", for an easier management of hypothetical values.

## Release notes

> Version 0.3.0 : still a draft version.

Php version : 8.2.14

- New **Hypothetik** interface, describing the behavior of a monad included in this package.
- **Maybe** class implements **Hypothetik** interface
- New **When**, **Hypothetik** implementation to handle booleans.

## Descriptions

### Interfaces

**OptionnalValue** is a general interface shared by both **Hypothetik** an **Option** instances.

- **OptionnalValue**::isValid(): _bool_ : check validity of a value (!null & !false)
- **OptionnalValue**::value(): _mixed_ : return **Option** raw value

---

The **Hypothetik** interface describes the way to handle a value which may be null or false using callables.

- **Hypothetik**::apply(_callable_, _...mixed_): _mixed_ : return the result of given callable using the **Option** value
- **Hypothetik**::either(_callable_, _...mixed_): _Either_ : return an **Either** instance according to an **Option**.
- **Hypothetik**::then(_callable_, _...mixed_): _Hypothetik_ : return a new **Hypothetic** instance with the result of given callable.
- **Hypothetik**::otherwise(_mixed_): _Hypothetik_ : choose an alternative to return if the **Option** value is invalid.

---

The **Option** interface describes the behavior of an instance containing the exepcted value :

- **Option**::apply(_callable_, _?array_): _mixed_ : return the result of given function with **Option** value as first parameter
- **Option**::equals(_Option_): bool : Check equality with another **Option**
- **Option**::isValid(): _bool_ : see **OptionnalValue** interface
- **Option**::value(): mixed : see **OptionnalValue** interface

This package includes 2 **Option** implementations : _final_ class **None** and _final_ class **Some**.
Anyone can build another implementation of **Option** to replace **Some** class.
That's why this interface is exposed here.

> An **Option** is a simple ValueObject with simple behaviors, unuseful outside of an **Hypothetik** instance.

### Available Implementations

_final_ class **Maybe** :
The **Maybe** class is the main tool of this package.
It handles an **Option** which may contain a value, or may not, and drives different operations on this value, or not...

- **Maybe**::equals(_Maybe_): _bool_ : check equality with another **Maybe** instance.
- **Maybe**::apply(_callable_, _...mixed_): _mixed_ : see **Hypothetik** interface
- **Maybe**::either(_callable_): _Either_ : see **Hypothetik** interface
- **Maybe**::isValid(): _bool_ : see **OptionnalValue** interface
- **Maybe**::otherwise(_mixed_): _Hypothetik_ : see **Hypothetik** interface
- **Maybe**::then(_callable_, _...mixed_): _Hypothetik_ : see **Hypothetik** interface
- **Maybe**::value(): _mixed_ : see **OptionnalValue** interface
- static **Maybe**::not(): _Maybe_ : return a **Maybe** instance with a **None** option
- static **Maybe**::of(_Option_): _Maybe_ : return a **Maybe** instance with given **Option** instance.
- static **Maybe**::some(_mixed_): _Hypothetik_ : return a **Hypothetik** instance with given value

> **Maybe** contructor is private. See examples below for instanciation.

---

_final_ class **When** : (v.0.3.0)
This implementation works almost like **Maybe**. The difference is that **When** contains an **Option** with a bool value and a falsy **Option** is considered as invalid.

- **When**::apply(_callable_, _...mixed_): _mixed_ : see **Hypothetik** interface
- **When**::either(_callable_): _Either_ : see **Hypothetik** interface
- **When**::isValid(): _bool_ : see **OptionnalValue** interface
- **When**::otherwise(_mixed_): _Hypothetik_ : see **Hypothetik** interface
- **When**::then(_callable_, _...mixed_): _Hypothetik_ : see **Hypothetik** interface
- **When**::value(): _mixed_ : see **OptionnalValue** interface
- static **When**::ever(_bool_): _When_ : return a **Maybe** instance with given value

> Private constructor. Use **When**::ever(_bool_) or **Maybe**::some(_bool_) methods to build an instance.

---

_final_ class **Either** :

The **Either** class handles a function to execute according to a **Maybe** Option value.

- **Either**::or(_callable_, _...mixed_): Either : return an new **Either** instance if **maybe**'s value is null.
- **Either**::try(): _Maybe_ : return a new **Maybe** instance with the result of current **Either** function.
- **Either**::value(): _mixed_ : retourne la valeur contenue par le Maybe
- **Either**::static use(_Maybe_, _callable_, _...mixed_): _Either_ : récupération d'une instance de **Either**, constructeur privé

> An **Either** instance is exposed by a **Hypothetik** implementation for usage, unuseful in any other context.

---

_final_ class **None** is a "null value" **Option**.
_final_ class **Some** is a not null value **Option**.

## Usage

### Construction

As **Maybe** constructor access is not available, 3 factory methods are provided :

```php
$not = Maybe::not(); // Maybe<null>

$some = Maybe::some('my value'); // Maybe<string>
$someobj = Maybe::some(new MyOwnInstance()); // Maybe<MyOwnInstance>
$nullAnyway = Maybe::some(null); // Maybe<null>

$any = Maybe::of(new MyOwnStringOption('any string value')); // Maybe<string>
$anyobj = Maybe::of(new MyOwnOption(new MyOwnInstance())); // Maybe<MyOwnInstance>
$anyNullObj = Maybe::of(new MyOwnNullOption()); // Maybe<null>

// version 0.3.0
$truthy = Maybe::some(true); // this is a truthy "When" instance
$falsy = Maybe::some(false); // this is a falsy "When" instance

```

### Valid value : mixed value not null

To retrieve raw optionnal value from the "$some" **Maybe** instance of previous example :

```php
echo $some->value(); // "my value"

```

Pass an optionnal value through a function an get the result :

```php

echo $some->apply(strtoupper(...)); // "MY VALUE"

```

The **Option** value is always passed to the function as first parameter.

It is possible to add arguments, separated by comas. The order of the arguments is important.

Another example with some added arguments :

```php

$pathToAboutPage = Maybe::some('/about');
echo $pathToAboutPage->apply(trim(...), '/'); // "about", without forward slash

$function = fn(string $value, string $separator, string $add)=> trim($value, $separator) . '-' . $add
echo $pathToAboutPage->apply($function, '/','page' ); //"about-page"

```

It is possible to get a new **Hypothetik** instance containing the result of a function.
Once again, the **Option** value is always passed to the function as first parameter and arguments can be added :

```php
$maybeThen = $some->then(strtoupper(...)); // retourne un nouveau Maybe contenant "MY VALUE"
echo $maybeThen->value(); // affiche "MY VALUE"

```

As a new **Maybe** instance is returned, we can chain calls of this method :

```php
echo $some->then(strtoupper(...)) // return a new Maybe containing "MY VALUE"
    ->then(fn(string $value) => $value.' is not null')
    ->value(); // "MY VALUE is not null"

```

### Invalid value : null value

With a null value, things are slightly different.
Both _apply()_ and _value()_ methods will return null again.
The _then()_ method returns a Maybe with a null Option value.

Take a look at the "$not" instance from the first example :

```php
echo $not->value(); // prints nothing because null
echo $not->apply(strtoupper(...)); // null too, function is not applied
echo $not->then(strtoupper(...))->value(); // still null

```

**Maybe** provides a way to substitute an "invalid" instance to a valid one by using _otherwise_ method :

```php

$otherwise = $not->otherwise('nothing');
echo $otherwise->value(); // prints "nothing"
echo $otherwise->apply(strtoupper(...)); // prints "NOTHING"
echo $otherwise->then(strtoupper(...)) // // Maybe<'NOTHING'>
               ->value(); // "NOTHING" again.

```

Some other examples chaining methods :

```php

echo $not->then(strtoupper(...)) // run strtoupper with a Maybe<null> won't work
    ->otherwise('i replace null') // new Maybe<string> available after first then() call
    ->then(fn(string $value) => $value . ' for demonstration') // run the function with the new instance
    ->value(); // prints "i replace null for demonstration"

echo $not->otherwise('i replace null') // new Maybe<string> available
    ->then(strtoupper(...)) // now transform initial string to upper case
    ->then(fn(string $value) => $value . ' for demonstration') // and append another string to the previous value
    ->value(); // prints "I REPLACE NULL for demonstration"

```

The _otherwise_ method is only applied when the value is null. So :

```php
echo $some->otherwise('my other value') // initial $some instance returned
    ->then(strtoupper(...))
    ->then(fn(string $value) => $value . ' for demonstration')
    ->value(); // prints "MY VALUE for demonstration"

```

Of course, we already know the content of the instances of the examples above.
During runtime, we just can suppose that our value could be null.
Sometimes, _then()_ and _otherwise()_ are not enough to make the job we want to.
Another possibility is to use _either()_ :

```php
// with Some value
echo $some->either(toUpperCase(...))
    ->or(fn() => 'late value')
    ->value(); // prints "MY VALUE"

// with None value
echo $not->either(toUpperCase(...))
    ->or(fn() => 'late value')
    ->value(); // prints "late value"

```

And to retrieve a new **Hypothetik** instance from **Either** :

```php

// with Some value
echo $some->either(toUpperCase(...))
    ->or(fn() => 'late value')
    ->try()    // returns a Maybe<string> from "$some" value
    ->then(fn(string $value) => $value . ' for demonstration')
    ->value(); // prints "MY VALUE for demonstration"

// with None value
echo $not->either(toUpperCase(...)) // won't run this function
    ->or(fn() => 'late value') // returns a new Either instance holding this new function
    ->try() // returns a Maybe<string> with "late value'
    ->then(fn(string $value) => $value . ' for demonstration') // append a string and return another Maybe with new complete string
    ->value(); // prints "late value for demonstration"

```

## Boolean value :

An hypothetik boolean value works a different way.
It always holds an **Option** with a boolean value where false is the invalid one.
The **Hypothetik** interface ensures a fully substitutionnable instance,
providing the hability to chain methods from a **Maybe** to a **When** or reverse.

Here's a simple example :

```php
$phrase = 'this is just a test';

$when = Maybe::some(str_contains($phrase, 'just')); // truthy When
echo $when->value() ? 'valid' : 'invalid'; // 'valid'
$whenNot = Maybe::some(str_contains($phrase, 'only')); // falsy When
echo $whenNot->value() ? 'valid' : 'invalid'; // 'invalid'

```
**When** class has its own static factory method :

```php
$when = When::ever(true); // or false...

```

Methods _apply()_ and _then_ won't use the boolean value as first function parameter, this time.
Additionnal rguments are allowed, separated by comas, just like **Maybe**.

Combining **Maybe** and **When** instance calls :

```php
$truthyWhen = Maybe::some('/about') // instance of Maybe</about>
    ->then(fn (string $value) => str_starts_with($value, '/')) // instance of When<false>
    ->either(fn() => 'truthy result') // will be executed
    ->or(fn() => 'falsy result') // won't be executed
    ->try(); // Maybe<'truthy result'>
echo $when->value(); // 'truthy result'

$falsyWhen = Maybe::some('/about') // instance of Maybe</about>
    ->then(fn (string $value) => trim($value, '/')) // instance of Maybe<about>
    ->then(fn (string $value) => str_starts_with($value, '/')) // instance of When<false>
    ->either(fn() => 'truthy result') // won't be executed
    ->or(fn() => 'falsy result') // will be executed
    ->value(); // raw value
echo $when; // 'falsy result'

```

## Notes

No dependency injection. User has to provide required instances if needed.
A **Maybe** cannot carry another **Hypothetik**, an **Option** cannot carry another **Option**. Trying to do so will return the given instance as is.

## Issues

I'm still not able to use Php Documentation properly in order to provide autocompletion from any IDE.
Problems on generic types handling.

I still don't need any **Hypothetik** container to handle multiple **Hypothetik** instances.
I'll think about this kind of implementation only if necessary...

Maybe some tests are still missing. I'm not sure to cover all possible use cases.
