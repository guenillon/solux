<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JPI\SoluxBundle\Validator\Constraints as JPIAssert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * TauxParticipation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\TauxParticipationRepository")
 * @JPIAssert\TauxParticipationLimite
 */
class TauxParticipation extends BaseEntity
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
     * @ORM\Column(name="min", type="decimal", precision=12, scale=2)
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    protected $min;

    /**
     * @var string
     *
     * @ORM\Column(name="max", type="decimal", precision=12, scale=2)
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    protected $max;

    /**
     * @var string
     *
     * @ORM\Column(name="taux", type="decimal", precision=6, scale=4)
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    protected $taux;

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
     * Set min
     *
     * @param string $min
     * @return TauxParticipation
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return string 
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max
     *
     * @param string $max
     * @return TauxParticipation
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return string 
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set taux
     *
     * @param string $taux
     * @return TauxParticipation
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
     * isLimiteValid
     *
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
     * @Assert\Callback
     */
    public function isLimiteValid(ExecutionContextInterface $context)
    {
    	if($this->max < $this->min) {
    		$context->addViolationAt('min', 'Le min doit être inférieur au max', array(),	null);
    		$context->addViolationAt('max', 'Le min doit être inférieur au max', array(),	null);
    	}
    }
}
