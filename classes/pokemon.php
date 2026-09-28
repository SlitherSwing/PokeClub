<?php

class Pokemon
{
    private ?int $id = null;
    private string $name;
    private array $types;
    private string $item;
    private string $picture;

    public function __construct(string $name, array $types, string $item, string $picture)
    {
        $this->name = $name;
        $this->types = $types;
        $this->item = $item;
        $this->picture = $picture;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTypes(): array
    {
        return $this->types;
    }
    public function setTypes(array $types): void
    {
        $this->types = $types;
    }

    public function getItem(): string
    {
        return $this->item;
    }
    public function setItem(string $item): void
    {
        $this->item = $item;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }
    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }


}


