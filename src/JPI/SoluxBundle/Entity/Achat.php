<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JPI\SoluxBundle\Validator\Constraints as JPIAssert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Achat
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\AchatRepository")
 * @JPIAssert\AchatTauxParticipationFamille
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
     * @Assert\Date()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=12, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\Range(min = 0, max = 100000000)
     * @Assert\GreaterThan(value = 0)
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
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\Range(min = 0, max = 100000000)
     * @Assert\GreaterThan(value = 0)
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
    
    /**
     * isMontantsValid
     *
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
     * @Assert\Callback
     */
    public function isMontantsValid(ExecutionContextInterface $context)
    {
    	$lTotal = 0;
    	$lTotalPaye = 0;
    	foreach($this->getDetail() as $lProduit) {
    		$lTotal = bcadd($lTotal, $lProduit->getPrix(),2);
    		$lTotalPaye = bcadd($lTotalPaye, $lProduit->getPrixPaye(), 2);
    	}
    	
    	if($this->getMontant() != $lTotal) {
    		$context->addViolationAt(
    				'montant',
    				'Le montant est différent de la somme du détail.',
    				array(),
    				null
    		);
    	}
    	if($this->getMontantPaye() != $lTotalPaye) {
    		$context->addViolationAt(
    				'montantPaye',
    				'Le montant payé est différent de la somme du détail.',
    				array(),
    				null
    		);
    	}
    }
}
