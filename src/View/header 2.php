<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PokeClub, votre créateur d'équipe Pokémon.">
    <title><?= htmlspecialchars($pageTitle ?? 'PokeClub — Créateur d’équipe Pokémon', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/css/tailwind.css">
</head>

<body class="<?= htmlspecialchars($bodyClass ?? 'pokemon-page', ENT_QUOTES, 'UTF-8') ?>">
    <?php if (isset($mainContentId)) { ?>
        <a class="create-skip-link" href="#<?= htmlspecialchars($mainContentId, ENT_QUOTES, 'UTF-8') ?>">Aller au contenu</a>
    <?php } ?>

<header class="create-header">
    <div class="create-container flex items-center justify-between gap-4">
        <a class="create-brand" href="/" aria-label="PokeClub, accueil">
            <svg width="30" height="30" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                <circle cx="16" cy="16" r="14" fill="#eef2ff" stroke="currentColor" stroke-width="2.5" />
                <path d="M2 16a14 14 0 0 1 28 0Z" fill="currentColor" />
                <path d="M2 16h28" stroke="currentColor" stroke-width="2.5" />
                <circle cx="16" cy="16" r="5" fill="white" stroke="currentColor" stroke-width="2.5" />
            </svg>
            <span>PokeClub<span class="create-brand-dot">.</span></span>
        </a>
        <nav class="create-nav" aria-label="Navigation principale">
            <a href="/"<?= ($activePage ?? null) === 'team' ? ' aria-current="page"' : '' ?>>Mon équipe</a>
            <a href="/pokepage/create.php"<?= ($activePage ?? null) === 'create' ? ' aria-current="page"' : '' ?>>Créer un Pokémon</a>
        </nav>
    </div>
</header>
