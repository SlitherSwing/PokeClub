<?php

class Game
{
    private ?int $id = null;
    private datetime $date;
    private string $city;
    private string $tournament;

    public function __construct(datetime $date, string $city, string $tournament)
    {
        $this->date = $date;
        $this->city = $city;
        $this->tournament = $tournament;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDate(): datetime
    {
        return $this->date;
    }
    public function setDate(datetime $date): void
    {
        $this->date = $date;
    }

    public function getCity(): string
    {
        return $this->city;
    }
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getTournament(): string
    {
        return $this->tournament;
    }
    public function setTournament(string $tournament): void
    {
        $this->tournament = $tournament;
    }


}


