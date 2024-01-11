# Mono

[EN]

Home made OOP "Monad", for an easier management of predictible null values.

## Release notes

> V 0.1.0 : draft version.

T.D.D. built

## Desciptions

_final_ class **Maybe** :
The **Maybe** class is the main tool of this package.
It handles an **Option** which may contain a value and drives different operations on this value.

**Maybe**::apply(_callable_, _...mixed_): _mixed_ : return the result of given function
**Maybe**::either(_callable_): _Either_ : return an instance of **Either**.
**Maybe**::equals(_Maybe_): _bool_ : check equality with another **Maybe** instance.
**Maybe**::isNull(): bool : check if value is null
**Maybe**::otherwise(_mixed_): Maybe : return a new instance when Option value is null
**Maybe**::then(_callable_, _...mixed_): _Maybe_ : return a new instance with the result of given function
**Maybe**::value(): _mixed_ : Return raw **Option** value
**Maybe**::static not(): Maybe : return a **Maybe** instance with null
**Maybe**::static of(_Option_): Maybe : return a **Maybe** instance with given option
**Maybe**::static some(_mixed_): Maybe : return a **Maybe** instance with given value

> **Maybe** contructor is private. See examples below for instanciation.

---

The **Option** interface describes the behavior of an instance containing the exepcted value :

**Option**::apply(_callable_, _?array_): _mixed_ : return the result of given function with **Option** value as first parameter
**Option**::equals(_Option_): bool : Check equality with another **Option**
**Option**::value(): mixed : return **Option** value

This package includes 2 **Option** implementations : _final_ class **None** and _final_ class **Some**.
Anyone can build another implementation of **Option** to replace **Some** class.
That's why this interface is exposed here.

> An **Option** instance is not useful as is. It is a simple ValueObject with a behavior limited to its content.
> It is a **Maybe** internal value, never exposed to the user.

---

_final_ class **Either** :

The **Either** class handles a function to execute according to a **Maybe** Option value.
It contains the current **Maybe**, a callable (using ascetik/callapsule package) and an optionnal array of parameters.

**Either**::or(_callable_, _...mixed_): Either : return an new **Either** instance if **maybe**'s value is null.
**Either**::orThrow(_Trowable_): Either : return a new **Either** instance which will throw given Exception.
**Either**::try(): _Maybe_ : return a new **Maybe** instance with the result of current **Either** function.
**Either**::value(): _mixed_ : retourne la valeur contenue par le Maybe
**Either**::static use(_Maybe_, _callable_, _...mixed_): _Either_ : récupération d'une instance de **Either**, constructeur privé

> An **Either** instance is exposed for usage. However, any instance of that type is only useful in a **Maybe** instance context.

## Usage

### Construction

As **Maybe** constructor access is not available, 3 factory methods are available :

- static method not() returning a **Maybe<null>**.
- static method some($value) asking for a mixed parameter type and return an instance like **Maybe<typeof $value>**
- static method of() asking for an implementation of **Option**, opened to extension. Build your own **Option** with dependency injection, for example.


```php
$not = Maybe::not(); // Maybe<null>

$some = Maybe::some('my value'); // Maybe<string>
$someobj = Maybe::some(new MyOwnInstance()); // Maybe<MyOwnInstance>
$nullAnyway = Maybe::some(null); // Maybe<null>

$any = Maybe::of(new MyOwnStringOption('any string value')); // Maybe<string>
$anyobj = Maybe::of(new MyOwnOption(new MyOwnInstance())); // Maybe<MyOwnInstance>
$anyNullObj = Maybe::of(new MyOwnNullOption()); // Maybe<null>

```

### Truthy Maybe : mixed value not null

To retrieve the value from the "$some" **Maybe** instance of previous example :

```php
echo $some->value(); // "my value"

```

To return the result of a function using the Option value as parameter :

```php

echo $some->apply(uppercase(...)); // "MY VALUE"

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
$maybeThen = $some->then(uppercase(...)); // retourne un nouveau Maybe contenant "MY VALUE"
echo $maybeThen->value(); // affiche "MY VALUE"

```
As a new **Maybe** instance is returned, we can chain calls of this method :

```php
echo $some->then(uppercase(...)) // return a new Maybe containing "MY VALUE"
    ->then(fn(string $value) => $value.' is not null')
    ->value(); // "MY VALUE is not null"

```
Just like _apply()_ method, _then()_ can accept some other arguments.
Those will be appended after the main value as parameters.

### Falsy Maybe : null value

Dans le cas de valeurs nulles, le comportement diffère. C'est là tout l'intérêt.
C'est aussi l'occasion d'en découvrir davantage sur les fonctionnalités proposées.

Reprenons l'instance "$not" du premier exemple :

```php
echo $not->value(); // n'affiche rien car null
echo $not->apply(uppercase(...)); // null aussi
echo $not->then(uppercase(...))->value(); // toujours null

```

Mais il existe une alternative pour continuer dans ce cas :

```php

$otherwise = $not->otherwise('nothing');
echo $otherwise->value(); // affiche "nothing"
echo $otherwise->apply(uppercase(...)); // affiche "NOTHING"
echo $otherwise->then(uppercase(...))->value(); // toujours "NOTHING"

```

Voici quelques exemples plus concrets :

```php

echo $not->then(uppercase(...))
    ->otherwise('i replace null')
    ->then(fn(string $value) => $value . ' for demonstration')
    ->value(); // affiche "i replace null for demonstration"

echo $not->otherwise('i replace null')
    ->then(uppercase(...))
    ->then(fn(string $value) => $value . ' for demonstration')
    ->value(); // affiche "I REPLACE NULL for demonstration"

```

On peut bien sûr utiliser la méthode _otherwise_ pour une valeur non nulle :

```php
echo $some->otherwise('my other value')
    ->then(uppercase(...))
    ->then(fn(string $value) => $value . ' for demonstration')
    ->value(); // affiche "MY VALUE for demonstration"

```

Ces exemples utilisent des instances dont on connait déjà le contenu.
Dans la réalité, on ne peut que supposer le contenu de Maybe au moment où on l'utilise, d'où l'intérêt de cette méthode.

Il existe une autre manière de gérer le retour d'un Maybe selon son Option avec **Either**.
Une instance **Either** exécute une fonction pour une valeur non nulle et une autre pour une valeur nulle.
Voilà l'illustration de son fonctionnement :

```php

echo $some->either(toUpperCase(...))
    ->or(fn() => 'late value')
    ->value(); // affiche "MY VALUE"

echo $not->either(toUpperCase(...))
    ->or(fn() => 'late value')
    ->value(); // affiche "late value"

```

Il est aussi possible d'en obtenir un nouveau **Maybe** :

```php

echo $some->either(toUpperCase(...))
    ->or(fn() => 'late value')
    ->try()    // celui-ci retourne un Maybe contenant le résultat de la fonction exécutée
    ->then(fn(string $value) => $value . ' for demonstration')
    ->value(); // affiche "MY VALUE for demonstration"

echo $not->either(toUpperCase(...))
    ->or(fn() => 'late value')
    ->try()
    ->then(fn(string $value) => $value . ' for demonstration')
    ->value(); // affiche "late value for demonstration"

```

Pour les dingues du try/catch, la méthode orCatch() permet de lancer une Exception donnée :

```php

echo $some->either(toUpperCase(...))
    ->orThrow(new Exception('no string to work with...'))
    ->value(); // affiche bien "my value"

echo $not->either(toUpperCase(...))
    ->orThrow(new Exception('no string to work with...'))
    ->value(); // lance l'Exception donnée

```

## Notes

Le développement de ce package a été "Test-drivé".
Je n'ai pas encore fait de tests poussés sur les callables autres que les Closures.
Les tests sur le contenu de Maybe n'ont concerné que des chaines de caractères et des entiers.

Cette librairie n'utilise pas de système d'injection de dépendance. Il appartient à l'utilisateur de livrer les instances nécessaires si besoin.

## Issues

Quelques difficultés ont été rencontrées lors de tentatives de documentation efficace du code.
Je ne sais pas encore gérer les types génériques en PHP DOC.
Ça ne gène pas l'exécution du code, évidemment, mais on ne bénéficie pas de l'autocomplétion sur les différents IDEs.

Je ne veux pas d'une implémentation de **Option** par type possible.

## Next Features

Traduction de ce README en anglais.
