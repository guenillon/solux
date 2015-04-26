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
		    			//$data['idFamille'] = $famille->getId();
		    			
		    			//On va récupérer la méthode dans le repository afin de trouver le produit
		    			$produits = $em->getRepository('JPISoluxBundle:Produit')->findProduitByParametres($data);
		    			
		    			if(!empty($produits)) {
		    				$produit = $produits[0];
		    				//$produit->eraseLimites();
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
    	
    	
    	$achat = new Achat();
    	$achat->setFamille($famille);
    	
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

    		/*	$formAchat;
    			$achat = $formAchat->getData();
    			$formAchat->setData();*/
    			
    		//	$detail = $achat->getDetail();
    		//	$detail = $detail[0];
    			
    			
    			//return new Response($detail->getTaux());
    			
    			//On vérifie que les valeurs entrées sont correctes
    			if($formAchat->handleRequest($request)->isValid())
    			{
    				$em = $this->getDoctrine()->getManager();
    				$em->persist($achat);
    				$em->flush();
    				
    				$request->getSession()->getFlashBag()->add('success', 'Ajout effectué avec succés.');
    				return $this->redirect($this->generateUrl('jpi_solux_caisse'));
	    			//return new Response('OK');
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
    			'form_achat' => $formAchat->createView()
    	));
    }
}
?>