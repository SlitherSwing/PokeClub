<?php

class PokemonTeam
{
    private Pokemon $pokemon;
    private Team $team;
    private string $role;

    public function __construct(Pokemon $pokemon, Team $team, string $role)
    {
        $this->pokemon = $pokemon;
        $this->team = $team;
        $this->setRole($role);
    }

    public function getPokemon(): Pokemon
    {
        return $this->pokemon;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getRole(): string
    {
        return $this->role;
    }
    public function setRole(string $role): void
    {
        if ($role === "") {
            throw new InvalidArgumentException('Vous devez définir un rôle pour ce pokémon');
        } else {
            $this->role = $role;
        }
    }
}


