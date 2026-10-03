<?php

require_once __DIR__ . '/../classes/pokemon.php';

$pokemon = new Pokemon('Rampe-Aile', ['Insecte', 'Combat'], ['Close Combat', 'Seisme', 'Demi-tour', 'Abri'], 'Bandeau Choix', [2, 32, 0, 0, 0, 32], 'adamant', '');
echo ('Le pokémon du jour est : ') . $pokemon->getName() . (', il est de type : ') . implode('/', $pokemon->getTypes()) . (' et sa nature est la suivante : ') . $pokemon->getNature() . PHP_EOL;
echo ('Ses attaques sont les suivantes : ') . PHP_EOL;
foreach ($pokemon->getMoves() as $move) {
    echo $move . PHP_EOL;
}
echo ('Il tient un objet qui est : ') . $pokemon->getItem() . PHP_EOL;
echo ('Ses statistiques sont réparties de la manière suivante : ') . PHP_EOL;
foreach ($pokemon->getEffortPoints() as $key => $value) {
    echo ('Pour la statistique de ' . $key . ', on distribue le nombre de points suivant :' . $value) . PHP_EOL;
}

?>

