<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Produit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\ProduitRepository")
 * @UniqueEntity(fields="nom")
 */
class Produit extends BaseEntity
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
     * @Assert\NotBlank()
     * @Assert\Length(min = "0",max = "255")
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="codeBarre", type="integer", nullable=true)
     * @Assert\GreaterThan(value = 0)
     */
    protected $codeBarre;

    /**
     * @var string
     *
     * @ORM\Column(name="quantite", type="decimal", precision=12, scale=3)
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\Range(min = 0, max = 100000000)
     */
    protected $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="unite", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = "0",max = "255")
     */
    protected $unite;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=12, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\Range(min = 0, max = 100000000)
     */
    protected $prix;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prixFixe", type="boolean")
     * @Assert\Type(type="bool")
     */
    protected $prixFixe;
    
    /**
     * @ORM\ManyToOne(targetEntity="JPI\SoluxBundle\Entity\Categorie", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    protected $categorie;

    /**
     * @ORM\OneToMany(targetEntity="JPI\SoluxBundle\Entity\LimiteAchatProduit", mappedBy="produit", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid
     */
    private $limites;

    public function __construct()
    {
    	$this->limites = new ArrayCollection();
    }
    
    public function addLimite(LimiteAchatProduit $limite)
    {
    	$this->limites[] = $limite;
    	$limite->setProduit($this);
    	return $this;
    }
    
    public function removeLimite(LimiteAchatProduit $limite)
    {
    	$this->limites->removeElement($limite);
    }
    
    public function getLimites()
    {
    	return $this->limites;
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
     * @return Produit
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
     * Set description
     *
     * @param string $description
     * @return Produit
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
     * Set codeBarre
     *
     * @param integer $codeBarre
     * @return Produit
     */
    public function setCodeBarre($codeBarre)
    {
        $this->codeBarre = $codeBarre;

        return $this;
    }

    /**
     * Get codeBarre
     *
     * @return integer 
     */
    public function getCodeBarre()
    {
        return $this->codeBarre;
    }

    /**
     * Set quantite
     *
     * @param string $quantite
     * @return Produit
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return string 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set unite
     *
     * @param string $unite
     * @return Produit
     */
    public function setUnite($unite)
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * Get unite
     *
     * @return string 
     */
    public function getUnite()
    {
        return $this->unite;
    }

    /**
     * Set prix
     *
     * @param string $prix
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set prixFixe
     *
     * @param boolean $prixFixe
     * @return Produit
     */
    public function setPrixFixe($prixFixe)
    {
        $this->prixFixe = $prixFixe;

        return $this;
    }

    /**
     * Get prixFixe
     *
     * @return boolean 
     */
    public function getPrixFixe()
    {
        return $this->prixFixe;
    }
    
    /**
     * Set categorie
     *
     * @param \JPI\SoluxBundle\Entity\Categorie $categorie
     * @return Produit
     */
    public function setCategorie(\JPI\SoluxBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \JPI\SoluxBundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}
