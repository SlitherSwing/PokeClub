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
        $this->role = $role;
    }

    public function getPokemon(): Pokemon
    {
        return $this->pokemon;
    }
    public function setPokemon(Pokemon $pokemon): void
    {
        $this->pokemon = $pokemon;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }
    public function setTeam(Team $team): void
    {
        $this->team = $team;
    }

    public function getRole(): string
    {
        return $this->role;
    }
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}


