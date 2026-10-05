<?php
$pdo = require __DIR__ . '/../src/require/bootstrap.php';
$pokemonRepository = new PokemonRepository($pdo);
$teamRepository = new TeamRepository($pdo, $pokemonRepository);
$team = $teamRepository->findFirst();
$pokemons = [];
if ($team !== null) {
    $pokemons = $team->getPokemons();
}
require __DIR__ . '/../src/templates/header.php';
?>
<div>
    <div>
        <!--Liens vers la liste des pokémons disponibles -->
        <div>
            <a href="/pokepage/list.php">Accéder à la liste des pokémons</a>
        </div>

        <!--Formulaire de création de pokémons -->
        <div>
            <a href="/pokepage/create.php">Créer un nouveau pokémon</a>
        </div>
    </div>

    <!-- Team en cours d'utilisation -->
    <div>
        <?php if ($team !== null) { ?>
            <h2><?= e($team->getName()) ?></h2>
        <?php } ?>
        <?php if (count($pokemons) === 0) { ?>
            <p>Aucun Pokémon à afficher.</p>
        <?php } else { ?>
            <table class="border-separate border-spacing-2 border-4 border-indigo-600">
                <thead>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<th class="border border-indigo-600 p-2">' . e($pokemon->getName()) . '</th>';
                        }
                        ?>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td class="border border-indigo-600 p-2">';
                            if ($pokemon->getPicture() !== '') {
                                echo '<img src="' . e($pokemon->getPicture()) . '" alt="' . e($pokemon->getName()) . '" width="100">';
                            }
                            echo '</td>';
                        } ?>
                    </tr>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td class="border border-indigo-600 p-2">' . e(implode(' / ', $pokemon->getTypes())) . '</td>';
                        } ?>
                    </tr>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td class="border border-indigo-600 p-2">' . e($pokemon->getItem()) . '</td>';
                        } ?>
                    </tr>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td class="border border-indigo-600 p-2">';
                            foreach ($pokemon->getMoves() as $move) {
                                echo e($move) . '<br>';
                            }
                            echo '</td>';
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td class="border border-indigo-600 p-2">';
                            foreach ($pokemon->getEffortPoints() as $key => $points) {
                                echo $key . ' -> ' . e($points) . '<br>';
                            }
                            echo '</td>';
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td class="border border-indigo-600 p-2">' . e($pokemon->getNature()) . '</td>';
                        } ?>
                    </tr>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <?php
    require __DIR__ . '/../src/templates/footer.php';
    ?>
