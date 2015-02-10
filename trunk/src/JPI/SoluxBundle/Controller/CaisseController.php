<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CaisseController extends Controller
{
    public function listeFamilleAction()
    {           
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
    	$listeFamille = $repository->findAll();
    	
        return $this->render('JPISoluxBundle:Caisse:ListeFamille.html.twig', array("listeFamille" => $listeFamille));
    }
}
?>