<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AchatDetail
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\AchatDetailRepository")
 */
class AchatDetail extends BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="quantite", type="decimal", precision=12, scale=3)
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="unite", type="string", length=255)
     */
    private $unite;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=12, scale=2)
     */
    private $prix;
    
    /**
     * @var string
     *
     * @ORM\Column(name="taux", type="decimal", precision=6, scale=4)
     */
    private $taux;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prixPaye", type="decimal", precision=12, scale=2)
     */
    private $prixPaye;
    
    /**
     * @ORM\ManyToOne(targetEntity="JPI\SoluxBundle\Entity\Achat", inversedBy="detail")
     * @ORM\JoinColumn(nullable=false)
     */
    private $achat;
    
    /**
     * @ORM\ManyToOne(targetEntity="JPI\SoluxBundle\Entity\Produit")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    
    public function majDetail()
    {
    	$produit = $this->produit;
    	$this->taux = 1;
    	if(!$produit->getPrixFixe()) {
    		$this->taux = $this->achat->getTaux();
    	}    	
    	$this->unite = $produit->getUnite();
    	$this->prix = $produit->getPrix() * $this->getQuantite();
    	$this->prixPaye = $this->prix * $this->taux;
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
     * Set quantite
     *
     * @param string $quantite
     * @return AchatDetail
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
     * @return AchatDetail
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
     * @return AchatDetail
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
     * Set achat
     *
     * @param \JPI\SoluxBundle\Entity\Achat $achat
     * @return AchatDetail
     */
    public function setAchat(\JPI\SoluxBundle\Entity\Achat $achat)
    {
        $this->achat = $achat;

        return $this;
    }

    /**
     * Get achat
     *
     * @return \JPI\SoluxBundle\Entity\Achat 
     */
    public function getAchat()
    {
        return $this->achat;
    }

    /**
     * Set produit
     *
     * @param \JPI\SoluxBundle\Entity\Produit $produit
     * @return AchatDetail
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

    /**
     * Set taux
     *
     * @param string $taux
     *
     * @return AchatDetail
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return string
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * Set prixPaye
     *
     * @param string $prixPaye
     *
     * @return AchatDetail
     */
    public function setPrixPaye($prixPaye)
    {
        $this->prixPaye = $prixPaye;

        return $this;
    }

    /**
     * Get prixPaye
     *
     * @return string
     */
    public function getPrixPaye()
    {
        return $this->prixPaye;
    }
}
