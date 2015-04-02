<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Famille
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\FamilleRepository")
 */
class Famille extends BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenomChef", type="string", length=255)
     */
    protected $prenomChef;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEntree", type="date")
     */
    protected $dateEntree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateSortie", type="date", nullable=true)
     */
    protected $dateSortie;

    /**
     * @var integer
     *
     * @ORM\Column(name="numeroCompte", type="integer", nullable=true)
     */
    protected $numeroCompte;

    /**
     * @var string
     *
     * @ORM\Column(name="recettes", type="decimal", precision=12, scale=2)
     */
    protected $recettes;

    /**
     * @var string
     *
     * @ORM\Column(name="depenses", type="decimal", precision=12, scale=2)
     */
    protected $depenses;
    
    /**
     * @ORM\ManyToOne(targetEntity="JPI\SoluxBundle\Entity\StatutProfessionnel")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $statutProfessionnel;
    
    /**
     * @ORM\OneToMany(targetEntity="JPI\SoluxBundle\Entity\MembreFamille", mappedBy="famille", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $membres;
    
    public function __construct()
    {
    	$this->membres = new ArrayCollection();
    }
    
    public function addMembre(MembreFamille $membres)
    {
    	$this->membres[] = $membres;
    	$membres->setFamille($this);
    	return $this;
    }
    
    public function removeMembre(MembreFamille $membres)
    {
    	$this->membres->removeElement($membres);
    }
    
    public function getMembres()
    {
    	return $this->membres;
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
     * Set nom
     *
     * @param string $nom
     * @return Famille
     */
    public function setNom($nom)
    {
        $this->nom = ucfirst(strtolower($nom));

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenomChef
     *
     * @param string $prenomChef
     * @return Famille
     */
    public function setPrenomChef($prenomChef)
    {
        $this->prenomChef = ucfirst(strtolower($prenomChef));

        return $this;
    }

    /**
     * Get prenomChef
     *
     * @return string 
     */
    public function getPrenomChef()
    {
        return $this->prenomChef;
    }

    /**
     * Set dateEntree
     *
     * @param \DateTime $dateEntree
     * @return Famille
     */
    public function setDateEntree($dateEntree)
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    /**
     * Get dateEntree
     *
     * @return \DateTime 
     */
    public function getDateEntree()
    {
        return $this->dateEntree;
    }

    /**
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     * @return Famille
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return \DateTime 
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set numeroCompte
     *
     * @param integer $numeroCompte
     * @return Famille
     */
    public function setNumeroCompte($numeroCompte)
    {
        $this->numeroCompte = $numeroCompte;

        return $this;
    }

    /**
     * Get numeroCompte
     *
     * @return integer 
     */
    public function getNumeroCompte()
    {
        return $this->numeroCompte;
    }

    /**
     * Set recettes
     *
     * @param string $recettes
     * @return Famille
     */
    public function setRecettes($recettes)
    {
        $this->recettes = $recettes;

        return $this;
    }

    /**
     * Get recettes
     *
     * @return string 
     */
    public function getRecettes()
    {
        return $this->recettes;
    }

    /**
     * Set depenses
     *
     * @param string $depenses
     * @return Famille
     */
    public function setDepenses($depenses)
    {
        $this->depenses = $depenses;

        return $this;
    }

    /**
     * Get depenses
     *
     * @return string 
     */
    public function getDepenses()
    {
        return $this->depenses;
    }

    /**
     * Set statutProfessionnel
     *
     * @param \JPI\SoluxBundle\Entity\StatutProfessionnel $statutProfessionnel
     * @return Famille
     */
    public function setStatutProfessionnel(\JPI\SoluxBundle\Entity\StatutProfessionnel $statutProfessionnel)
    {
        $this->statutProfessionnel = $statutProfessionnel;

        return $this;
    }

    /**
     * Get statutProfessionnel
     *
     * @return \JPI\SoluxBundle\Entity\StatutProfessionnel 
     */
    public function getStatutProfessionnel()
    {
        return $this->statutProfessionnel;
    }
}
