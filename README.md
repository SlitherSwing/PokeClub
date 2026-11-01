# PokeClub
Créateur d'équipe Pokémon

## Organisation du projet

```text
public/                 Pages et fichiers servis au navigateur
src/
├── assets/             Sources du CSS
├── Model/              Classes PHP
├── Mapper/             Conversion entre les lignes SQL et les objets
├── Repository/         Requêtes vers la BDD
├── require/            Chargement commun et configuration
├── View/               Vues, formulaire partagé, en-tête et pied de page
├── scripts/            Fixtures et scripts PHP
├── docs/               Documents et notes locales
└── sql/                Fichiers SQL
vendor/                 Bibliothèques PHP installées par Composer (ignoré)
var/log/                Journaux applicatifs hors du dossier public (ignoré)
tests/                  Contrôles autonomes de la fiche 03
```

Les fichiers de gestion du projet, dont `package.json`, `composer.json` et `composer.lock`, restent à la racine. La configuration locale est dans `src/require/config.local.php` et reste exclue de Git.

## Installation

Prérequis : **PHP 8.3 minimum (branche 8.x)**, Composer, MySQL, Node.js et npm.
Les extensions PHP déclarées sont PDO, PDO MySQL, mbstring, session, filter et JSON.
Cette installation a été vérifiée avec PHP **8.5.10** et Composer **2.11.1**.
Le PHP du serveur doit lui aussi respecter ces exigences. Le PHP **8.2.4 de XAMPP**
présent sur cette machine ne convient pas au code existant : utiliser le serveur
PHP 8.5 indiqué ci-dessous, tout en gardant MySQL démarré dans XAMPP.

Vérifier les outils puis installer les bibliothèques PHP depuis le verrouillage :

```bash
php -v
composer --version
composer install
composer check-platform-reqs
```

`composer.json` décrit les bibliothèques et associe `PokeClub\` à `src/`.
`composer.lock` fixe les versions exactes ; ces deux fichiers sont versionnés.
`vendor/` est ignoré et se recrée avec `composer install`. Ne pas lancer
`composer update` pour simplement reproduire cette installation.
Le chargeur généré `vendor/autoload.php` remplace le chargeur manuel.

Créer `src/require/config.local.php` à partir de `config.example.php`, puis renseigner
la connexion MySQL locale. Ce fichier reste privé et ignoré par Git.
La base et ses tables doivent exister : `composer install` ne les crée pas.

Depuis la racine du projet, installer les dépendances et générer le CSS :

```bash
npm install
npm run build
```

## Développement local

Dans un premier terminal, démarrer Tailwind pour régénérer le CSS à chaque modification :

```bash
npm run dev
```

Dans un second terminal, vérifier `php -v` puis démarrer le serveur PHP compatible :

```bash
php -S localhost:8000 -t public
```

Sur cette machine, si la commande `php` désigne le PHP 8.2 de XAMPP,
utiliser explicitement `/opt/homebrew/bin/php -S localhost:8000 -t public`.
Le dossier servi doit être `public`, afin de ne pas exposer la configuration et les journaux.

Ouvrir <http://localhost:8000> et recharger la page après les modifications.
Utiliser `Ctrl + C` dans chaque terminal pour arrêter les processus.

Les classes Tailwind s'utilisent directement dans les fichiers PHP/HTML.
Les styles personnalisés s'ajoutent dans `src/assets/css/tailwind.css`.
Le fichier `public/assets/css/tailwind.css` est généré automatiquement : ne pas le modifier à la main.

Pour générer un CSS minifié avant publication :

```bash
npm run build
```

Le CSS généré est ignoré par Git et doit être inclus lors de la publication du dossier `public`.

Pour lancer les fixtures depuis la racine du projet :

```bash
php src/scripts/fixtures.php
```

Documentation : [installation avec Tailwind CLI](https://tailwindcss.com/docs/installation/tailwind-cli).

## Bibliothèques de la fiche 03

Versions effectivement verrouillées : **Monolog 3.12.1** et **Symfony Validator 7.4.22**.
Composer installe aussi leurs six dépendances ; le détail figure dans `composer.lock`.

- **Symfony Validator** vérifie que le nom du Pokémon est renseigné et contient au
  maximum 255 caractères. Le contrôleur transforme ses violations en messages
  français dans le formulaire. Les règles de points, de types et d’attaques restent
  dans le modèle `Pokemon`.
- **Monolog** écrit les erreurs de création, modification et lecture dans
  `var/log/pokeclub.log`. `src/require/logger.php` configure le journal ; le dossier
  est créé au premier message. Le serveur doit pouvoir y écrire. Le navigateur
  reçoit un message générique ; le journal conserve uniquement l’opération,
  le code SQLSTATE et le code du pilote, sans mot de passe ni formulaire.

## Vérifications

Depuis la racine du projet :

```bash
composer validate --strict
composer check-platform-reqs
composer dump-autoload
php tests/composer.php
php src/scripts/fixtures.php
```

`tests/composer.php` contrôle le chargement des classes, les erreurs du nom,
les limites Unicode, les règles métier conservées et les erreurs de base simulées.
Il écrit son journal dans un fichier temporaire ensuite supprimé et n’utilise
aucune base réelle. Il vérifie aussi que le journal n’inclut pas les données de test
privées présentes dans l’exception simulée.
Les fixtures affichent un exemple de Pokémon sans écrire en base.

Pour vérifier la reproductibilité, dans une **copie de test** contenant le code,
`composer.json` et `composer.lock`, supprimer uniquement son dossier `vendor/`
puis exécuter `composer install`. Les mêmes versions doivent être réinstallées.

Documentation : [Composer](https://getcomposer.org/doc/01-basic-usage.md),
[Monolog](https://github.com/Seldaek/monolog),
[Symfony Validator](https://symfony.com/doc/7.4/validation.html).
