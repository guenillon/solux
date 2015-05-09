<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Categorie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\CategorieRepository")
 * @UniqueEntity(fields="nom")
 */
class Categorie extends BaseEntity
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
     * @ORM\OneToMany(targetEntity="JPI\SoluxBundle\Entity\Produit", mappedBy="categorie", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $produits;

    public function __construct()
    {
    	$this->produits = new ArrayCollection();
    }
    
    public function addProduit(Produit $produit)
    {
    	$this->produits[] = $produit;
    	return $this;
    }
    
    public function eraseProduits()
    {
    	$this->produits = new ArrayCollection();
    }
    
    public function removeProduit(Produit $produit)
    {
    	$this->produits->removeElement($produit);
    }
    
    public function getProduits()
    {
    	return $this->produits;
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
     * @return Categorie
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
     * @return Categorie
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
}
?>
