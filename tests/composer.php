<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/require/helpers.php';

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use PokeClub\Controller\PokemonController;
use PokeClub\Model\Pokemon;
use PokeClub\Repository\PokemonRepository;

// Ce repository simule les erreurs, sans connexion ni écriture dans une base.
class ComposerTestRepository extends PokemonRepository
{
    public function __construct(private bool $failRead = false) {}

    public function findById(int $id): ?Pokemon
    {
        if ($this->failRead) {
            $this->fail();
        }
        $pokemon = new Pokemon('DONNEE_PRIVEE_TEST', ['psy'], ['Psyko'], '', [0, 0, 0, 0, 0, 0], 'modeste', '');
        $pokemon->setId($id);
        return $pokemon;
    }

    public function save(Pokemon $pokemon): void
    {
        $this->fail();
    }

    private function fail(): never
    {
        $error = new PDOException('password=SECRET_TEST, formulaire=DONNEE_PRIVEE_TEST', 23000);
        $error->errorInfo = ['23000', 1062, 'SECRET_TEST'];
        throw $error;
    }
}

$checks = 0;
$check = static function (bool $condition, string $message) use (&$checks): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
    $checks++;
};

foreach ([Pokemon::class, PokemonController::class, Monolog\Logger::class, Symfony\Component\Validator\Validation::class] as $class) {
    $check(class_exists($class), 'Classe non chargée : ' . $class);
}
$check(!is_file(__DIR__ . '/../src/require/autoload.php'), 'L’ancien chargeur existe encore.');

$logger = require __DIR__ . '/../src/require/logger.php';
$handler = $logger->getHandlers()[0];
$check($handler instanceof StreamHandler, 'Le journal doit utiliser un fichier.');
$check($handler->getUrl() === dirname(__DIR__) . '/src/require/../../var/log/pokeclub.log', 'Chemin du journal inattendu.');
$check($handler->getLevel() === Level::Error, 'Seuil du journal inattendu.');
$log = tempnam(sys_get_temp_dir(), 'pokeclub-composer-');
if ($log === false) {
    throw new RuntimeException('Impossible de créer le journal temporaire.');
}
$logger->setHandlers([new StreamHandler($log, Level::Error)]);

try {
    $controller = new PokemonController(new ComposerTestRepository(), $logger);
    $method = new ReflectionMethod($controller, 'validateForm');
    $valid = ['name' => ' Gardevoir ', 'nature' => 'modeste', 'type-1' => 'psy', 'type-2' => 'fée', 'picture' => '', 'item' => '', 'move-1' => 'Psyko', 'move-2' => '', 'move-3' => '', 'move-4' => '', 'hp' => '32', 'atk' => '0', 'def' => '0', 'spa' => '32', 'spd' => '2', 'spe' => '0'];
    $cases = [
        [$valid, true, null],
        [array_replace($valid, ['name' => str_repeat('é', 255)]), true, null],
        [array_replace($valid, ['name' => '']), false, 'Le nom du Pokémon est obligatoire.'],
        [array_replace($valid, ['name' => '   ']), false, 'Le nom du Pokémon est obligatoire.'],
        [array_replace($valid, ['name' => str_repeat('é', 256)]), false, 'Le nom du Pokémon doit contenir au maximum 255 caractères.'],
        [array_replace($valid, ['name' => ['tableau']]), false, 'Un champ contient une valeur invalide.'],
        [array_replace($valid, ['item' => str_repeat('é', 256)]), false, 'Chaque champ doit contenir au maximum 255 caractères.'],
        [array_replace($valid, ['hp' => '33']), false, null],
        [array_replace($valid, ['def' => '32']), false, null],
        [array_replace($valid, ['type-2' => 'psy']), false, null],
        [array_replace($valid, ['move-2' => 'Psyko']), false, null],
        [array_replace($valid, ['nature' => 'inconnue']), false, null],
        [array_replace($valid, ['picture' => 'javascript:alert(1)']), false, null],
    ];
    foreach ($cases as [$input, $accepted, $expected]) {
        $result = $method->invoke($controller, $input);
        $check(($result['errors'] === []) === $accepted && ($result['pokemon'] instanceof Pokemon) === $accepted, 'Validation incorrecte.');
        if ($expected !== null) {
            $check(in_array($expected, $result['errors'], true), 'Message français attendu absent.');
        }
        if (is_string($input['name'])) {
            $check($result['values']['name'] === trim($input['name']), 'Saisie perdue.');
            if ($input['name'] === '' || trim($input['name']) === '' || mb_strlen($input['name']) > 255) {
                $check(count($result['errors']) === 1, 'Erreur du nom doublonnée.');
            }
        }
    }

    $_SESSION = ['csrf_token' => 'jeton-test'];
    $_POST = array_replace($valid, ['csrf_token' => 'jeton-test', 'name' => 'DONNEE_PRIVEE_TEST']);
    $_GET = ['id' => '42'];
    $_SERVER['REQUEST_METHOD'] = 'POST';
    foreach (['create', 'edit'] as $action) {
        http_response_code(200);
        ob_start();
        $controller->$action();
        $html = ob_get_clean();
        $check(http_response_code() === 422 && str_contains($html, 'L’enregistrement a échoué.'), 'Erreur de sauvegarde mal gérée.');
        $check(str_contains($html, 'value="DONNEE_PRIVEE_TEST"') && !str_contains($html, 'SECRET_TEST'), 'Saisie perdue ou diagnostic privé affiché.');
    }
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $controller = new PokemonController(new ComposerTestRepository(true), $logger);
    ob_start();
    $controller->edit();
    $html = ob_get_clean();
    $check(http_response_code() === 503 && str_contains($html, 'Le chargement a échoué.') && !str_contains($html, 'SECRET_TEST'), 'Erreur de lecture mal gérée.');
    $logger->close();
    $records = file_get_contents($log);
    foreach (['creation', 'modification', 'lecture'] as $operation) {
        $check(substr_count($records, '"operation":"' . $operation . '"') === 1, 'Opération absente ou répétée dans le journal.');
    }
    $check(str_contains($records, '"sqlstate":"23000"') && str_contains($records, '"driver_code":1062'), 'Codes de diagnostic absents.');
    foreach (['SECRET_TEST', 'DONNEE_PRIVEE_TEST', 'password=', 'formulaire=', 'jeton-test', 'trace', 'exception'] as $private) {
        $check(!str_contains($records, $private), 'Donnée privée trouvée dans le journal.');
    }
    http_response_code(200);
    echo $checks, " contrôles réussis : chargement Composer, validation et journalisation, sans base réelle.\n";
} finally {
    $logger->close();
    unlink($log);
}
