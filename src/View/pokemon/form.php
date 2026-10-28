<?php

use PokeClub\Model\PokemonNature;
use PokeClub\Model\PokemonType;

?>

<form action="<?= e($formAction) ?>" method="post" class="create-form">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>" />
    <?php if ($success !== null) { ?>
        <div class="create-section" role="status">
            <h2 class="font-bold">Pokémon enregistré !</h2>
            <p><?= e($success) ?> : <?= e($successMessage) ?></p>
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
            <?= e($submitLabel) ?>
        </button>
    </div>
</form>
