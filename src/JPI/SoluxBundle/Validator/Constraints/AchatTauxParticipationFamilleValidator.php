<?php
namespace JPI\SoluxBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class AchatTauxParticipationFamilleValidator extends ConstraintValidator
{
	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function validate($achat, Constraint $constraint)
	{		
		$repository = $this->entityManager->getRepository('JPISoluxBundle:Famille');
		$tauxParticipation = $repository->getTauxParticipation($achat->getFamille()->getId());

		if (!is_null($tauxParticipation) && $tauxParticipation->getTaux() != $achat->getTaux()) {
			$this->context->addViolation($constraint->message, array());
			return false;
		}
		return true;
	}
}
?>