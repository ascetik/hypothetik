# Hypothetik

[EN]

Home made OOP "Monad", for an easier management of predictible null values.

## Release notes

> Version 0.3.0 : still a draft version.

Php version : 8.2.14

1. New **Hypothetik** interface, describing the behavior of a monad included in this package.
2. **Maybe** class implements **Hypothetik** interface as well.
3. New **When**, **Hypothetik** implementation to handle booleans.

## Desciptions

_final_ class **Maybe** :
The **Maybe** class is the main tool of this package.
It handles an **Option** which may contain a value and drives different operations on this value.

- **Maybe**::apply(_callable_, _...mixed_): _mixed_ : return the result of given function
- **Maybe**::either(_callable_): _Either_ : return an instance of **Either**.
- **Maybe**::equals(_Maybe_): _bool_ : check equality with another **Maybe** instance.
- **Maybe**::isNull(): bool : check if value is null
- **Maybe**::otherwise(_mixed_): Maybe : return a new instance when Option value is null
- **Maybe**::then(_callable_, _...mixed_): _Maybe_ : return a new instance with the result of given function
- **Maybe**::value(): _mixed_ : Return raw **Option** value
- **Maybe**::static not(): Maybe : return a **Maybe** instance with null
- **Maybe**::static of(_Option_): Maybe : return a **Maybe** instance with given option
- **Maybe**::static some(_mixed_): Maybe : return a **Maybe** instance with given value

> **Maybe** contructor is private. See examples below for instanciation.

---

The **Option** interface describes the behavior of an instance containing the exepcted value :

- **Option**::apply(_callable_, _?array_): _mixed_ : return the result of given function with **Option** value as first parameter
- **Option**::equals(_Option_): bool : Check equality with another **Option**
- **Option**::value(): mixed : return **Option** value

This package includes 2 **Option** implementations : _final_ class **None** and _final_ class **Some**.
Anyone can build another implementation of **Option** to replace **Some** class.
That's why this interface is exposed here.

> An **Option** instance is not useful as is. It is a simple ValueObject with a behavior limited to its content.
> It is a **Maybe** internal value, never exposed to the user.

---

_final_ class **Either** :

The **Either** class handles a function to execute according to a **Maybe** Option value.
It contains the current **Maybe**, a callable (using ascetik/callapsule package) and an optionnal array of parameters.

- **Either**::or(_callable_, _...mixed_): Either : return an new **Either** instance if **maybe**'s value is null.
- **Either**::try(): _Maybe_ : return a new **Maybe** instance with the result of current **Either** function.
- **Either**::value(): _mixed_ : retourne la valeur contenue par le Maybe
- **Either**::static use(_Maybe_, _callable_, _...mixed_): _Either_ : récupération d'une instance de **Either**, constructeur privé

> An **Either** instance is exposed for usage. However, any instance of that type is only useful in a **Maybe** instance context.

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

// Until version 0.3.0
$truthy = Maybe::some(true); // this is a "When" instance
$falsy = Maybe::some(false); // this is a "When" instance too

```

The version 0.3.0 provides a new **When** class to use with booleans.
As **Maybe** is invalid with a null value, a **When** instance is invalid when its value is _false_.
More descriptions below...

### Valid value : mixed value not null

To retrieve the value from the "$some" **Maybe** instance of previous example :

```php
echo $some->value(); // "my value"

```

To return the result of a function using the Option value as parameter :

```php

echo $some->apply(strtoupper(...)); // "MY VALUE"

```

It is possible to add arguments, separated by comas.
In this case, there are two restrictions :

- The Option value MUST be the first parameter of the function.
- The order of the arguments must match with the other function parameters.

Another example to illustrate those restrictions :

```php

$pathToAboutPage = Maybe::some('/about');
echo $pathToAboutPage->apply(trim(...), '/'); // "about", without forward slash

$function = fn(string $value, string $separator, string $add)=> trim($value, $separator) . '-' . $add
echo $pathToAboutPage->apply($function, '/','page' ); //"about-page"

```

The **Maybe** value is able to return a new instance of himself with the result of a function.
The current instance value is passed to the given function :

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

Just like _apply()_ method, _then()_ can accept some other arguments.
Those will be appended after the main value as parameters.

### Invalid value : null value

With a null value, things are slightly different.
Both _apply()_ and _value()_ methods will return null again.
The _then()_ method returns a Maybe with a null Option value.

Let's take a look at the "$now" instance from the first example :

```php
echo $not->value(); // prints nothing because null
echo $not->apply(strtoupper(...)); // null too
echo $not->then(strtoupper(...))->value(); // still null

```

**Maybe** provides a way to substitute a falsy instance to a truthy one by using _otherwise_ method :

```php

$otherwise = $not->otherwise('nothing');
echo $otherwise->value(); // prints "nothing"
echo $otherwise->apply(strtoupper(...)); // prints "NOTHING"
echo $otherwise->then(strtoupper(...))->value(); // "NOTHING" again.

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

And to retrieve a new instance of **Maybe** with **Either** function result :

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

## Boolean value : When<bool>

There are nothing special to say but the fact that it handles boolean values.
Every methods work the same way. The main difference is that now, a boolean value
as methods to execute a task weither it is set to true or not.

Here's a simple example :

```php
$phrase = 'this is just a test';

$when = Maybe::some(str_contains($phrase, 'just'));
echo $when->value() ? 'valid' : 'invalid'; // 'valid'
$whenNot = Maybe::some(str_contains($phrase, 'only'));
echo $whenNot->value() ? 'valid' : 'invalid'; // 'invalid'

```

**When** class has its own static factory method :

```php
$when = When::ever(true); // or false...

```

It's possible to combine **Maybe** and **When** instance calls :

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

No dependency injection. User has to provide required instances.
A **Maybe** cannot carry another **Hypothetik**, an **Option** cannot carry another **Option**. Trying to do so will return the given instance as is.

## Issues

I'm still not able to use Php Documentation in order to provide autocompletion with any IDE.
Problems on generic type handling.

I still don't need any **Hypothetik** container to handle multiple **Hypothetik** instances.
I'll think about this kind of implementation only if necessary...

Maybe some tests are still missing. I'm not sure to cover all possible use cases.
