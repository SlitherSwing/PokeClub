<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PokeClub, votre créateur d'équipe Pokémon.">
    <title><?= htmlspecialchars($pageTitle ?? 'PokeClub — Créateur d’équipe Pokémon', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/css/tailwind.css">
</head>

<body class="<?= htmlspecialchars($bodyClass ?? 'p-8', ENT_QUOTES, 'UTF-8') ?>">
    <?php if (!isset($pageTitle)) { ?>
        <h1 class="mb-4 text-3xl font-bold">Bienvenue sur PokeClub, mon gestionnaire d'équipe en ligne</h1>
    <?php } ?>
