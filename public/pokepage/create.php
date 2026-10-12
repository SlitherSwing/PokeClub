<?php
require __DIR__ . '/../../src/require/bootstrap.php';
$pageTitle = 'Créer un Pokémon — PokeClub';
$bodyClass = 'pokemon-create-page';
$activePage = 'create';
$mainContentId = 'create-main';
require __DIR__ . '/../../src/templates/header.php';
?>

<main id="create-main" class="create-container create-main">
    <a class="create-back" href="/">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m12 5-7 7 7 7M5 12h14" /></svg>
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
                            <path d="M88 73a48 48 0 0 1 49-22" stroke="#a5b4fc" stroke-width="6" stroke-linecap="round" />
                            <circle cx="130" cy="100" r="51" stroke="#343766" stroke-width="3" />
                            <path d="M79 100h102" stroke="#343766" stroke-width="5" />
                            <circle cx="130" cy="100" r="17" fill="white" stroke="#343766" stroke-width="4" />
                            <circle cx="130" cy="100" r="8" fill="#eef2ff" stroke="#c7d2fe" stroke-width="2" />
                        </g>
                        <path d="M211 50v14m-7-7h14M48 131v10m-5-5h10" stroke="#818cf8" stroke-width="2.5" stroke-linecap="round" />
                        <circle cx="56" cy="49" r="4" fill="#f7bc85" />
                        <circle cx="204" cy="151" r="3" fill="#a5b4fc" />
                    </svg>
                </div>
                <h2>Votre prochain<br>partenaire vous attend.</h2>
                <p>Du premier type à la dernière attaque, chaque détail compte pour construire votre équipe.</p>
                <div class="create-companion-footer"><span aria-hidden="true">✦</span> Pensez à la complémentarité des types.</div>
            </div>
            <div class="create-tip">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M9 18h6m-5 3h4M8 14a6 6 0 1 1 8 0c-1 1-1 2-1 2H9s0-1-1-2Z" /></svg>
                <div><h2>Le conseil du coach</h2><p>Associez la nature, l’objet et les attaques de votre Pokémon pour définir son rôle dans l’équipe.</p></div>
            </div>
        </aside>

        <form action="create.php" method="get" class="create-form">
            <section class="create-section" aria-labelledby="identity-title">
                <div class="create-section-heading">
                    <span class="create-section-number" aria-hidden="true">01</span>
                    <div><h2 id="identity-title">L’identité</h2><p>Faisons les présentations.</p></div>
                </div>
                <div class="create-fields">
                    <div class="create-field">
                        <label for="name">Nom du Pokémon</label>
                        <input type="text" id="name" name="name" placeholder="Ex. : Gardevoir" />
                    </div>
                    <div class="create-field">
                        <label for="nature">Nature</label>
                        <select id="nature" name="nature">
                            <?php foreach (PokemonNature::cases() as $nature) { ?>
                                <option value="<?= e($nature->value) ?>"><?= e($nature->label()) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="create-field create-field-wide">
                        <label for="picture">Image du Pokémon</label>
                        <input type="url" id="picture" name="picture" placeholder="https://exemple.com/pokemon.png" aria-describedby="picture-help" />
                        <p class="create-field-help" id="picture-help">Collez le lien direct vers une image de votre Pokémon.</p>
                    </div>
                    <div class="create-field">
                        <label for="type-1">Type principal</label>
                        <select id="type-1" name="type-1">
                            <?php foreach (PokemonType::cases() as $type) { ?>
                                <option value="<?= e($type->value) ?>"><?= e($type->label()) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="create-field">
                        <label for="type-2">Type secondaire <span>facultatif</span></label>
                        <select id="type-2" name="type-2">
                            <option value="">Aucun</option>
                            <?php foreach (PokemonType::cases() as $type) { ?>
                                <option value="<?= e($type->value) ?>"><?= e($type->label()) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </section>

            <section class="create-section" aria-labelledby="moves-title">
                <div class="create-section-heading">
                    <span class="create-section-number" aria-hidden="true">02</span>
                    <div><h2 id="moves-title">Le set de combat</h2><p>Un objet, quatre attaques, votre stratégie.</p></div>
                </div>
                <div class="create-fields">
                    <div class="create-field create-field-wide">
                        <label for="item">Objet tenu</label>
                        <input type="text" id="item" name="item" placeholder="Ex. : Restes" />
                    </div>
                    <div class="create-field">
                        <label for="move-1">Attaque 1</label>
                        <input type="text" id="move-1" name="move-1" placeholder="Ex. : Pouvoir Lunaire" />
                    </div>
                    <div class="create-field">
                        <label for="move-2">Attaque 2</label>
                        <input type="text" id="move-2" name="move-2" placeholder="Ex. : Psyko" />
                    </div>
                    <div class="create-field">
                        <label for="move-3">Attaque 3</label>
                        <input type="text" id="move-3" name="move-3" placeholder="Ex. : Plénitude" />
                    </div>
                    <div class="create-field">
                        <label for="move-4">Attaque 4</label>
                        <input type="text" id="move-4" name="move-4" placeholder="Ex. : Ball’Ombre" />
                    </div>
                </div>
            </section>

            <section class="create-section" aria-labelledby="stats-title">
                <div class="create-section-heading">
                    <span class="create-section-number" aria-hidden="true">03</span>
                    <div><h2 id="stats-title">Les statistiques</h2><p>Répartissez les points selon son rôle dans l’équipe.</p></div>
                </div>
                <div class="create-stats">
                    <div class="create-field"><label for="hp"><span class="create-stat-dot stat-hp" aria-hidden="true"></span>PV</label><input type="number" id="hp" name="hp" placeholder="0" /></div>
                    <div class="create-field"><label for="atk"><span class="create-stat-dot stat-atk" aria-hidden="true"></span>Attaque</label><input type="number" id="atk" name="atk" placeholder="0" /></div>
                    <div class="create-field"><label for="def"><span class="create-stat-dot stat-def" aria-hidden="true"></span>Défense</label><input type="number" id="def" name="def" placeholder="0" /></div>
                    <div class="create-field"><label for="spa"><span class="create-stat-dot stat-spa" aria-hidden="true"></span>Atq. Spé.</label><input type="number" id="spa" name="spa" placeholder="0" /></div>
                    <div class="create-field"><label for="spd"><span class="create-stat-dot stat-spd" aria-hidden="true"></span>Déf. Spé.</label><input type="number" id="spd" name="spd" placeholder="0" /></div>
                    <div class="create-field"><label for="spe"><span class="create-stat-dot stat-spe" aria-hidden="true"></span>Vitesse</label><input type="number" id="spe" name="spe" placeholder="0" /></div>
                </div>
            </section>

            <div class="create-actions">
                <a class="create-cancel" href="/">Annuler</a>
                <button class="create-submit" type="submit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                    Créer le Pokémon
                </button>
            </div>
        </form>
    </div>
    <footer class="create-footer">PokeClub <span aria-hidden="true">·</span> À chaque dresseur, son équipe.</footer>
</main>

<?php require __DIR__ . '/../../src/templates/footer.php'; ?>
