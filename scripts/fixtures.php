<?php

require_once __DIR__ . '/../classes/pokemon.php';

$pokemon = new Pokemon('Rampe-Aile', ['Insecte', 'Combat'], '', '');
echo $pokemon->getName();

?>

