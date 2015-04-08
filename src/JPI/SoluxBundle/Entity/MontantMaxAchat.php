<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JPI\SoluxBundle\Validator\Constraints as JPIAssert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * MontantMaxAchat
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\MontantMaxAchatRepository")
 * @JPIAssert\MontantMaximumAchatLimite
 */
class MontantMaxAchat extends BaseEntity
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
     * @ORM\Column(name="nbMembreAdulteMin", type="smallint")
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     * @Assert\Range(min = 0, max = 1000)
     */
    protected $nbMembreAdulteMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbMembreAdulteMax", type="smallint")
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     * @Assert\Range(min = 0, max = 1000)
     */
    protected $nbMembreAdulteMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbMembreEnfantMin", type="smallint")
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     * @Assert\Range(min = 0, max = 1000)
     */
    protected $nbMembreEnfantMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbMembreEnfantMax", type="smallint")
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     * @Assert\Range(min = 0, max = 1000)
     */
    protected $nbMembreEnfantMax;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=12, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\Range(min = 0, max = 100000000)
     */
    protected $montant;

    /**
     * @var integer
     *
     * @ORM\Column(name="duree", type="smallint")
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     * @Assert\Range(min = 0, max = 1000)
     */
    protected $duree;

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
     * Set nbMembreAdulteMin
     *
     * @param integer $nbMembreAdulteMin
     * @return MontantMaxAchat
     */
    public function setNbMembreAdulteMin($nbMembreAdulteMin)
    {
        $this->nbMembreAdulteMin = $nbMembreAdulteMin;

        return $this;
    }

    /**
     * Get nbMembreAdulteMin
     *
     * @return integer 
     */
    public function getNbMembreAdulteMin()
    {
        return $this->nbMembreAdulteMin;
    }

    /**
     * Set nbMembreAdulteMax
     *
     * @param integer $nbMembreAdulteMax
     * @return MontantMaxAchat
     */
    public function setNbMembreAdulteMax($nbMembreAdulteMax)
    {
        $this->nbMembreAdulteMax = $nbMembreAdulteMax;

        return $this;
    }

    /**
     * Get nbMembreAdulteMax
     *
     * @return integer 
     */
    public function getNbMembreAdulteMax()
    {
        return $this->nbMembreAdulteMax;
    }

    /**
     * Set nbMembreEnfantMin
     *
     * @param integer $nbMembreEnfantMin
     * @return MontantMaxAchat
     */
    public function setNbMembreEnfantMin($nbMembreEnfantMin)
    {
    	$this->nbMembreEnfantMin = $nbMembreEnfantMin;
    
    	return $this;
    }
    
    /**
     * Get nbMembreEnfantMin
     *
     * @return integer
     */
    public function getNbMembreEnfantMin()
    {
    	return $this->nbMembreEnfantMin;
    }
    
    /**
     * Set nbMembreEnfantMax
     *
     * @param integer $nbMembreEnfantMax
     * @return MontantMaxAchat
     */
    public function setNbMembreEnfantMax($nbMembreEnfantMax)
    {
    	$this->nbMembreEnfantMax = $nbMembreEnfantMax;
    
    	return $this;
    }
    
    /**
     * Get nbMembreEnfantMax
     *
     * @return integer
     */
    public function getNbMembreEnfantMax()
    {
    	return $this->nbMembreEnfantMax;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return MontantMaxAchat
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
     * isNbMembreValid
     *
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
     * @Assert\Callback
     */
    public function isNbMembreValid(ExecutionContextInterface $context)
    {
    	if($this->nbMembreAdulteMax < $this->nbMembreAdulteMin) {
    		$context->addViolationAt('nbMembreAdulteMin', 'Le min doit être inférieur au max', array(),	null);
    		$context->addViolationAt('nbMembreAdulteMax', 'Le min doit être inférieur au max', array(),	null);
    	}

    	if($this->nbMembreEnfantMax < $this->nbMembreEnfantMin) {
    		$context->addViolationAt('nbMembreEnfantMin', 'Le min doit être inférieur au max', array(),	null);
    		$context->addViolationAt('nbMembreEnfantMax', 'Le min doit être inférieur au max', array(),	null);
    	}
    }
}
