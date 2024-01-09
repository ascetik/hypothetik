# Mono

[FR]
Monade "maison", pour une gestion avancée des valeurs nulles prédictibles.

## Pourquoi ?

En bossant pour la Nième fois sur un Router compatible D.D.D.|M.V.C/REST (bah oui REST !), je me retrouvais à vérifier des valeurs nulles.
Ça casse la syntaxe du code, c'est moins facile à lire et à débugger.

J'ai re-découvert récemment les bénéfices à tirer des _monades_, concept issu de la programmation fonctionnelle.
Les différents packages trouvés sur _packagist_ ne satisfaisaient pas certaines contraintes.

Le paradigme de la programmation fonctionnelle reste intéressant même en P.O.O..
Cette approche permet de virer ces "dos d'âne" pour avoir une route bien plate et toute droite.
On dit "fais ceci dans ce cas et ça dans l'autre cas" et on arrive à gérer les valeurs nulles dans le même geste.

Cette implémentation maison de **Maybe** ne propose rien de plus que la gestion des valeurs potentiellement nulles.

## Release notes

> V 0.1.0 : draft version

- T.D.D. built, Version certainement incomplète.
- classe **Maybe**, contient une implémentation de **Option** : **Some** ou **None**
  - apply(_callable_): _mixed_ : retourne le résultat de la fonction donnée
  - either(_callable_): _Either_ : retourne une instance de **Either** contenant la fonction donnée
  - equals(_Maybe_): _bool_ : vérifie l'égalité des Options entre l'instance **Maybe** courante et l'instance donnée
  - isNull(): bool : vérifie si l'option courante est nulle
  - otherwise(_mixed_): Maybe : retourne un nouveau Maybe si l'instance courante a une option nulle
  - then(_callable_): _Maybe_ : retourne le résultat de la fonction donnée dans un nouveau Maybe
  - value(): _mixed_ : retourne la valeur de l'option du **Maybe** courant.
  - static not(): Maybe : retourne une instance de **Maybe** avec une valeur nulle
  - static of(_Option_): Maybe : retourne une instance de **Maybe** avec l'option donnée
  - static some(_mixed_): Maybe : retourne une instance de **Maybe** avec la valeur donnée
- classe **Either**, contient un Maybe, une fonction à exécuter et les arguments utiles à la fonction :
  - or(_callable_): Either : retourne un nouveau **Either** si la valeur de **Maybe** est nulle
  - orThrow(_Trowable_): Either : retourne un nouveau **Either** qui lancera une exception.
  - try(): _Maybe_ : retourne un **Maybe** contenant le résultat de la fonction contenue par **Either**
  - value(): _mixed_ : retourne la valeur contenue par le Maybe
  - static use(_Maybe_, _callable_, _...mixed_): _Either_ : récupération d'une instance de **Either**, constructeur privé
- interface **Option**, contient la valeur du **Maybe** courant :
  - apply(_callable_): _mixed_ : exécute la fonction donnée avec la valeur de l'option en paramètre et retourne le résultat
  - equals(_Option_): bool : Vérifie l'égalité entre deux instances **Option**. Comparaison non typée
  - value(): mixed : retourne la valeur de l'option

Les classes **Some** et **None** sont les deux implémentations de l'interface **Option** inclues dans ce package.
Une instance **Option** est inutile seule. C'est **Maybe** qui a le contrôle sur son option.

## Usage

### Construction

Le constructeur de **Maybe** n'est pas disponible. L'instanciation passe par 3 méthodes statiques au choix :

- la méthode statique use() qui prend en paramètre une instance d'**Option** équivalente à l'implémentations **Some** de ce package.
- la méthode statique not() renvoie une instance **Maybe** avec une option nulle.

```php
// instanciation de **Maybe** avec une valeur nulle
$not = Maybe::not(); // Maybe<null>

// Instanciation avec une valeur non nulle
$some = Maybe::some('my value'); // Maybe<string>
$someobj = Maybe::some(new MyOwnInstance()); // Maybe<MyOwnInstance>

// Instanciation avec une **Option** "maison"
$any = Maybe::of(new MyOwnStringOption('valeur quelconque')); // Maybe<string>
$anyobj = Maybe::of(new MyOwnOption(new MyOwnInstance())); // Maybe<MyOwnInstance>

```

La méthode some(), pour une valeur non nulle, affecte une instance **Some** à l'instance retournée.
La méthode not() affecte une instance de **None** à l'instance retournée.
La méthode of() affecte l'instance donnée à la place d'un **Some** pour des implémentations personnalisées.
les méthodes some() et of() vérifient le contenu proposé. Si ce dernier est nul, un **None** est affecté.

### Valeur non nulle

Pour récupérer les données, en reprenant l'instance "$some" de l'exemple précédent :

```php
echo $some->value(); // affiche "my value"

```

Pour transformer une valeur et retourner son résultat :

```php
echo $some->apply(uppercase(...)); // affiche "MY VALUE"

```

Il est aussi possible d'obtenir le résultat d'une fonction pour en récupérer une nouvelle instance Maybe contenant ce résultat :

```php
$maybeThen = $some->then(uppercase(...)); // retourne un nouveau Maybe contenant "MY VALUE"
echo $maybeThen->value(); // affiche "MY VALUE"

```

Il est donc possible d'enchainer les appels :

```php
echo $some->then(uppercase(...)) // retourne un nouveau Maybe contenant "MY VALUE"
    ->then(fn(string $value) => $value.' is not null')
    ->value(); // affiche "MY VALUE is not null"

```

### Valeurs nulles

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

## Notes/Issues

Le développement de ce package a été "Test-drivé".
Je n'ai pas encore fait de tests poussés sur les callables autres que les Closures.
Les tests sur le contenu de Maybe n'ont concerné que des chaines de caractères et des entiers.

Il n'y a pas encore de stratégie spécifique pour exécuter les fonctions.
On ne peut donc pas encore utiliser des services ou de l'injection de dépendance.

J'ai encore beaucoup de lacunes avec PHP doc.
Je ne parviens pas à utiliser les types génériques de manière à simplifier l'utilisation de Maybe.
Je ne suis même pas sûr que ce soit possible, à mmoins de faire une implémentation de **Some** par types de valeur possible.
Ce qui est débile... Enfin, ce que j'en dit...

## Next Features

Interface pour la stratégie d'exécution des callables donnés, pour pouvoir brancher un système d'injection de dépendance.
Cette interface sera peut-être à terme l'objet d'un nouveau package.

Traduction de ce README en anglais. I am ze flemme... in ze bilouque...
