<?php

use PokeClub\Repository\PokemonRepository;
use PokeClub\Repository\TeamRepository;

$pdo = require __DIR__ . '/../src/require/bootstrap.php';
$pokemonRepository = new PokemonRepository($pdo);
$teamRepository = new TeamRepository($pdo, $pokemonRepository);
$team = $teamRepository->findFirst();
$pokemons = $team !== null ? $team->getPokemons() : [];
$teamTypes = [];
foreach ($pokemons as $pokemonTeam) {
    foreach ($pokemonTeam->getPokemon()->getTypes() as $type) {
        $teamTypes[$type->value] = true;
    }
}
$statLabels = ['hp' => 'PV', 'atk' => 'ATQ', 'def' => 'DÉF', 'spa' => 'A. SPÉ', 'spd' => 'D. SPÉ', 'spe' => 'VIT'];
$pageTitle = 'Mon équipe — PokeClub';
$bodyClass = 'pokemon-page';
$activePage = 'team';
$mainContentId = 'home-main';
require __DIR__ . '/../src/View/home.php'; 

