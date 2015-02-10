<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * LimiteAchatProduit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\LimiteAchatProduitRepository")
 */
class LimiteAchatProduit extends BaseEntity
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
     * @var integer
     *
     * @ORM\Column(name="nbMembreMin", type="smallint")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    protected $nbMembreMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbMembreMax", type="smallint")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    protected $nbMembreMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="duree", type="smallint")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    protected $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="quantiteMax", type="decimal", precision=12, scale=3)
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    protected $quantiteMax;
    
    /**
     * @ORM\ManyToOne(targetEntity="JPI\SoluxBundle\Entity\Produit", inversedBy="limites")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    protected $produit;


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
     * Set nbMembreMin
     *
     * @param integer $nbMembreMin
     * @return LimiteAchatProduit
     */
    public function setNbMembreMin($nbMembreMin)
    {
        $this->nbMembreMin = $nbMembreMin;

        return $this;
    }

    /**
     * Get nbMembreMin
     *
     * @return integer 
     */
    public function getNbMembreMin()
    {
        return $this->nbMembreMin;
    }

    /**
     * Set nbMembreMax
     *
     * @param integer $nbMembreMax
     * @return LimiteAchatProduit
     */
    public function setNbMembreMax($nbMembreMax)
    {
        $this->nbMembreMax = $nbMembreMax;

        return $this;
    }

    /**
     * Get nbMembreMax
     *
     * @return integer 
     */
    public function getNbMembreMax()
    {
        return $this->nbMembreMax;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     * @return LimiteAchatProduit
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer 
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set quantiteMax
     *
     * @param string $quantiteMax
     * @return LimiteAchatProduit
     */
    public function setQuantiteMax($quantiteMax)
    {
        $this->quantiteMax = $quantiteMax;

        return $this;
    }

    /**
     * Get quantiteMax
     *
     * @return string 
     */
    public function getQuantiteMax()
    {
        return $this->quantiteMax;
    }

    /**
     * Set produit
     *
     * @param \JPI\SoluxBundle\Entity\Produit $produit
     * @return LimiteAchatProduit
     */
    public function setProduit(\JPI\SoluxBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \JPI\SoluxBundle\Entity\Produit 
     */
    public function getProduit()
    {
        return $this->produit;
    }
}
?>
