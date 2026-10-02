# PokeClub
Créateur d'équipe Pokémon

## Installation

Prérequis : PHP, Node.js et npm.

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

Dans un second terminal, démarrer le serveur PHP :

```bash
php -S localhost:8000 -t public
```

Ouvrir <http://localhost:8000> et recharger la page après les modifications.
Utiliser `Ctrl + C` dans chaque terminal pour arrêter les processus.

Les classes Tailwind s'utilisent directement dans les fichiers PHP/HTML.
Les styles personnalisés s'ajoutent dans `assets/css/tailwind.css`.
Le fichier `public/assets/css/tailwind.css` est généré automatiquement : ne pas le modifier à la main.

Pour générer un CSS minifié avant publication :

```bash
npm run build
```

Le CSS généré est ignoré par Git et doit être inclus lors de la publication du dossier `public`.

Documentation : [installation avec Tailwind CLI](https://tailwindcss.com/docs/installation/tailwind-cli).
