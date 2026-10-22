<?php 

namespace PokeClub\Controller;

use PokeClub\Repository\PokemonRepository;

class PokemonController {

    private PokemonRepository $repository;

    public function __construct(PokemonRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(): void
    {

    }
}
