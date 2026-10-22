<?php require __DIR__ . '/header.php'; ?>

<main id="home-main" class="create-container home-main">
    <div class="home-intro">
        <div class="create-intro">
            <p class="create-eyebrow">VOTRE ESPACE DE DRESSEUR</p>
            <h1>Votre équipe, Votre stratégie<span>.</span></h1>
            <p>Rassemblez vos partenaires et trouvez la combinaison qui vous ressemble.</p>
        </div>
        <a class="create-submit" href="/pokepage/create.php">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                aria-hidden="true">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Créer un Pokémon
        </a>
    </div>

    <section class="home-overview" aria-label="Votre équipe en un coup d’œil">
        <div class="home-overview-intro">
            <div class="home-team-slots" aria-hidden="true">
                <?php for ($slot = 0; $slot < 6; $slot++) { ?>
                    <span class="home-slot<?= $slot < count($pokemons) ? ' is-filled' : '' ?>"></span>
                <?php } ?>
            </div>
            <h2><?= count($pokemons) === 6 ? 'L’équipe est au complet.' : 'Chaque partenaire fait la différence.' ?>
            </h2>
            <p>Pensez à varier les types pour une équipe équilibrée.</p>
        </div>
        <dl class="home-metrics">
            <div>
                <dt>Pokémon</dt>
                <dd><?= count($pokemons) ?><span> / 6</span></dd>
            </div>
            <div>
                <dt>Types représentés</dt>
                <dd><?= count($teamTypes) ?></dd>
            </div>
            <div>
                <dt>Places disponibles</dt>
                <dd><?= max(0, 6 - count($pokemons)) ?></dd>
            </div>
        </dl>
    </section>

    <section class="home-team" aria-labelledby="team-title">
        <div class="home-section-heading">
            <div>
                <p class="create-eyebrow">MON ÉQUIPE</p>
                <h2 id="team-title"><?= $team !== null ? e($team->getName()) : 'Votre prochaine équipe' ?></h2>
            </div>
            <a class="home-library-link" href="/pokepage/list.php">Voir tous les Pokémon <span
                    aria-hidden="true">↗</span></a>
        </div>

        <?php if (count($pokemons) === 0) { ?>
            <div class="home-empty">
                <span class="home-empty-icon" aria-hidden="true">＋</span>
                <h3>Une belle aventure commence ici.</h3>
                <p>Votre équipe n’a pas encore de Pokémon. Découvrez les Pokémon disponibles ou créez votre premier
                    partenaire.</p>
                <a class="create-submit" href="/pokepage/create.php">Créer mon premier Pokémon</a>
            </div>
        <?php } else { ?>
            <div class="home-pokemon-grid">
                <?php foreach ($pokemons as $index => $pokemonTeam) {
                    $pokemon = $pokemonTeam->getPokemon();
                    $points = $pokemon->getEffortPoints();
                    ?>
                    <article class="home-pokemon-card" aria-labelledby="pokemon-<?= $index ?>">
                        <div class="home-pokemon-art">
                            <span class="home-pokemon-position">PARTENAIRE
                                <?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                            <svg class="home-pokemon-placeholder" viewBox="0 0 100 100" fill="none" aria-hidden="true">
                                <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="2" />
                                <path d="M10 50h80" stroke="currentColor" stroke-width="2" />
                                <circle cx="50" cy="50" r="13" fill="#f0f1fc" stroke="currentColor" stroke-width="2" />
                            </svg>
                            <?php if ($pokemon->getPicture() !== '') { ?>
                                <img class="home-pokemon-image" src="<?= e($pokemon->getPicture()) ?>"
                                    alt="<?= e($pokemon->getName()) ?>" width="140" height="140" loading="lazy" />
                            <?php } ?>
                        </div>
                        <div class="home-pokemon-content">
                            <div class="home-pokemon-heading">
                                <h3 id="pokemon-<?= $index ?>"><?= e($pokemon->getName()) ?></h3>
                                <p class="home-role"><?= e($pokemonTeam->getRole()) ?></p>
                            </div>
                            <ul class="home-types" aria-label="Types">
                                <?php foreach ($pokemon->getTypes() as $type) { ?>
                                    <li><?= e($type->label()) ?></li>
                                <?php } ?>
                            </ul>
                            <dl class="home-pokemon-details">
                                <div>
                                    <dt>Objet tenu</dt>
                                    <dd><?= $pokemon->getItem() !== '' ? e($pokemon->getItem()) : 'Aucun objet' ?></dd>
                                </div>
                                <div>
                                    <dt>Nature</dt>
                                    <dd><?= e($pokemon->getNature()->label()) ?></dd>
                                </div>
                            </dl>
                            <h4 class="home-card-label">ATTAQUES</h4>
                            <?php if (count($pokemon->getMoves()) === 0) { ?>
                                <p class="home-no-moves">Aucune attaque renseignée.</p>
                            <?php } else { ?>
                                <ul class="home-moves">
                                    <?php foreach ($pokemon->getMoves() as $move) { ?>
                                        <li><?= e($move) ?></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                            <dl class="home-stats" aria-label="Statistiques">
                                <?php foreach ($statLabels as $key => $label) { ?>
                                    <div>
                                        <dt><span class="create-stat-dot stat-<?= e($key) ?>"
                                                aria-hidden="true"></span><?= e($label) ?></dt>
                                        <dd><?= e((string) $points[$key]) ?></dd>
                                    </div>
                                <?php } ?>
                            </dl>
                        </div>
                    </article>
                <?php } ?>
            </div>
        <?php } ?>
    </section>

    <aside class="home-coach">
        <span class="home-coach-icon" aria-hidden="true">✦</span>
        <div>
            <h2>Le conseil du coach</h2>
            <p>Faites des matchs, c'est l'expérience qui vous donnera la maîtrise de votre équipe.</p>
        </div>
    </aside>
    <footer class="create-footer">PokeClub <span aria-hidden="true">·</span> À chaque dresseur, son équipe.</footer>
</main>






<?php require __DIR__ . '/footer.php'; ?>
