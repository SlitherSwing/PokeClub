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
        $types = [$row['type_1']];
        if ($row['type_2'] !== null) {
            $types[] = $row['type_2'];
        }

        $moves = [];
        foreach (['move_1', 'move_2', 'move_3', 'move_4'] as $column) {
            if ($row[$column] !== null) {
                $moves[] = $row[$column];
            }
        }

        $points = [
            'hp' => (int) $row['hp'],
            'atk' => (int) $row['atk'],
            'def' => (int) $row['def'],
            'spa' => (int) $row['spa'],
            'spd' => (int) $row['spd'],
            'spe' => (int) $row['spe'],
        ];

        $pokemon = new Pokemon(
            $row['name'],
            $types,
            $moves,
            $row['item'],
            $points,
            $row['nature'],
            $row['picture']
        );
        $pokemon->setId((int) $row['id']);

        return $pokemon;
    }

    public function findByName(string $name): array
    {
        $name = trim($name);

        $sql = $this->pdo->prepare('SELECT * FROM pokemon WHERE name =:name ORDER BY id');
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
        $sql = $this->pdo->prepare('SELECT * FROM pokemon WHERE id =:id');
        $sql->execute(['id' => $id]);
        $row = $sql->fetch();

        if ($row == false) {
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
            name, type_1, type_2, item, picture,
            move_1, move_2, move_3, move_4,
            hp, atk, def, spa, spd, spe, nature
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
            name = :name,
            type_1 = :type_1,
            type_2 = :type_2,
            item = :item,
            picture = :picture,
            move_1 = :move_1,
            move_2 = :move_2,
            move_3 = :move_3,
            move_4 = :move_4,
            hp = :hp,
            atk = :atk,
            def = :def,
            spa = :spa,
            spd = :spd,
            spe = :spe,
            nature = :nature
            WHERE id = :id';

            $statement = $this->pdo->prepare($sql);
            $statement->execute($data);
        }
    }
}

?>

