<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JPI\SoluxBundle\Entity\Famille;

class CaisseController extends Controller
{
    public function listeAction()
    {           
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
    	$listeFamille = $repository->findAll();
    	
        return $this->render('JPISoluxBundle:Caisse:liste.html.twig', array("listeFamille" => $listeFamille));
    }
    
    public function addAction(Famille $famille)
    {
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
    	$tauxParticipation = $repository->getTauxParticipation($famille->getId());
    	
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:MembreFamille');
    	$montantMaxAchatParam = $repository->getMontantMax($famille->getId());

    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Achat');
    	$totalAchat = $repository->getTotalAchatSurPeriode($famille->getId(), $montantMaxAchatParam->getDuree());
    	
    	$lMontantMaxActuel = $montantMaxAchatParam->getMontant() - $totalAchat['total'];
    	$lMontantMaxActuel = ( $lMontantMaxActuel < 0 ) ? 0 : $lMontantMaxActuel;
    	
    	return $this->render('JPISoluxBundle:Caisse:add.html.twig', array(
    			"famille" => $famille, 
    			"taux" => $tauxParticipation, 
    			"montantMaxAchat" => $lMontantMaxActuel));
    }
}
?>