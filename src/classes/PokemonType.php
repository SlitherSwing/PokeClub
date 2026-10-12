<?php

namespace PokeClub\classes;

enum PokemonType: string
{
    case Normal = 'normal';
    case Eau = 'eau';
    case Feu = 'feu';
    case Plante = 'plante';
    case Spectre = 'spectre';
    case Tenebre = 'ténèbre';
    case Glace = 'glace';
    case Electrique = 'électrique';
    case Psy = 'psy';
    case Dragon = 'dragon';
    case Fee = 'fée';
    case Roche = 'roche';
    case Sol = 'sol';
    case Acier = 'acier';
    case Combat = 'combat';
    case Vol = 'vol';
    case Insecte = 'insecte';
    case Poison = 'poison';

    public function label(): string
    {
        return mb_convert_case($this->value, MB_CASE_TITLE, 'UTF-8');
    }

    public static function fromLocalized(string $input): self
    {
        $normalized = mb_strtolower(trim($input), 'UTF-8');
        return self::from($normalized);
    }
}
