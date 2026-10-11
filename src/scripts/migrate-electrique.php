<?php

$pdo = require __DIR__ . '/../require/db.php';

$updated = 0;

foreach (['pokemon_type1', 'pokemon_type2'] as $column) {
    $statement = $pdo->prepare("UPDATE pokemon SET $column = :fixed WHERE BINARY $column = :legacy");
    $statement->execute(['fixed' => 'électrique', 'legacy' => 'éléctrique']);
    $updated += $statement->rowCount();
}

echo "Lignes mises à jour : $updated" . PHP_EOL;

$types = $pdo->query(
    'SELECT DISTINCT pokemon_type1 AS type FROM pokemon
     UNION
     SELECT DISTINCT pokemon_type2 FROM pokemon WHERE pokemon_type2 IS NOT NULL'
)->fetchAll(PDO::FETCH_COLUMN);

echo 'Types présents en base :' . PHP_EOL;
foreach ($types as $type) {
    echo ' - ' . $type . PHP_EOL;
}
