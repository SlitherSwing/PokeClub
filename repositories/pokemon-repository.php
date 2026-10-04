<?php

class PokemonRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function fromRow(array $row): Pokemon
    {
        $types = [$row['pokemon_type1']];
        if ($row['pokemon_type2'] !== null) {
            $types[] = $row['pokemon_type2'];
        }

        $moves = [];
        foreach (['pokemon_move1', 'pokemon_move2', 'pokemon_move3', 'pokemon_move4'] as $column) {
            if ($row[$column] !== null) {
                $moves[] = $row[$column];
            }
        }

        $points = [
            'hp' => (int) $row['pokemon_hp'],
            'atk' => (int) $row['pokemon_atk'],
            'def' => (int) $row['pokemon_def'],
            'spa' => (int) $row['pokemon_spa'],
            'spd' => (int) $row['pokemon_spd'],
            'spe' => (int) $row['pokemon_spe'],
        ];

        $pokemon = new Pokemon(
            $row['pokemon_name'],
            $types,
            $moves,
            $row['pokemon_item'] ?? '',
            $points,
            $row['pokemon_nature'],
            $row['pokemon_picture']
        );
        $pokemon->setId((int) $row['id']);

        return $pokemon;
    }

    public function findByName(string $name): array
    {
        $name = trim($name);

        $sql = $this->pdo->prepare('SELECT * FROM pokemon WHERE pokemon_name = :name ORDER BY id');
        $sql->execute(['name' => $name]);
        $rows = $sql->fetchAll();

        $pokemons = [];
        foreach ($rows as $row) {
            $pokemons[] = $this->fromRow($row);
        }
        return $pokemons;
    }

    public function findById(int $id): ?Pokemon
    {
        $sql = $this->pdo->prepare('SELECT * FROM pokemon WHERE id = :id');
        $sql->execute(['id' => $id]);
        $row = $sql->fetch();

        if ($row === false) {
            return null;
        }

        return $this->fromRow($row);
    }

    public function findAll(): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM pokemon ORDER BY id');
        $statement->execute();
        $rows = $statement->fetchAll();

        $pokemons = [];
        foreach ($rows as $row) {
            $pokemons[] = $this->fromRow($row);
        }

        return $pokemons;
    }

    public function save(Pokemon $pokemon): void
    {
        $types = $pokemon->getTypes();
        $moves = $pokemon->getMoves();
        $points = $pokemon->getEffortPoints();

        $data = [
            'name' => $pokemon->getName(),
            'type_1' => $types[0],
            'type_2' => $types[1] ?? null,
            'item' => $pokemon->getItem(),
            'picture' => $pokemon->getPicture(),
            'move_1' => $moves[0] ?? null,
            'move_2' => $moves[1] ?? null,
            'move_3' => $moves[2] ?? null,
            'move_4' => $moves[3] ?? null,
            'hp' => $points['hp'],
            'atk' => $points['atk'],
            'def' => $points['def'],
            'spa' => $points['spa'],
            'spd' => $points['spd'],
            'spe' => $points['spe'],
            'nature' => $pokemon->getNature(),
        ];

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
