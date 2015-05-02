<?php 
namespace JPI\SoluxBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TauxParticipationLimite extends Constraint
{
    public $message = 'Les limites ne doivent pas se chevaucher avec les limites déjà existantes.';
    
    public function validatedBy()
    {
    	return 'tx_part_limite';
    }
    
    public function getTargets()
    {
    	return self::CLASS_CONSTRAINT;
    }
}
?>