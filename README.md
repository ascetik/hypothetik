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
- classe **Maybe**
    - apply(_callable_): _mixed_    : retourne le résultat de la fonction donnée
    - either(_callable_): _Either_  : retourne une instance de **Either** contenant la fonction donnée
    - equals(_Maybe_): _bool_       : vérifie l'égalité des valeurs contenues dans un autre **Maybe**
    - isNull(): bool                : vérifie si la valeur courante est nulle
    - otherwise(_mixed_): Maybe     : retourne un nouveau Maybe si l'instance courante a une valeur nulle
    - then(_callable_): _Maybe_     : retourne le résultat de la fonction donnée dans un nouveau Maybe
    - value(): _mixed_              : retourne la valeur contenue par le Maybe
    - static some(_mixed_): Maybe   : retourne une instance de **Maybe** avec la valeur donnée
    - static not(): Maybe           : retourne une instance de **Maybe** avec une valeur nulle
- classe **Either**
    - or(_callable_): Either        : retourne un nouveau **Either** si la valeur de **Maybe** est nulle
    - orCatch(_Trowable_): Either   : retourne un nouveau **Either** qui lancera une exception.
    - try(): _Maybe_                : retourne un **Maybe** contenant le résultat de la fonction contenue par **Either**
    - value(): _mixed_              : retourne la valeur contenue par le Maybe
    - static use(_Maybe_, _callable_, _...mixed_): _Either_ : récupération d'une instance de **Either**, constructeur privé
