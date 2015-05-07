<?php 
namespace JPI\SoluxBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MontantMaximumAchatLimite extends Constraint
{
    public $message = 'Les limites ne doivent pas se chevaucher avec les limites déjà existantes.';
    
    public function validatedBy()
    {
    	return 'mma_limite';
    }
    
    public function getTargets()
    {
    	return self::CLASS_CONSTRAINT;
    }
}
?>
