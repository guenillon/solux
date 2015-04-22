<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Entity\Famille;
use JPI\SoluxBundle\Entity\Produit;
use JPI\SoluxBundle\Form\CaisseRechercheProduitType;
use JPI\SoluxBundle\Form\AchatType;
/*use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;*/
use JPI\SoluxBundle\Entity\Achat;

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
    	$form = $this->createForm(new CaisseRechercheProduitType());
    	
    	//On récupère la requête
    	$request = $this->getRequest();
    	if($request->getMethod() == 'POST')
    	{
    		$form->bind($request);
    	
    		//On vérifie que les valeurs entrées sont correctes
    		if($form->isValid())
    		{
    			// TODO : Ne pas envoyer la requête si les deux champs sont vides
    			$em = $this->getDoctrine()->getManager();
    	
    			//On récupère les données entrées dans le formulaire par l'utilisateur
    			$data = $this->getRequest()->request->get('jpi_soluxbundle_recherche_produit');
    	
    			//On va récupérer la méthode dans le repository afin de trouver le produit
    			$produits = $em->getRepository('JPISoluxBundle:Produit')->findProduitByParametres($data);
    			
    			$produit = array();
    			if(!empty($produits)) {
    				$produit = $produits[0];
    				$produit->eraseLimites();
    				$produit->getCategorie()->eraseProduits();
    			}
    			
    			$serializer = $this->container->get('serializer');
    			$response = new Response($serializer->serialize($produit, 'json'));
    			$response->headers->set('Content-Type', 'application/json');
    			
    			return $response;
    			//return $response->setData();
			//	return $response->setData($produit);
			//	    $serializer->serialize($produit, 'json')
			//	);
    			//return new Response(var_dump($produit));
    			//return $this->render('HurricaneScriptAnnonceBundle:Annonce:listeAnnonces.html.twig', array('liste_annonces' => $liste_annonces));
    	
    		}
    	
    	}
    	
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
    	$tauxParticipation = $repository->getTauxParticipation($famille->getId());
    	 
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:MembreFamille');
    	$montantMaxAchatParam = $repository->getMontantMax($famille->getId());
    	
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Achat');
    	$totalAchat = $repository->getTotalAchatSurPeriode($famille->getId(), $montantMaxAchatParam->getDuree());
    	 
    	$lMontantMaxActuel = $montantMaxAchatParam->getMontant() - $totalAchat['total'];
    	$lMontantMaxActuel = ( $lMontantMaxActuel < 0 ) ? 0 : $lMontantMaxActuel;
    	
    	$achat = new Achat();
    	$achat->setFamille($famille);
    	$formAchat = $this->createForm(new AchatType(),$achat);

    	return $this->render('JPISoluxBundle:Caisse:add.html.twig', array(
    			"famille" => $famille, 
    			"taux" => $tauxParticipation, 
    			"montantMaxAchat" => $lMontantMaxActuel,
    			'form' => $form->createView(),
    			'form_achat' => $formAchat->createView()
    	));
    }
    

   /* public function getProduitParCodeBarreAction(Request $request) {
    	
    	
    	
    	//if ($formCodeBarre->handleRequest($request)->isValid()) {
    		
    		//$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
    		/*$produit = new Produit();
    		$formCodeBarre = $this->createForm(new CaisseRechercheProduitParCodeBarreType(), $produit, array(
			    'action' => $this->generateUrl('jpi_solux_caisse_recherche_produit_cb')
			));
   /* 		$formCodeBarre->bindRequest($request);*/
    /*		$codeBarre = $request->request->get('codeBarre');
   			$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Produit');
			$produit = $repository->findByCodeBarre($codeBarre);

		    	
    		return new Response(var_dump($produit));
    	} else {
    		throw $this->createNotFoundException("Le produit demandé n'existe pas.");
    	}
    }*/
}
?>