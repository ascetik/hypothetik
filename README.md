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

Version certainement incomplète.
T.D.D. built, concept très utile, comme bien des fois...
