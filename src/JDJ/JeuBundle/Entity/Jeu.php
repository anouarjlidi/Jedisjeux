<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jeu
 */
class Jeu
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $ageMin;

    /**
     * @var integer
     */
    private $joueurMin;

    /**
     * @var integer
     */
    private $joueurMax;

    /**
     * @var string
     */
    private $intro;

    /**
     * @var string
     */
    private $materiel;

    /**
     * @var string
     */
    private $but;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $mecanismes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $themes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mecanismes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->themes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Jeu
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set ageMin
     *
     * @param integer $ageMin
     * @return Jeu
     */
    public function setAgeMin($ageMin)
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    /**
     * Get ageMin
     *
     * @return integer 
     */
    public function getAgeMin()
    {
        return $this->ageMin;
    }

    /**
     * Set joueurMin
     *
     * @param integer $joueurMin
     * @return Jeu
     */
    public function setJoueurMin($joueurMin)
    {
        $this->joueurMin = $joueurMin;

        return $this;
    }

    /**
     * Get joueurMin
     *
     * @return integer 
     */
    public function getJoueurMin()
    {
        return $this->joueurMin;
    }

    /**
     * Set joueurMax
     *
     * @param integer $joueurMax
     * @return Jeu
     */
    public function setJoueurMax($joueurMax)
    {
        $this->joueurMax = $joueurMax;

        return $this;
    }

    /**
     * Get joueurMax
     *
     * @return integer 
     */
    public function getJoueurMax()
    {
        return $this->joueurMax;
    }

    /**
     * Set intro
     *
     * @param string $intro
     * @return Jeu
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string 
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set materiel
     *
     * @param string $materiel
     * @return Jeu
     */
    public function setMateriel($materiel)
    {
        $this->materiel = $materiel;

        return $this;
    }

    /**
     * Get materiel
     *
     * @return string 
     */
    public function getMateriel()
    {
        return $this->materiel;
    }

    /**
     * Set but
     *
     * @param string $but
     * @return Jeu
     */
    public function setBut($but)
    {
        $this->but = $but;

        return $this;
    }

    /**
     * Get but
     *
     * @return string 
     */
    public function getBut()
    {
        return $this->but;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Jeu
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add mecanismes
     *
     * @param \JDJ\JeuBundle\Entity\Mecanisme $mecanismes
     * @return Jeu
     */
    public function addMecanisme(\JDJ\JeuBundle\Entity\Mecanisme $mecanismes)
    {
        $this->mecanismes[] = $mecanismes;

        return $this;
    }

    /**
     * Remove mecanismes
     *
     * @param \JDJ\JeuBundle\Entity\Mecanisme $mecanismes
     */
    public function removeMecanisme(\JDJ\JeuBundle\Entity\Mecanisme $mecanismes)
    {
        $this->mecanismes->removeElement($mecanismes);
    }

    /**
     * Get mecanismes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMecanismes()
    {
        return $this->mecanismes;
    }

    /**
     * Add themes
     *
     * @param \JDJ\JeuBundle\Entity\Theme $themes
     * @return Jeu
     */
    public function addTheme(\JDJ\JeuBundle\Entity\Theme $themes)
    {
        $this->themes[] = $themes;

        return $this;
    }

    /**
     * Remove themes
     *
     * @param \JDJ\JeuBundle\Entity\Theme $themes
     */
    public function removeTheme(\JDJ\JeuBundle\Entity\Theme $themes)
    {
        $this->themes->removeElement($themes);
    }

    /**
     * Get themes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getThemes()
    {
        return $this->themes;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $jeuCaracteristiques;


    /**
     * Add jeuCaracteristiques
     *
     * @param \JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques
     * @return Jeu
     */
    public function addJeuCaracteristique(\JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques)
    {
        $this->jeuCaracteristiques[] = $jeuCaracteristiques;

        return $this;
    }

    /**
     * Remove jeuCaracteristiques
     *
     * @param \JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques
     */
    public function removeJeuCaracteristique(\JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques)
    {
        $this->jeuCaracteristiques->removeElement($jeuCaracteristiques);
    }

    /**
     * Get jeuCaracteristiques
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJeuCaracteristiques()
    {
        return $this->jeuCaracteristiques;
    }
    /**
     * @var \JDJ\JeuBundle\Entity\Statut
     */
    private $statut;


    /**
     * Set statut
     *
     * @param \JDJ\JeuBundle\Entity\Statut $statut
     * @return Jeu
     */
    public function setStatut(\JDJ\JeuBundle\Entity\Statut $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \JDJ\JeuBundle\Entity\Statut 
     */
    public function getStatut()
    {
        return $this->statut;
    }
}
