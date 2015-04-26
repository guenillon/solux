<?php 
namespace JPI\SoluxBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AchatTauxParticipationFamille extends Constraint
{
    public $message = 'Ce taux de participation ne correspond pas à celui de la famille.';
    
    public function validatedBy()
    {
    	return 'achat_taux_participation_famille';
    }
    
    public function getTargets()
    {
    	return self::CLASS_CONSTRAINT;
    }
}
?>