<?php
namespace JPI\SoluxBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class TauxParticipationLimiteValidator extends ConstraintValidator
{
	private $entityManager;
	
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
    public function validate($tauxParticipation, Constraint $constraint)
    {
       	$entities = $this->entityManager->getRepository("JPISoluxBundle:TauxParticipation")
               ->validLimite($tauxParticipation);
        
        if (!empty($entities)) {
            $this->context->addViolation($constraint->message, array());
            return false;
        }
        return true;
    }
}
?>