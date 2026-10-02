<?php

require_once __DIR__ . '/../classes/pokemon.php';

$pokemon = new Pokemon('Rampe-Aile', ['Insecte', 'Combat'], ['Close Combat', 'Seisme', 'Demi-tour', 'Abri'], '', '');
echo $pokemon->getName() . PHP_EOL;
foreach ($pokemon->getMoves() as $move) {
    echo $move . PHP_EOL;
}

?>

