<?php require __DIR__ . '/../header.php'; ?>

<main id="create-main" class="create-container create-main">
    <a class="create-back" href="/">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            aria-hidden="true">
            <path d="m12 5-7 7 7 7M5 12h14" />
        </svg>
        Retour à mon équipe
    </a>

    <div class="create-intro">
        <p class="create-eyebrow">LE PROCHAIN MEMBRE DE VOTRE ÉQUIPE</p>
        <h1>Créez votre Pokémon<span>.</span></h1>
        <p>Un partenaire unique, une stratégie à votre image. À vous de jouer.</p>
    </div>

    <div class="create-layout">
        <aside class="create-aside" aria-label="Conseils de création">
            <div class="create-companion">
                <span class="create-eyebrow">TOUT COMMENCE ICI</span>
                <div class="create-illustration" aria-hidden="true">
                    <svg viewBox="0 0 260 210" fill="none">
                        <circle cx="130" cy="100" r="85" stroke="#d7d9f3" stroke-dasharray="4 7" />
                        <circle cx="130" cy="100" r="66" fill="#e2e5fc" />
                        <ellipse cx="130" cy="184" rx="48" ry="7" fill="#d9ddf5" />
                        <g transform="rotate(-20 130 100)">
                            <circle cx="130" cy="100" r="51" fill="white" stroke="#343766" stroke-width="3" />
                            <path d="M79 100a51 51 0 0 1 102 0Z" fill="#6366f1" />
                            <path d="M88 73a48 48 0 0 1 49-22" stroke="#a5b4fc" stroke-width="6"
                                stroke-linecap="round" />
                            <circle cx="130" cy="100" r="51" stroke="#343766" stroke-width="3" />
                            <path d="M79 100h102" stroke="#343766" stroke-width="5" />
                            <circle cx="130" cy="100" r="17" fill="white" stroke="#343766" stroke-width="4" />
                            <circle cx="130" cy="100" r="8" fill="#eef2ff" stroke="#c7d2fe" stroke-width="2" />
                        </g>
                        <path d="M211 50v14m-7-7h14M48 131v10m-5-5h10" stroke="#818cf8" stroke-width="2.5"
                            stroke-linecap="round" />
                        <circle cx="56" cy="49" r="4" fill="#f7bc85" />
                        <circle cx="204" cy="151" r="3" fill="#a5b4fc" />
                    </svg>
                </div>
                <h2>Votre prochain<br>partenaire vous attend.</h2>
                <p>Du premier type à la dernière attaque, chaque détail compte pour construire votre équipe.</p>
                <div class="create-companion-footer"><span aria-hidden="true">✦</span><a>
                        https://pokedexia.com/fr/pokedex</a>
                </div>
            </div>
            <div class="create-tip">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                    aria-hidden="true">
                    <path d="M9 18h6m-5 3h4M8 14a6 6 0 1 1 8 0c-1 1-1 2-1 2H9s0-1-1-2Z" />
                </svg>
                <div>
                    <h2>Le conseil du coach</h2>
                    <p>Associez la nature, l’objet et les attaques de votre Pokémon pour définir son rôle dans l’équipe.
                    </p>
                </div>
            </div>
        </aside>

        <?php require __DIR__ . '/form.php'; ?>
    </div>
    <footer class="create-footer">PokeClub <span aria-hidden="true">·</span> À chaque dresseur, son équipe.</footer>
</main>

<?php require __DIR__ . '/../footer.php'; ?>
