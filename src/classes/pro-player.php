<?php

class ProPlayer
{
    private ?int $id = null;
    private string $firstname;
    private string $lastname;
    private string $nationality;
    private string $picture;

    public function __construct(string $firstname, string $lastname, string $nationality, string $picture)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->nationality = $nationality;
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

    public function getFirstname(): string
    {
        return $this->firstname;
    }
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }
    public function setNationality(string $nationality): void
    {
        $this->nationality = $nationality;
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


