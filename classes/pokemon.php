<?php

class Pokemon
{
    private string $name;
    private string $types;
    private string $item;
    private string $picture;

    public function __construct(string $name, string $types, string $item, string $picture)
    {
        $this->name = $name;
        $this->types = $types;
        $this->item = $item;
        $this->picture = $picture;
    }


}


?>

