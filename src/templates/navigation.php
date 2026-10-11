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
            <a href="/"<?= $activePage === 'team' ? ' aria-current="page"' : '' ?>>Mon équipe</a>
            <a href="/pokepage/create.php"<?= $activePage === 'create' ? ' aria-current="page"' : '' ?>>Créer un Pokémon</a>
        </nav>
    </div>
</header>
