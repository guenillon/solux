<?php
namespace JPI\SoluxBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class FamilleTauxParticipationExisteValidator extends ConstraintValidator
{
	private $entityManager;
	
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
    public function validate($famille, Constraint $constraint)
    {
       	$taux = $this->entityManager->getRepository("JPISoluxBundle:TauxParticipation")
               ->familleTauxParticipationExiste($famille);
        
        if (is_null($taux)) {
            $this->context->addViolation($constraint->message, array());
            return false;
        }
        return true;
    }
}
?>