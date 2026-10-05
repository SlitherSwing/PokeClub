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
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<th>' . e($pokemon->getName()) . '</th>';
                        }
                        ?>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td>' . e($pokemon->getPicture()) . '</td>';
                        } ?>
                    </tr>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td>' . e(implode(' / ', $pokemon->getTypes())) . '</td>';
                        } ?>
                    </tr>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td>' . e($pokemon->getItem()) . '</td>';
                        } ?>
                    </tr>
                    <tr>
                        <?php
                        foreach ($pokemons as $pokemonTeam) {
                            $pokemon = $pokemonTeam->getPokemon();
                            echo '<td>';
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
                            echo '<td>';
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
                            echo '<td>' . e($pokemon->getNature()) . '</td>';
                        } ?>
                    </tr>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <?php
    require __DIR__ . '/../src/templates/footer.php';
    ?>
