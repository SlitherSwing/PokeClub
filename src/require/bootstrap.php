<?php
session_start();

require_once __DIR__ . '/classes.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../repositories/PokemonRepository.php';
require_once __DIR__ . '/../repositories/TeamRepository.php';

return require __DIR__ . '/db.php';
