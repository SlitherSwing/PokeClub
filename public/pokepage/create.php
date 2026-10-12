<?php
$pdo = require __DIR__ . '/../../src/require/bootstrap.php';

$values = [
    'name' => '',
    'nature' => PokemonNature::Assure->value,
    'picture' => '',
    'type-1' => PokemonType::Normal->value,
    'type-2' => '',
    'item' => '',
    'move-1' => '',
    'move-2' => '',
    'move-3' => '',
    'move-4' => '',
    'hp' => '0',
    'atk' => '0',
    'def' => '0',
    'spa' => '0',
    'spd' => '0',
    'spe' => '0',
];
$errors = [];
$success = $_SESSION['pokemon_created'] ?? null;
unset($_SESSION['pokemon_created']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    foreach ($values as $field => $default) {
        $received = $_POST[$field] ?? '';
        if (!is_string($received)) {
            $errors[] = 'Un champ contient une valeur invalide.';
            continue;
        }
        $values[$field] = trim($received);
        if (mb_strlen($values[$field], 'UTF-8') > 255) {
            $errors[] = 'Chaque champ doit contenir au maximum 255 caractères.';
        }
    }

    if ($values['name'] === '') {
        $errors[] = 'Le nom du Pokémon est obligatoire.';
    }
    if ($values['move-1'] === '') {
        $errors[] = 'La première attaque est obligatoire.';
    }
    if (PokemonNature::tryFrom($values['nature']) === null) {
        $errors[] = 'Choisissez une nature proposée dans la liste.'; //Non nécessaire
    }
    if (
        PokemonType::tryFrom($values['type-1']) === null
        || ($values['type-2'] !== '' && PokemonType::tryFrom($values['type-2']) === null)
    ) {
        $errors[] = 'Choisissez des types proposés dans les listes.';//Non nécessaire
    }
    if (
        $values['picture'] !== '' && (!filter_var($values['picture'], FILTER_VALIDATE_URL)
            || !in_array(strtolower((string) parse_url($values['picture'], PHP_URL_SCHEME)), ['http', 'https'], true))
    ) {
        $errors[] = 'L’image doit être une URL commençant par http:// ou https://.';
    }

    $points = [];
    foreach (['hp', 'atk', 'def', 'spa', 'spd', 'spe'] as $stat) {
        $value = $values[$stat] === '' ? '0' : $values[$stat];
        $number = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 32]]);
        if ($number === false) {
            $errors[] = 'Chaque statistique doit être un entier compris entre 0 et 32.';
        } else {
            $points[$stat] = $number; //Non nécessaire
        }
    }

    if ($errors === []) {
        $types = [$values['type-1']];
        if ($values['type-2'] !== '') {
            $types[] = $values['type-2'];
        }
        $moves = [];
        foreach (['move-1', 'move-2', 'move-3', 'move-4'] as $field) {
            if ($values[$field] !== '') {
                $moves[] = $values[$field];
            }
        }

        try {
            $pokemon = new Pokemon(
                $values['name'],
                $types,
                $moves,
                $values['item'],
                $points,
                $values['nature'],
                $values['picture']
            );
            $repository = new PokemonRepository($pdo);
            $repository->save($pokemon);
            $_SESSION['pokemon_created'] = $pokemon->getName();
            header('Location: /pokepage/create.php', true, 303);
            exit;
        } catch (InvalidArgumentException | ValueError $error) {
            $errors[] = $error->getMessage();
        } catch (PDOException $error) {
            error_log('Création Pokémon : ' . $error->getMessage());
            $errors[] = 'L’enregistrement a échoué. Réessayez dans quelques instants.';
        }
    }

    if ($errors !== []) {
        http_response_code(422);
    }
}

$pageTitle = 'Créer un Pokémon — PokeClub';
$bodyClass = 'pokemon-create-page';
$activePage = 'create';
$mainContentId = 'create-main';
require __DIR__ . '/../../src/templates/header.php';
?>

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

        <form action="create.php" method="post" class="create-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>" />
            <?php if ($success !== null) { ?>
                <div class="create-section" role="status">
                    <h2 class="font-bold">Pokémon enregistré !</h2>
                    <p><?= e($success) ?> a été ajouté à la base de données. Il n’est pas encore associé à une équipe.</p>
                </div>
            <?php } ?>
            <?php if ($errors !== []) { ?>
                <div class="create-section" role="alert">
                    <h2 class="font-bold">Le Pokémon n’a pas été enregistré.</h2>
                    <ul class="list-disc pl-5">
                        <?php foreach (array_unique($errors) as $error) { ?>
                            <li><?= e($error) ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <section class="create-section" aria-labelledby="identity-title">
                <div class="create-section-heading">
                    <span class="create-section-number" aria-hidden="true">01</span>
                    <div>
                        <h2 id="identity-title">L’identité</h2>
                        <p>Faisons les présentations.</p>
                    </div>
                </div>
                <div class="create-fields">
                    <div class="create-field">
                        <label for="name">Nom du Pokémon</label>
                        <input type="text" id="name" name="name" placeholder="Ex. : Gardevoir"
                            value="<?= e($values['name']) ?>" maxlength="255" required />
                    </div>
                    <div class="create-field">
                        <label for="nature">Nature</label>
                        <select id="nature" name="nature">
                            <?php foreach (PokemonNature::cases() as $nature) { ?>
                                <option value="<?= e($nature->value) ?>" <?= $values['nature'] === $nature->value ? ' selected' : '' ?>><?= e($nature->label()) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="create-field create-field-wide">
                        <label for="picture">Image du Pokémon</label>
                        <input type="url" id="picture" name="picture" placeholder="https://exemple.com/pokemon.png"
                            aria-describedby="picture-help" value="<?= e($values['picture']) ?>" maxlength="255" />
                        <p class="create-field-help" id="picture-help">Collez le lien direct vers une image de votre
                            Pokémon.</p>
                    </div>
                    <div class="create-field">
                        <label for="type-1">Type principal</label>
                        <select id="type-1" name="type-1">
                            <?php foreach (PokemonType::cases() as $type) { ?>
                                <option value="<?= e($type->value) ?>" <?= $values['type-1'] === $type->value ? ' selected' : '' ?>><?= e($type->label()) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="create-field">
                        <label for="type-2">Type secondaire <span>facultatif</span></label>
                        <select id="type-2" name="type-2">
                            <option value="">Aucun</option>
                            <?php foreach (PokemonType::cases() as $type) { ?>
                                <option value="<?= e($type->value) ?>" <?= $values['type-2'] === $type->value ? ' selected' : '' ?>><?= e($type->label()) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </section>

            <section class="create-section" aria-labelledby="moves-title">
                <div class="create-section-heading">
                    <span class="create-section-number" aria-hidden="true">02</span>
                    <div>
                        <h2 id="moves-title">Le set de combat</h2>
                        <p>Un objet, quatre attaques, votre stratégie.</p>
                    </div>
                </div>
                <div class="create-fields">
                    <div class="create-field create-field-wide">
                        <label for="item">Objet tenu</label>
                        <input type="text" id="item" name="item" placeholder="Ex. : Restes"
                            value="<?= e($values['item']) ?>" maxlength="255" />
                    </div>
                    <div class="create-field">
                        <label for="move-1">Attaque 1</label>
                        <input type="text" id="move-1" name="move-1" placeholder="Ex. : Pouvoir Lunaire"
                            value="<?= e($values['move-1']) ?>" maxlength="255" required />
                    </div>
                    <div class="create-field">
                        <label for="move-2">Attaque 2</label>
                        <input type="text" id="move-2" name="move-2" placeholder="Ex. : Psyko"
                            value="<?= e($values['move-2']) ?>" maxlength="255" />
                    </div>
                    <div class="create-field">
                        <label for="move-3">Attaque 3</label>
                        <input type="text" id="move-3" name="move-3" placeholder="Ex. : Plénitude"
                            value="<?= e($values['move-3']) ?>" maxlength="255" />
                    </div>
                    <div class="create-field">
                        <label for="move-4">Attaque 4</label>
                        <input type="text" id="move-4" name="move-4" placeholder="Ex. : Ball’Ombre"
                            value="<?= e($values['move-4']) ?>" maxlength="255" />
                    </div>
                </div>
            </section>

            <section class="create-section" aria-labelledby="stats-title">
                <div class="create-section-heading">
                    <span class="create-section-number" aria-hidden="true">03</span>
                    <div>
                        <h2 id="stats-title">Les statistiques</h2>
                        <p>De 0 à 32 par statistique, pour un total maximum de 66 points.</p>
                    </div>
                </div>
                <div class="create-stats">
                    <div class="create-field"><label for="hp"><span class="create-stat-dot stat-hp"
                                aria-hidden="true"></span>PV</label><input type="number" id="hp" name="hp"
                            placeholder="0" value="<?= e($values['hp']) ?>" min="0" max="32" step="1" /></div>
                    <div class="create-field"><label for="atk"><span class="create-stat-dot stat-atk"
                                aria-hidden="true"></span>Attaque</label><input type="number" id="atk" name="atk"
                            placeholder="0" value="<?= e($values['atk']) ?>" min="0" max="32" step="1" /></div>
                    <div class="create-field"><label for="def"><span class="create-stat-dot stat-def"
                                aria-hidden="true"></span>Défense</label><input type="number" id="def" name="def"
                            placeholder="0" value="<?= e($values['def']) ?>" min="0" max="32" step="1" /></div>
                    <div class="create-field"><label for="spa"><span class="create-stat-dot stat-spa"
                                aria-hidden="true"></span>Atq. Spé.</label><input type="number" id="spa" name="spa"
                            placeholder="0" value="<?= e($values['spa']) ?>" min="0" max="32" step="1" /></div>
                    <div class="create-field"><label for="spd"><span class="create-stat-dot stat-spd"
                                aria-hidden="true"></span>Déf. Spé.</label><input type="number" id="spd" name="spd"
                            placeholder="0" value="<?= e($values['spd']) ?>" min="0" max="32" step="1" /></div>
                    <div class="create-field"><label for="spe"><span class="create-stat-dot stat-spe"
                                aria-hidden="true"></span>Vitesse</label><input type="number" id="spe" name="spe"
                            placeholder="0" value="<?= e($values['spe']) ?>" min="0" max="32" step="1" /></div>
                </div>
            </section>

            <div class="create-actions">
                <a class="create-cancel" href="/">Annuler</a>
                <button class="create-submit" type="submit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Créer le Pokémon
                </button>
            </div>
        </form>
    </div>
    <footer class="create-footer">PokeClub <span aria-hidden="true">·</span> À chaque dresseur, son équipe.</footer>
</main>

<?php require __DIR__ . '/../../src/templates/footer.php'; ?>

