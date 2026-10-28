<?php

namespace PokeClub\Controller;

use InvalidArgumentException;
use PDOException;
use ValueError;
use PokeClub\Model\Pokemon;
use PokeClub\Model\PokemonNature;
use PokeClub\Model\PokemonType;
use PokeClub\Repository\PokemonRepository;

class PokemonController
{
    private PokemonRepository $repository;

    public function __construct(PokemonRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(): void
    {
        $values = $this->defaultValues();
        $errors = [];
        $success = $_SESSION['pokemon_created'] ?? null;
        unset($_SESSION['pokemon_created']);
        $successMessage = 'Le Pokémon a été ajouté à la base de données. Il n’est pas encore associé à une équipe.';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $result = $this->validateForm($_POST);
            $values = $result['values'];
            $errors = $result['errors'];
            $pokemon = $result['pokemon'];

            if ($errors === [] && $pokemon !== null) {
                try {
                    $this->repository->save($pokemon);
                    $_SESSION['pokemon_created'] = $pokemon->getName();
                    header('Location: /?page=pokemon-create', true, 303);
                    exit;
                } catch (PDOException $error) {
                    error_log('Création Pokémon : ' . $error->getMessage());
                    $errors[] = 'L’enregistrement a échoué. Réessayez dans quelques instants.';
                }
            }

            if ($errors !== []) {
                http_response_code(422);
            }
        }

        $pageTitle = 'Créer un Pokémon — PokeClub';
        $bodyClass = 'pokemon-create-page';
        $activePage = 'create';
        $mainContentId = 'create-main';
        $formAction = '/?page=pokemon-create';
        $submitLabel = 'Créer mon Pokémon';
        require __DIR__ . '/../View/pokemon/create.php';
    }

    public function edit(): void
    {
        $rawId = $_GET['id'] ?? null;
        $id = is_string($rawId)
            ? filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
            : false;

        if ($id === false) {
            http_response_code(400);
            echo 'Identifiant invalide.';
            return;
        }

        try {
            $pokemon = $this->repository->findById($id);
        } catch (PDOException $error) {
            error_log('Lecture Pokémon : ' . $error->getMessage());
            http_response_code(503);
            echo 'Le chargement a échoué. Réessayez dans quelques instants.';
            return;
        }

        if ($pokemon === null) {
            http_response_code(404);
            echo 'Pokémon introuvable.';
            return;
        }

        $types = $pokemon->getTypes();
        $moves = $pokemon->getMoves();
        $points = $pokemon->getEffortPoints();
        $values = [
            'name' => $pokemon->getName(),
            'nature' => $pokemon->getNature()->value,
            'picture' => $pokemon->getPicture(),
            'type-1' => $types[0]->value,
            'type-2' => isset($types[1]) ? $types[1]->value : '',
            'item' => $pokemon->getItem(),
            'move-1' => $moves[0] ?? '',
            'move-2' => $moves[1] ?? '',
            'move-3' => $moves[2] ?? '',
            'move-4' => $moves[3] ?? '',
        ];
        foreach (['hp', 'atk', 'def', 'spa', 'spd', 'spe'] as $stat) {
            $values[$stat] = (string) $points[$stat];
        }
        $errors = [];
        $updatedId = $_SESSION['pokemon_updated'] ?? null;
        unset($_SESSION['pokemon_updated']);
        $success = $updatedId === $id ? $pokemon->getName() : null;
        $successMessage = 'Les modifications ont été enregistrées.';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $result = $this->validateForm($_POST);
            $values = $result['values'];
            $errors = $result['errors'];
            $updatedPokemon = $result['pokemon'];

            if ($errors === [] && $updatedPokemon !== null) {
                try {
                    // L’ID existant fait choisir UPDATE au repository.
                    $updatedPokemon->setId($id);
                    $this->repository->save($updatedPokemon);
                    $_SESSION['pokemon_updated'] = $id;
                    header('Location: /?page=pokemon-edit&id=' . $id, true, 303);
                    exit;
                } catch (PDOException $error) {
                    error_log('Modification Pokémon : ' . $error->getMessage());
                    $errors[] = 'L’enregistrement a échoué. Réessayez dans quelques instants.';
                }
            }

            if ($errors !== []) {
                http_response_code(422);
            }
        }

        $pageTitle = 'Modifier un Pokémon — PokeClub';
        $bodyClass = 'pokemon-create-page';
        $activePage = 'edit';
        $mainContentId = 'edit-main';
        $formAction = '/?page=pokemon-edit&id=' . $id;
        $submitLabel = 'Enregistrer les modifications';
        require __DIR__ . '/../View/pokemon/edit.php';
    }

    private function defaultValues(): array
    {
        return [
            'name' => '',
            'nature' => PokemonNature::Assure->value,
            'picture' => '',
            'type-1' => PokemonType::Normal->value,
            'type-2' => '',
            'item' => '',
            'move-1' => '',
            'move-2' => '',
            'move-3' => '',
            'move-4' => '',
            'hp' => '0',
            'atk' => '0',
            'def' => '0',
            'spa' => '0',
            'spd' => '0',
            'spe' => '0',
        ];
    }

    /** @return array{values: array, errors: array, pokemon: ?Pokemon} */
    private function validateForm(array $input): array
    {
        $values = $this->defaultValues();
        $errors = [];
        $pokemon = null;
        foreach ($values as $field => $default) {
            $received = $input[$field] ?? '';
            if (!is_string($received)) {
                $errors[] = 'Un champ contient une valeur invalide.';
                continue;
            }
            $values[$field] = trim($received);
            if (mb_strlen($values[$field], 'UTF-8') > 255) {
                $errors[] = 'Chaque champ doit contenir au maximum 255 caractères.';
            }
        }

        if ($values['name'] === '') {
            $errors[] = 'Le nom du Pokémon est obligatoire.';
        }
        if ($values['move-1'] === '') {
            $errors[] = 'La première attaque est obligatoire.';
        }
        if (PokemonNature::tryFrom($values['nature']) === null) {
            $errors[] = 'Choisissez une nature proposée dans la liste.';
        }
        if (
            PokemonType::tryFrom($values['type-1']) === null
            || ($values['type-2'] !== '' && PokemonType::tryFrom($values['type-2']) === null)
        ) {
            $errors[] = 'Choisissez des types proposés dans les listes.';
        }
        if (
            $values['picture'] !== '' && (!filter_var($values['picture'], FILTER_VALIDATE_URL)
                || !in_array(strtolower((string) parse_url($values['picture'], PHP_URL_SCHEME)), ['http', 'https'], true))
        ) {
            $errors[] = 'L’image doit être une URL commençant par http:// ou https://.';
        }

        $points = [];
        foreach (['hp', 'atk', 'def', 'spa', 'spd', 'spe'] as $stat) {
            $value = $values[$stat] === '' ? '0' : $values[$stat];
            $number = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 32]]);
            if ($number === false) {
                $errors[] = 'Chaque statistique doit être un entier compris entre 0 et 32.';
            } else {
                $points[$stat] = $number;
            }
        }

        if ($errors === []) {
            $types = [$values['type-1']];
            if ($values['type-2'] !== '') {
                $types[] = $values['type-2'];
            }
            $moves = [];
            foreach (['move-1', 'move-2', 'move-3', 'move-4'] as $field) {
                if ($values[$field] !== '') {
                    $moves[] = $values[$field];
                }
            }

            try {
                $pokemon = new Pokemon(
                    $values['name'],
                    $types,
                    $moves,
                    $values['item'],
                    $points,
                    $values['nature'],
                    $values['picture']
                );
            } catch (InvalidArgumentException | ValueError $error) {
                $errors[] = $error->getMessage();
            }
        }

        return ['values' => $values, 'errors' => $errors, 'pokemon' => $pokemon];
    }
}
