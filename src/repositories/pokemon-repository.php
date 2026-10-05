<?php

require_once __DIR__ . '/../mappers/pokemon-mapper.php';

class PokemonRepository
{
    private PDO $pdo;
    private PokemonMapper $mapper;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->mapper = new PokemonMapper();
    }

    public function findByName(string $name): array
    {
        $name = trim($name);

        $statement = $this->pdo->prepare('SELECT * FROM pokemon WHERE pokemon_name = :name ORDER BY id');
        $statement->execute(['name' => $name]);
        $rows = $statement->fetchAll();

        $pokemons = [];
        foreach ($rows as $row) {
            $pokemons[] = $this->mapper->fromRow($row);
        }
        return $pokemons;
    }

    public function findById(int $id): ?Pokemon
    {
        $statement = $this->pdo->prepare('SELECT * FROM pokemon WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapper->fromRow($row);
    }

    public function findAll(): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM pokemon ORDER BY id');
        $statement->execute();
        $rows = $statement->fetchAll();

        $pokemons = [];
        foreach ($rows as $row) {
            $pokemons[] = $this->mapper->fromRow($row);
        }

        return $pokemons;
    }

    public function save(Pokemon $pokemon): void
    {
        $data = $this->mapper->toData($pokemon);

        if ($pokemon->getId() === null) {
            $sql = 'INSERT INTO pokemon (
            pokemon_name, pokemon_type1, pokemon_type2, pokemon_item, pokemon_picture,
            pokemon_move1, pokemon_move2, pokemon_move3, pokemon_move4,
            pokemon_hp, pokemon_atk, pokemon_def, pokemon_spa, pokemon_spd, pokemon_spe, pokemon_nature
        ) VALUES (
            :name, :type_1, :type_2, :item, :picture,
            :move_1, :move_2, :move_3, :move_4,
            :hp, :atk, :def, :spa, :spd, :spe, :nature
        )';

            $statement = $this->pdo->prepare($sql);
            $statement->execute($data);
            $pokemon->setId((int) $this->pdo->lastInsertId());
        } else {
            $data['id'] = $pokemon->getId();

            $sql = 'UPDATE pokemon SET
            pokemon_name = :name,
            pokemon_type1 = :type_1,
            pokemon_type2 = :type_2,
            pokemon_item = :item,
            pokemon_picture = :picture,
            pokemon_move1 = :move_1,
            pokemon_move2 = :move_2,
            pokemon_move3 = :move_3,
            pokemon_move4 = :move_4,
            pokemon_hp = :hp,
            pokemon_atk = :atk,
            pokemon_def = :def,
            pokemon_spa = :spa,
            pokemon_spd = :spd,
            pokemon_spe = :spe,
            pokemon_nature = :nature
            WHERE id = :id';

            $statement = $this->pdo->prepare($sql);
            $statement->execute($data);
        }
    }
}

?>
