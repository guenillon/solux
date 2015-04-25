<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Achat
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\AchatRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Achat extends BaseEntity
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=12, scale=2)
     */
    private $montant;
    
    /**
     * @var string
     *
     * @ORM\Column(name="taux", type="decimal", precision=6, scale=4)
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    private $taux;
    
    /**
     * @var string
     *
     * @ORM\Column(name="montantPaye", type="decimal", precision=12, scale=2)
     */
    private $montantPaye;
    
    /**
     * @ORM\ManyToOne(targetEntity="JPI\SoluxBundle\Entity\Famille")
     * @ORM\JoinColumn(nullable=false)
     */
    private $famille;
    
    /**
     * @ORM\OneToMany(targetEntity="JPI\SoluxBundle\Entity\AchatDetail", mappedBy="achat", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid
     */
    private $detail;

    public function __construct()
    {
    	$this->detail = new ArrayCollection();
    	$this->date = new \Datetime();
    }
    
    public function addDetail(AchatDetail $detail)
    {
    	$this->detail[] = $detail;
    	$detail->setAchat($this);
    	return $this;
    }
    
    public function removeDetail(AchatDetail $detail)
    {
    	$this->detail->removeElement($detail);
    }
    
    public function getDetail()
    {
    	return $this->detail;
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
     * Set date
     *
     * @param \DateTime $date
     * @return Achat
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return Achat
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set famille
     *
     * @param \JPI\SoluxBundle\Entity\Famille $famille
     * @return Achat
     */
    public function setFamille(\JPI\SoluxBundle\Entity\Famille $famille)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille
     *
     * @return \JPI\SoluxBundle\Entity\Famille 
     */
    public function getFamille()
    {
        return $this->famille;
    }
    
    public function majDetail() {
    	foreach($this->detail as $lProduit) {
    		$lProduit->majDetail();
    	}
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
    	$lTotal = 0;
    	$lTotalPaye = 0;
    	foreach($this->detail as $lProduit) {
    		$lTotal += $lProduit->getPrix();
    		$lTotalPaye += $lProduit->getPrixPaye();
    	}
    	$this->setMontant($lTotal);
    	$this->setMontantPaye($lTotalPaye);
    }

    /**
     * Set taux
     *
     * @param string $taux
     *
     * @return Achat
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
     * Set montantPaye
     *
     * @param string $montantPaye
     *
     * @return Achat
     */
    public function setMontantPaye($montantPaye)
    {
        $this->montantPaye = $montantPaye;

        return $this;
    }

    /**
     * Get montantPaye
     *
     * @return string
     */
    public function getMontantPaye()
    {
        return $this->montantPaye;
    }
}
