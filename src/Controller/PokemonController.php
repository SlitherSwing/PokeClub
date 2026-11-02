<?php

namespace PokeClub\Controller;

use InvalidArgumentException;
use Monolog\Logger;
use PDOException;
use ValueError;
use PokeClub\Model\Pokemon;
use PokeClub\Repository\PokemonRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class PokemonController
{
    private PokemonRepository $repository;
    private Logger $logger;

    public function __construct(PokemonRepository $repository, Logger $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
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

            try {
                $values = $this->readForm($values);
                $pokemon = $this->createPokemon($values);
                $this->repository->save($pokemon);

                $_SESSION['pokemon_created'] = $pokemon->getName();
                header('Location: /?page=pokemon-create', true, 303);
                exit;
            } catch (InvalidArgumentException $error) {
                $errors[] = $error->getMessage();
            } catch (PDOException $error) {
                $this->logger->error('Impossible de créer le Pokémon.');
                $errors[] = 'L’enregistrement a échoué. Réessayez dans quelques instants.';
            }

            http_response_code(422);
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
        $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($id === false) {
            http_response_code(400);
            echo 'Identifiant invalide.';
            return;
        }

        try {
            $pokemon = $this->repository->findById($id);
        } catch (PDOException $error) {
            $this->logger->error('Impossible de charger le Pokémon.');
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
            'type-2' => $types[1]->value ?? '',
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

            try {
                $values = $this->readForm($values);
                $pokemon = $this->createPokemon($values);
                $pokemon->setId($id);
                $this->repository->save($pokemon);

                $_SESSION['pokemon_updated'] = $id;
                header('Location: /?page=pokemon-edit&id=' . $id, true, 303);
                exit;
            } catch (InvalidArgumentException $error) {
                $errors[] = $error->getMessage();
            } catch (PDOException $error) {
                $this->logger->error('Impossible de modifier le Pokémon.');
                $errors[] = 'L’enregistrement a échoué. Réessayez dans quelques instants.';
            }

            http_response_code(422);
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
            'nature' => 'assuré',
            'picture' => '',
            'type-1' => 'normal',
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

    private function readForm(array $values): array
    {
        foreach ($values as $field => $value) {
            $value = $_POST[$field] ?? '';
            if (!is_string($value)) {
                throw new InvalidArgumentException('Un champ contient une valeur invalide.');
            }
            $values[$field] = trim($value);
        }

        return $values;
    }

    private function createPokemon(array $values): Pokemon
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($values['name'], [
            new Assert\NotBlank(message: 'Le nom du Pokémon est obligatoire.'),
            new Assert\Length(max: 255, maxMessage: 'Le nom du Pokémon doit contenir au maximum 255 caractères.'),
        ]);
        foreach ($violations as $violation) {
            throw new InvalidArgumentException($violation->getMessage());
        }

        $violations = $validator->validate($values['picture'], new Assert\Url(
            protocols: ['http', 'https'],
            requireTld: false,
            message: 'L’image doit être une URL commençant par http:// ou https://.'
        ));
        foreach ($violations as $violation) {
            throw new InvalidArgumentException($violation->getMessage());
        }

        foreach ($values as $value) {
            if (mb_strlen($value, 'UTF-8') > 255) {
                throw new InvalidArgumentException('Chaque champ doit contenir au maximum 255 caractères.');
            }
        }
        if ($values['move-1'] === '') {
            throw new InvalidArgumentException('La première attaque est obligatoire.');
        }

        $points = [];
        foreach (['hp', 'atk', 'def', 'spa', 'spd', 'spe'] as $stat) {
            $value = $values[$stat] === '' ? '0' : $values[$stat];
            $points[$stat] = filter_var($value, FILTER_VALIDATE_INT);
            if ($points[$stat] === false) {
                throw new InvalidArgumentException('Chaque statistique doit être un entier.');
            }
        }

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

        // Le modèle vérifie les types, la nature, les attaques et les limites des statistiques.
        try {
            return new Pokemon(
                $values['name'],
                $types,
                $moves,
                $values['item'],
                $points,
                $values['nature'],
                $values['picture']
            );
        } catch (ValueError $error) {
            throw new InvalidArgumentException('Choisissez une nature et des types proposés dans les listes.');
        }
    }
}
