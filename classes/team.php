<?php

class Team
{
    private ?int $id = null;
    private string $name;

    private array $members = [];

    public function __construct(string $name)
    {
        $this->name = $name;
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
        $this->name = $name;
    }

    public function addPokemon(Pokemon $pokemon, string $role): void
    {
        if (count($this->members) >= 6) {
            throw new InvalidArgumentException('Vous ne pouvez composez une équipe que de 6 pokémon distinct');
        }

        foreach ($this->members as $member) {
            if ($member->getPokemon() === $pokemon) {
                throw new InvalidArgumentException('Ce pokémon est déjà présent');
            }
        }

        $this->members[] = new PokemonTeam($pokemon, $this, $role);
    }

    public function getMembers(): array
    {
        return array_values($this->members);
    }

    public function removePokemon(Pokemon $pokemon): void
    {
        foreach ($this->members as $index => $member) {
            if ($member->getPokemon() === $pokemon) {
                unset($this->members[$index]);
                return;
            }
        }
        throw new InvalidArgumentException('Aucun pokémon ne correspond à votre demande');
    }
}
