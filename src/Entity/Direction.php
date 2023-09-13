<?php

namespace App\Entity;

use App\Repository\DirectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="neosys.p_direccion")
 * @ORM\Entity(repositoryClass=DirectionRepository::class)
 */
class Direction
{
    const TABLE_NAME = "p_direccion";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="descripcion", type="string", length=100)
     */
    private $description;

    /**
     * @ORM\Column(name="usuario_m", type="string", length=25)
     */
    private $userM;

    /**
     * @ORM\Column(name="fecha_m", type="string", length=25)
     */
    private $dateM;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserM(): ?string
    {
        return $this->userM;
    }

    public function setUserM(string $userM): self
    {
        $this->userM = $userM;

        return $this;
    }

    public function getDateM(): ?string
    {
        return $this->dateM;
    }

    public function setDateM(string $dateM): self
    {
        $this->dateM = $dateM;

        return $this;
    }
}
