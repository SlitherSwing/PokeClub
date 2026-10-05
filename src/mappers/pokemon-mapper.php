<?php

class PokemonMapper
{
    public function fromRow(array $row): Pokemon
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

    public function toData(Pokemon $pokemon): array
    {
        $types = $pokemon->getTypes();
        $moves = $pokemon->getMoves();
        $points = $pokemon->getEffortPoints();

        return [
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
    }
}
