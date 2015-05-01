<?php 
namespace JPI\SoluxBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FamilleTauxParticipationExiste extends Constraint
{
    public $message = 'Aucun taux de participation n\'existe pour cette famille.';
    
    public function validatedBy()
    {
    	return 'famille_tx_part_existe';
    }
    
    public function getTargets()
    {
    	return self::CLASS_CONSTRAINT;
    }
}
?>