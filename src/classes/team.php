<?php

class Team
{
    private ?int $id = null;
    private string $name;

    private array $pokemons = [];

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
        if (count($this->pokemons) >= 6) {
            throw new InvalidArgumentException('Vous ne pouvez composez une équipe que de 6 pokémon distinct');
        }

        foreach ($this->pokemons as $pokemonTeam) {
            if ($pokemonTeam->getPokemon() === $pokemon) {
                throw new InvalidArgumentException('Ce pokémon est déjà présent');
            }
        }

        $this->pokemons[] = new PokemonTeam($pokemon, $this, $role);
    }

    public function getPokemons(): array
    {
        return array_values($this->pokemons);
    }

    public function removePokemon(Pokemon $pokemon): void
    {
        foreach ($this->pokemons as $index => $pokemonTeam) {
            if ($pokemonTeam->getPokemon() === $pokemon) {
                unset($this->pokemons[$index]);
                return;
            }
        }
        throw new InvalidArgumentException('Aucun pokémon ne correspond à votre demande');
    }
}
