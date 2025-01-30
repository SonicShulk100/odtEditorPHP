<?php

class Fichier{
    private ?int $id;
    private ?string $nom;
    private ?string $contenu;
    private DateTime|string|null $createdAt;
    private DateTime|string|null $updatedAt;
    private ?int $idUtilisateur;
    private ?string $fichierBinaire;

    /**
     * @param int|null $id
     * @param string|null $nom
     * @param string|null $contenu
     * @param DateTime|string|null $createdAt
     * @param DateTime|string|null $updatedAt
     * @param int|null $idUtilisateur
     * @param string|null $fichierBinaire
     */
    public function __construct(?int $id, ?string $nom, ?string $contenu, DateTime|string|null $createdAt, DateTime|string|null $updatedAt, ?int $idUtilisateur, ?string $fichierBinaire)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->contenu = $contenu;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->idUtilisateur = $idUtilisateur;
        $this->fichierBinaire = $fichierBinaire;
    }

    //Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function getCreatedAt(): DateTime|string|null
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime|string|null
    {
        return $this->updatedAt;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function getFichierBinaire(): ?string
    {
        return $this->fichierBinaire;
    }

    //Setters

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function setContenu(?string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function setCreatedAt(DateTime|string|null $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime|string|null $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function setFichierBinaire(?string $fichierBinaire): void
    {
        $this->fichierBinaire = $fichierBinaire;
    }
}