<?php

class TeamRepository
{
    private PDO $pdo;
    private PokemonRepository $pokemonRepository;

    public function __construct(PDO $pdo, PokemonRepository $pokemonRepository)
    {
        $this->pdo = $pdo;
        $this->pokemonRepository = $pokemonRepository;
    }

    public function findFirst(): ?Team
    {
        $id = $this->pdo->query('SELECT id FROM team ORDER BY id LIMIT 1')->fetchColumn();

        if ($id === false) {
            return null;
        }

        return $this->findById((int) $id);
    }

    public function findById(int $id): ?Team
    {
        $statement = $this->pdo->prepare('SELECT * FROM team WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        $team = new Team($row['name']);
        $team->setId((int) $row['id']);

        $statement = $this->pdo->prepare(
            'SELECT pokemon_id, role FROM pokemon_team WHERE team_id = :team_id ORDER BY pokemon_id'
        );
        $statement->execute(['team_id' => $id]);

        foreach ($statement->fetchAll() as $pokemonRow) {
            $pokemon = $this->pokemonRepository->findById((int) $pokemonRow['pokemon_id']);

            if ($pokemon === null) {
                throw new RuntimeException("Un Pokémon de l'équipe est introuvable.");
            }

            $team->addPokemon($pokemon, $pokemonRow['role']);
        }

        return $team;
    }
}
