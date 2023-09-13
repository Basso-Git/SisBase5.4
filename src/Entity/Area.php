<?php

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="neosys.p_area")
 * @ORM\Entity(repositoryClass=AreaRepository::class)
 */
class Area
{
    const TABLE_NAME = "p_area";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
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

    public function getDateM(): ?\DateTimeInterface
    {
        return $this->dateM;
    }

    public function setDateM(\DateTimeInterface $dateM): self
    {
        $this->dateM = $dateM;

        return $this;
    }
}
