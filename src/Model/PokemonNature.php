<?php

namespace PokeClub\Model;

enum PokemonNature: string
{
    case Assure = 'assuré';
    case Bizarre = 'bizarre';
    case Brave = 'brave';
    case Calme = 'calme';
    case Discret = 'discret';
    case Doux = 'doux';
    case Docile = 'docile';
    case Foufou = 'foufou';
    case Gentil = 'gentil';
    case Hardi = 'hardi';
    case Jovial = 'jovial';
    case Lache = 'lâche';
    case Malin = 'malin';
    case Malpoli = 'malpoli';
    case Mauvais = 'mauvais';
    case Modeste = 'modeste';
    case Naif = 'naïf';
    case Presse = 'pressé';
    case Prudent = 'prudent';
    case Pudique = 'pudique';
    case Relax = 'relax';
    case Rigide = 'rigide';
    case Serieux = 'sérieux';
    case Solo = 'solo';
    case Timide = 'timide';

    private const array LOCALIZED_MAP = [
        'bold' => 'assuré',
        'quirky' => 'bizarre',
        'calm' => 'calme',
        'quiet' => 'discret',
        'mild' => 'doux',
        'rash' => 'foufou',
        'gentle' => 'gentil',
        'hardy' => 'hardi',
        'jolly' => 'jovial',
        'lax' => 'lâche',
        'impish' => 'malin',
        'sassy' => 'malpoli',
        'naughty' => 'mauvais',
        'modest' => 'modeste',
        'naive' => 'naïf',
        'hasty' => 'pressé',
        'careful' => 'prudent',
        'bashful' => 'pudique',
        'relaxed' => 'relax',
        'adamant' => 'rigide',
        'serious' => 'sérieux',
        'lonely' => 'solo',
        'timid' => 'timide',
    ];

    public function label(): string
    {
        return mb_convert_case($this->value, MB_CASE_TITLE, 'UTF-8');
    }

    public static function fromLocalized(string $input): self
    {
        $normalized = mb_strtolower(trim($input), 'UTF-8');
        $canonical = self::LOCALIZED_MAP[$normalized] ?? $normalized;

        return self::from($canonical);
    }
}
