<?php
namespace JPI\SoluxBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class MontantMaximumAchatLimiteValidator extends ConstraintValidator
{
	private $entityManager;
	
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
    public function validate($montantMaxAchat, Constraint $constraint)
    {
       	$entitiesParent = $this->entityManager->getRepository("JPISoluxBundle:MontantMaxAchat")
               ->validLimite($montantMaxAchat);
        
        if (!empty($entitiesParent)) {
            $this->context->addViolation($constraint->message, array());
            return false;
        }
        return true;
    }
}
?>