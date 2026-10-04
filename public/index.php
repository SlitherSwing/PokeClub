<?php
$pdo = require __DIR__ . '/../require/bootstrap.php';
$pokemonRepository = new PokemonRepository($pdo);
$teamRepository = new TeamRepository($pdo, $pokemonRepository);
$team = $teamRepository->findFirst();
require __DIR__ . '/../templates/header.php';
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
        <table>
            <thead>
                <tr>
                    <th>Pokémon</th>
                    <th>Rôle</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($team === null || count($team->getMembers()) === 0) {
                    echo '<tr><td colspan="2">Aucun Pokémon à afficher.</td></tr>';
                } else {
                    foreach ($team->getMembers() as $member) {
                        $pokemon = $member->getPokemon();

                        echo '<tr>';
                        echo '<td>' . e($pokemon->getName()) . '</td>';
                        echo '<td>' . e($member->getRole()) . '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    require __DIR__ . '/../templates/footer.php';
    ?>

