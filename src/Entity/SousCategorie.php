<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousCategorie
 *
 * @ORM\Table(name="sous_categorie", indexes={@ORM\Index(name="sous_categorie_categorie_FK", columns={"id_categorie"})})
 * @ORM\Entity(repositoryClass= "App\Repository\SousCategorieRepository")
 */
class SousCategorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_sous_categorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSousCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_sous_categorie", type="string", length=100, nullable=false)
     */
    private $nomSousCategorie;

    /**
     * @var int
     *
     * @ORM\Column(name="id_categorie", type="integer", nullable=false)
     */
    private $idCategorie;

    public function getIdSousCategorie(): ?int
    {
        return $this->idSousCategorie;
    }

    public function getNomSousCategorie(): ?string
    {
        return $this->nomSousCategorie;
    }

    public function setNomSousCategorie(string $nomSousCategorie): static
    {
        $this->nomSousCategorie = $nomSousCategorie;

        return $this;
    }

    public function getIdCategorie(): ?int
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(int $idCategorie): static
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }


}
