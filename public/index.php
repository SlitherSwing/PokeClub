<?php

use PokeClub\Controller\PokemonController;
use PokeClub\Repository\PokemonRepository;
use PokeClub\Repository\TeamRepository;

$pdo = require __DIR__ . '/../src/require/bootstrap.php';
$pokemonRepository = new PokemonRepository($pdo);

$page = $_GET['page'] ?? 'home';

if (!is_string($page)) {
    http_response_code(400);
    echo 'La page demandée est invalide.';
    exit;
}

switch ($page) {
    case 'pokemon-create':
        $controller = new PokemonController($pokemonRepository);
        $controller->create();
        exit;

    case 'home':
        break;

    default:
        http_response_code(404);
        echo 'Page introuvable.';
        exit;
}

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
