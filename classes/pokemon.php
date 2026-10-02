<?php

class Pokemon
{
    private ?int $id = null;
    private string $name;
    private array $types;
    private array $moves;
    private string $item;
    private string $picture;

    public function __construct(string $name, array $types, array $moves, string $item, string $picture)
    {
        $this->setName($name);
        $this->types = $types;
        $this->setMoves($moves);
        $this->item = $item;
        $this->picture = $picture;
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
        $this->types = $types;
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

    public function getPicture(): string
    {
        return $this->picture;
    }
    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }


}

