<?php

namespace PokeClub\Model;

use InvalidArgumentException;

class Pokemon
{
    private ?int $id = null;
    private string $name;
    private array $types;
    private array $moves;
    private string $item;
    private array $effortPoints;
    private PokemonNature $nature;
    private string $picture;

    public function __construct(string $name, array $types, array $moves, string $item, array $effortPoints, string $nature, string $picture)
    {
        $this->setName($name);
        $this->setTypes($types);
        $this->setMoves($moves);
        $this->setItem($item);
        $this->setEffortPoints($effortPoints);
        $this->setNature($nature);
        $this->setPicture($picture);
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $name = trim($name);
        if ($name === "") {
            throw new InvalidArgumentException('Le nom du Pokémon est obligatoire');
        }

        $this->name = $name;
    }

    public function getTypes(): array
    {
        return $this->types;
    }
    public function setTypes(array $types): void
    {
        $cleanTypes = [];
        $usedTypes = [];
        if (count($types) > 2 || count($types) < 1) {
            throw new InvalidArgumentException('Le pokémon doit avoir 1 ou 2 types différents');
        }
        foreach ($types as $type) {
            $pokemonType = PokemonType::fromLocalized($type);

            if (in_array($pokemonType, $usedTypes, true)) {
                throw new InvalidArgumentException('Un pokémon ne peux avoir un double type identitque');
            }

            $usedTypes[] = $pokemonType;
            $cleanTypes[] = $pokemonType;
        }
        $this->types = $cleanTypes;
    }

    public function getMoves(): array
    {
        return $this->moves;
    }

    public function setMoves(array $moves): void
    {
        $cleanMoves = [];
        $usedMoves = [];
        if (count($moves) > 4) {
            throw new InvalidArgumentException('Le pokémon ne peut apprendre que 4 attaques max');
        } else {
            foreach ($moves as $move) {
                $move = trim($move);
                $lowerMove = mb_strtolower($move, 'UTF-8');

                if (in_array($lowerMove, $usedMoves, true)) {
                    throw new InvalidArgumentException('Une attaque ne peut pas être présente deux fois.');
                }

                $usedMoves[] = $lowerMove;
                $cleanMoves[] = $move;
            }
            $this->moves = $cleanMoves;
        }
    }

    public function getItem(): string
    {
        return $this->item;
    }
    public function setItem(string $item): void
    {
        $this->item = $item;
    }

    public function getEffortPoints(): array
    {
        return $this->effortPoints;
    }
    public function setEffortPoints(array $points): void
    {
        $keys = ['hp', 'atk', 'def', 'spa', 'spd', 'spe'];

        if (count($points) !== 6) {
            throw new InvalidArgumentException('Il faut rentrer les valeurs des 6 statistiques');
        }

        if (array_is_list($points)) {
            $points = array_combine($keys, $points);
        }

        $cleanPoints = [];
        $total = 0;

        foreach ($keys as $key) {
            if (!isset($points[$key])) {
                throw new InvalidArgumentException('Il manque une statistique');
            }
            if (!is_int($points[$key])) {
                throw new InvalidArgumentException('Il faut rentrer pour chaque statistique une valeur numérique ');
            }
            if ($points[$key] < 0 || $points[$key] > 32) {
                throw new InvalidArgumentException('La statistique doit être comprise entre 0 et 32');
            }

            $total += $points[$key];
            $cleanPoints[$key] = $points[$key];
        }

        if ($total > 66) {
            throw new InvalidArgumentException('Le culmul des EV ne peux dépasser 66');
        }

        $this->effortPoints = $cleanPoints;
    }

    public function getNature(): PokemonNature
    {
        return $this->nature;
    }
    public function setNature(string $nature): void
    {
        $normalized = mb_strtolower(trim($nature));
        if ($normalized === "") {
            throw new InvalidArgumentException('Un pokémon doit avoir une nature');
        }
        $this->nature = PokemonNature::fromLocalized($nature);
    }
    public function getPicture(): string
    {
        return $this->picture;
    }
    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }


}
