<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JPI\SoluxBundle\Entity\Famille;
use JPI\SoluxBundle\Form\Type\CaisseRechercheProduitType;
use JPI\SoluxBundle\Form\Type\AchatType;
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
    	return $this->formCaisse($famille);
    }
    
    public function showAction(Achat $achat)
    {
    	$famille = $achat->getFamille();
    	return $this->formCaisse($famille, $achat);
    }
    
    private function formCaisse($famille, $achat = null) {
    	$form = $this->createForm(new CaisseRechercheProduitType());
    	
    	//On récupère la requête
    	$request = $this->getRequest();
    	if($request->getMethod() == 'POST')
    	{
    		// Form de recherche
    		if ($request->request->has('jpi_soluxbundle_recherche_produit')) {    		    				 
    			//On vérifie que les valeurs entrées sont correctes
    			if($form->handleRequest($request)->isValid())
    			{	
		    		$em = $this->getDoctrine()->getManager();
		    	
		    		//On récupère les données entrées dans le formulaire par l'utilisateur
		    		$data = $this->getRequest()->request->get('jpi_soluxbundle_recherche_produit');

		    		$produit = array();
		    		$quantiteAchatProduit = 0;
		    		
		    		// Ne pas envoyer la requête si les deux champs sont vides
		    		if(!(empty($data['codeBarre']) && empty($data['nom']))) {		    			
		    			// Ajout des infos pour les limites d'achats du produit
		    			$data['nbMembres'] = $famille->countMembres();
		    			
		    			//On va récupérer la méthode dans le repository afin de trouver le produit
		    			$produits = $em->getRepository('JPISoluxBundle:Produit')->findProduitByParametres($data);
		    			
		    			if(!empty($produits)) {
		    				$produit = $produits[0];
		    				$produit->getCategorie()->eraseProduits();
		    				
		    				$repositoryAchat = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Achat');
		    				
		    				$limites = $produit->getLimites();
		    				if(!empty($limites) && isset($limites[0])) {
		    					$quantiteAchatProduit = $repositoryAchat->getTotalAchatProduitSurPeriode($famille->getId(), $limites[0]->getDuree(), $produit->getId());
		    				}
		    			}
	    			}
	    			$serializer = $this->container->get('serializer');
	    			$response = new Response($serializer->serialize(array("produit" => $produit, "quantiteAchat" => $quantiteAchatProduit), 'json'));
	    			$response->headers->set('Content-Type', 'application/json');
	    			
	    			return $response;
    			}
    		}	
    	}
    	
    	$addAchat = false;
    	if(is_null($achat)) {
    		$addAchat = true;
    		$achat = new Achat();
    		$achat->setFamille($famille);
    	}
    	
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
    	$tauxParticipation = $repository->getTauxParticipation($famille->getId());

    	$taux = 1;
    	if(!is_null($tauxParticipation)) {
    		$taux = $tauxParticipation->getTaux();
    	}
    	$achat->setTaux($taux);

    	$formAchat = $this->createForm(new AchatType(),$achat);
    	
    	if($request->getMethod() == 'POST')
    	{
    		// Form d'achat	
    		if ($request->request->has('jpi_soluxbundle_achat')) {    	    			
    			//On vérifie que les valeurs entrées sont correctes
    			if($formAchat->handleRequest($request)->isValid())
    			{
    				$em = $this->getDoctrine()->getManager();
    				$em->persist($achat);
    				$em->flush();
    				
    				$request->getSession()->getFlashBag()->add('success', 'Achat enregistré avec succés.');
    				if($addAchat) {
    					return $this->redirect($this->generateUrl('jpi_solux_caisse'));
    				} else {
    					return $this->redirect($this->generateUrl('jpi_solux_caisse_achats'));
    				}
    			}
    		}
    	}

    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:MembreFamille');
    	$montantMaxAchatParam = $repository->getMontantMax($famille->getId());
    	
    	$lMontantMaxActuel = null;
    	if(!is_null($montantMaxAchatParam)) {
    		$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Achat');
    		$totalAchat = $repository->getTotalAchatSurPeriode($famille->getId(), $montantMaxAchatParam->getDuree());
    	 
    		$lMontantMaxActuel = $montantMaxAchatParam->getMontant() - $totalAchat['total'];
    		$lMontantMaxActuel = ( $lMontantMaxActuel < 0 ) ? 0 : $lMontantMaxActuel;
    	}

    	return $this->render('JPISoluxBundle:Caisse:add.html.twig', array(
    			"famille" => $famille, 
    			"taux" => $tauxParticipation, 
    			"montantMaxAchat" => $lMontantMaxActuel,
    			'form' => $form->createView(),
    			'form_achat' => $formAchat->createView(),
    			"achat" => $achat
    	));
    }
    
    public function listeProduitAction() {
    	$request = $this->getRequest();
    	if($request->getMethod() == 'POST')
    	{
    		$data = $this->getRequest()->request->get('id');
	    	$repo = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Produit');
	    	$produits = $repo->getProduits($data);
	    	
	    	foreach($produits as $produit) {
	    		$produit->getCategorie()->eraseProduits();
	    	}
	    	
	    	$serializer = $this->container->get('serializer');
	    	$response = new Response($serializer->serialize($produits, 'json'));
	    	$response->headers->set('Content-Type', 'application/json');
	    	
	    	return $response;
    	}
    }
    
    public function listeAchatsAction()
    {
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
    	$listeFamille = $repository->findAll();
    	 
    	return $this->render('JPISoluxBundle:Caisse:listeAchats.html.twig', array("listeFamille" => $listeFamille));
    }
    
    public function listeAchatsFamilleAction(Famille $famille) {
    	$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Achat');
    	$listeAchats = $repository->findByFamille($famille);
    	 
    	return $this->render('JPISoluxBundle:Caisse:listeAchatsFamille.html.twig',
    			array("listeAchats" => $listeAchats,
    					"famille" => $famille
    			));
    }
    
    public function deleteAction(Achat $achat){
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($achat);
    	$em->flush();
    	$this->container->get('session')->getFlashBag()->set('success', 'Achat supprimé avec succés.');
    	return $this->redirect($this->generateUrl('jpi_solux_caisse_achats'));
    }
}
?>