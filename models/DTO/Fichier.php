<?php

class Fichier{
    private ?int $id;
    private ?string $nom;
    private ?string $contenu;
    private DateTime|string|null $createdAt;
    private DateTime|string|null $updatedAt;

    private ?int $idUtilisateur;

    /**
     * @param int|null $id
     * @param string|null $nom
     * @param string|null $contenu
     * @param DateTime|string|null $createdAt
     * @param DateTime|string|null $updatedAt
     * @param int|null $idUtilisateur
     */
    public function __construct(?int $id, ?string $nom, ?string $contenu, DateTime|string|null $createdAt, DateTime|string|null $updatedAt, ?int $idUtilisateur)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->contenu = $contenu;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->idUtilisateur = $idUtilisateur;
    }

    //Getters => Récupération des données

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

    //Setters => Modification des données.

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

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }
}