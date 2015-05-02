<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JPI\CoreBundle\Entity\Entity as BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * MembreFamille
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JPI\SoluxBundle\Entity\MembreFamilleRepository")
 */
class MembreFamille extends BaseEntity
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
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = "0",max = "255")
     */
    protected $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date")
     * @Assert\Date()
     */
    protected $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="pourcentageACharge", type="decimal", precision=6, scale=4)
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\Range(min = 0, max = 1)
     */
    protected $pourcentageACharge;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parent", type="boolean")
     * @Assert\Type(type="bool")
     */
    protected $parent;
    
    /**
     * @ORM\ManyToOne(targetEntity="JPI\SoluxBundle\Entity\Famille", inversedBy="membres")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    protected $famille;


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
     * @return MembreFamille
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
     * Set prenom
     *
     * @param string $prenom
     * @return MembreFamille
     */
    public function setPrenom($prenom)
    {
        $this->prenom = ucfirst(strtolower($prenom));

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return MembreFamille
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set pourcentageACharge
     *
     * @param string $pourcentageACharge
     * @return MembreFamille
     */
    public function setPourcentageACharge($pourcentageACharge)
    {
        $this->pourcentageACharge = $pourcentageACharge;

        return $this;
    }

    /**
     * Get pourcentageACharge
     *
     * @return string 
     */
    public function getPourcentageACharge()
    {
        return $this->pourcentageACharge;
    }

    /**
     * Set parent
     *
     * @param boolean $parent
     * @return MembreFamille
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return boolean 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set famille
     *
     * @param \JPI\SoluxBundle\Entity\Famille $famille
     * @return MembreFamille
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
     * isParentValid
     *
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
     * @Assert\Callback
     */
    public function isParentValid(ExecutionContextInterface $context)
    {
    	if($this->getParent() && $this->getPourcentageACharge() != 1) {
    		$context->addViolationAt(
    				'pourcentageACharge',
    				' Un parent doit être à 100%',
    				array(),
    				null
    		);
    		$context->addViolationAt(
    				'parent',
    				' Un parent doit être à 100%',
    				array(),
    				null
    		);
    	}
    }
}
