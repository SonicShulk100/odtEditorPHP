<?php

class Utilisateur{
    private ?int $idUtilisateur;
    private ?string $nomUtilisateur;
    private ?string $prenom;
    private ?string $login;
    private ?string $mdp;

    /**
     * Constructeur de l'utilisateur en question, ainsi avec les getters et setters.
     * @param int|null $idUtilisateur
     * @param string|null $nomUtilisateur
     * @param string|null $prenom
     * @param string|null $login
     * @param string|null $mdp
     */

    //Constructors
    public function __construct(?int $idUtilisateur, ?string $nomUtilisateur, ?string $prenom, ?string $login, ?string $mdp)
    {
        $this->idUtilisateur = $idUtilisateur;
        $this->nomUtilisateur = $nomUtilisateur;
        $this->prenom = $prenom;
        $this->login = $login;
        $this->mdp = $mdp;
    }

    //Getters

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    //Setters

    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function setNomUtilisateur(?string $nomUtilisateur): void
    {
        $this->nomUtilisateur = $nomUtilisateur;
    }

    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    public function setMdp(?string $mdp): void
    {
        $this->mdp = $mdp;
    }
}
