<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomDuProduit = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $Categorie = null;

    #[ORM\Column]
    private ?int $Prix = null;

    

     
    #[ORM\Column(type:"json", nullable:true)]
    private $images = [];

    public function __construct()
    {
        $this->images = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDuProduit(): ?string
    {
        return $this->NomDuProduit;
    }

    public function setNomDuProduit(string $NomDuProduit): static
    {
        $this->NomDuProduit = $NomDuProduit;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): static
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): static
    {
        $this->Prix = $Prix;

        return $this;
    }


    public function addImage(string $imageName): self
    {
        $this->images[] = $imageName;

        return $this;
    }

    public function getImages(): array
    {
        return $this->images ?: [];
    }

    public function removeImage(string $imageName): void
    {
        // Recherchez l'index de l'image dans la collection
        $index = array_search($imageName, $this->images);

        // Si l'image existe, supprimez-la
        if ($index !== false) {
            unset($this->images[$index]);

            // Réorganisez les indices du tableau pour éviter les lacunes
            $this->images = array_values($this->images);
        }
    }
    
}
