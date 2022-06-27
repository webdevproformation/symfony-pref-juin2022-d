<?php

namespace App\Entity;

use App\Services\DateService;
use Symfony\Component\Validator\Constraints as Assert;

class Search
{

    #[Assert\Type("datetime")]
    #[Assert\GreaterThanOrEqual("today")]
    private $dt_debut;

    #[Assert\Type("datetime")] 
    #[Assert\GreaterThan(propertyPath:"dt_debut" )]
    private $dt_fin;

    public function __construct()
    {
        $dt_service = new DateService();
        $this->setDtDebut($dt_service->now());
        $this->setDtFin($dt_service->demain());
    }

    public function getDtDebut(): ?\DateTimeInterface
    {
        return $this->dt_debut;
    }

    public function setDtDebut(\DateTimeInterface $dt_debut): self
    {
        $this->dt_debut = $dt_debut;

        return $this;
    }

    public function getDtFin(): ?\DateTimeInterface
    {
        return $this->dt_fin;
    }

    public function setDtFin(\DateTimeInterface $dt_fin): self
    {
        $this->dt_fin = $dt_fin;

        return $this;
    }
}
