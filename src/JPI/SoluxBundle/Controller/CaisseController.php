<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JPI\SoluxBundle\Entity\Famille;
use JPI\SoluxBundle\Entity\Produit;
use JPI\SoluxBundle\Entity\Achat;
use JPI\SoluxBundle\Form\Type\CaisseRechercheProduitType;
use JPI\SoluxBundle\Form\Type\AchatType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Form\Type\DeleteType;

/**
 * @Route("/caisse")
 */
class CaisseController extends Controller
{
	/**
	 * @Route("/", name="jpi_solux_caisse")
	 * @Method({"GET"})
	 */
    public function listeAction()
    {           
    	$listeFamille = $this->get('jpi_solux.manager.famille')->getListe();    	
        return $this->render('JPISoluxBundle:Caisse:liste.html.twig', array("listeFamille" => $listeFamille));
    }
    
    /**
     * @Route("/add/{id}", name="jpi_solux_caisse_add", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request, Famille $famille)
    {	
    	$achat = new Achat();
    	$achat->setFamille($famille);
    	$tauxParticipation = $this->get('jpi_solux.manager.famille')->getTauxParticipation($famille);
    	$taux = 1;
    	if(!is_null($tauxParticipation)) {
    		$taux = $tauxParticipation->getTaux();
    	}
    	$achat->setTaux($taux);
    	
    	$form = $this->createForm(new CaisseRechercheProduitType());
    	$formAchat = $this->createForm(new AchatType(),$achat);
    	
    	//On vérifie que les valeurs entrées sont correctes
    	$formAchat->handleRequest($request);
    	// Form valid
    	if($formAchat->isSubmitted() && $formAchat->isValid())
    	{
    		// Enregistrement de l'achat
    		$this->get('jpi_solux.manager.achat')->set($achat);
    		$request->getSession()->getFlashBag()->add('success', 'Achat enregistré avec succés.');
    		return $this->redirect($this->generateUrl('jpi_solux_caisse'));
    	}
    	
    	$lMontantMaxActuel = $this->get('jpi_solux.manager.achat')->getMontantMax($famille);
    	
    	return $this->render('JPISoluxBundle:Caisse:add.html.twig', array(
    			"famille" => $famille,
    			"montantMaxAchat" => $lMontantMaxActuel,
    			'form' => $form->createView(),
    			'form_achat' => $formAchat->createView(),
    			"achat" => $achat
    	));
    }
    
    /**
     * @Route("/achats/{id}", name="jpi_solux_caisse_achats_show", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Achat $achat)
    {
    	$famille = $achat->getFamille();
    	
    	$form = $this->createForm(new CaisseRechercheProduitType());
    	$formAchat = $this->createForm(new AchatType(),$achat);
    	$formDelete = $this->createForm(new DeleteType());
    	 
    	//On vérifie que les valeurs entrées sont correctes
    	$formAchat->handleRequest($request);
    	// Form valid
    	if($formAchat->isSubmitted() && $formAchat->isValid())
    	{
    		// Enregistrement de l'achat
    		$this->get('jpi_solux.manager.achat')->set($achat);
    		$request->getSession()->getFlashBag()->add('success', 'Achat enregistré avec succés.');
    		return $this->redirect($this->generateUrl('jpi_solux_caisse_achats'));
    	}
    	 
    	$lMontantMaxActuel = $this->get('jpi_solux.manager.achat')->getMontantMax($famille);
    	 
    	return $this->render('JPISoluxBundle:Caisse:edit.html.twig', array(
    			"famille" => $famille,
    			"montantMaxAchat" => $lMontantMaxActuel,
    			'form' => $form->createView(),
    			'form_achat' => $formAchat->createView(),
    			"achat" => $achat,
    			'formDelete' => $formDelete->createView(),
    			"pathDelete" => $this->generateUrl('jpi_solux_caisse_achats_delete', array('id' => $achat->getId()))
    	));
    }

    /**
     * @Route("/add/produits", name="jpi_solux_caisse_liste_produit")
     * @Method({"POST"})
     */
    public function listeProduitAction() {    	
    	$data = $this->getRequest()->request->get('id');
	    $produits = $this->get('jpi_solux.manager.produit')->getProduits($data);
	    	
	    foreach($produits as $produit) {
	    	$produit->getCategorie()->eraseProduits();
	    }
	    	
	    $serializer = $this->container->get('serializer');
	    $response = new Response($serializer->serialize($produits, 'json'));
	    $response->headers->set('Content-Type', 'application/json');
	    	
	    return $response;
    }
    
    /**
     * @Route("/produit/{id}", name="jpi_solux_caisse_produit", requirements={"id" = "\d+"})
     * @Method({"POST"})
     */
    public function produitAction(Request $request, Famille $famille) {
    	$serializer = $this->container->get('serializer');
    	$quantiteAchatProduit = "";
    	$produitReturn = "";
    	
    	// Le formulaire
    	$produit = new Produit();
    	$form = $this->createForm(new CaisseRechercheProduitType(), $produit);
    	
    	//Mapping des données
    	$form->handleRequest($request);
    	
    	// Si le form est en soumission
    	if($form->isSubmitted())
    	{
    		$produitRecherche = "";    		
    		// Ne pas envoyer la requête si les deux champs sont vides
    		if(!(empty($produit->getCodeBarre()) && empty($produit->getNom()))) {
    			 				
    			//On va récupérer la méthode dans le repository afin de trouver le produit
    			$produits = $this->get('jpi_solux.manager.produit')->findProduitByParametres($produit, $famille->countMembres());
    			
    			// Traitement du produit si la recherche retoure des produits
    			if(!empty($produits)) {
    				$produitRecherche = $produits[0];
    				// Ne pas charger les produits de la catégorie
    				$produitRecherche->getCategorie()->eraseProduits();
    			
    				// Si le produit à des limites chargement de la quantité d'achat pour la famille
    				$limites = $produitRecherche->getLimites();
    				if(!empty($limites) && isset($limites[0])) {
    					$quantiteAchatProduit = $this->get('jpi_solux.manager.achat')->getTotalAchatProduitSurPeriode($famille->getId(), $limites[0]->getDuree(), $produitRecherche->getId());
    				}
    			}
    		}
    		$produitReturn = $produitRecherche;
    	}
    	
    	// Retour des informations en JSON
    	$response = new Response($serializer->serialize(array("produit" => $produitReturn, "quantiteAchat" => $quantiteAchatProduit), 'json'));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
    /**
     * @Route("/achats", name="jpi_solux_caisse_achats")
     * @Method({"GET"})
     */
    public function listeAchatsAction()
    {
    	// La liste des familles
    	$listeFamille = $this->get('jpi_solux.manager.famille')->getListe();
    	return $this->render('JPISoluxBundle:Caisse:listeAchats.html.twig', array("listeFamille" => $listeFamille));
    }
    
    /**
     * @Route("/achats/famille/{id}", name="jpi_solux_caisse_achats_famille", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function listeAchatsFamilleAction(Famille $famille) {
    	// La liste des achats pour une famille
    	$listeAchats = $this->get('jpi_solux.manager.achat')->findByFamille($famille);
    	 
    	return $this->render('JPISoluxBundle:Caisse:listeAchatsFamille.html.twig',
    			array("listeAchats" => $listeAchats,
    					"famille" => $famille
    			));
    }
    
    /**
     * @Route("/achats/delete/{id}", name="jpi_solux_caisse_achats_delete", requirements={"id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, Achat $achat){
    	$form = $this->createForm(new DeleteType());
    	
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
    	
    		$this->get('jpi_solux.manager.achat')->delete($achat);
    		$this->container->get('session')->getFlashBag()->set('success', 'Achat supprimé avec succés.');
    		
    		return $this->redirect($this->generateUrl('jpi_solux_caisse_achats'));
    	}
    	return $this->render('JPISoluxBundle:Form:delete.html.twig',array(
				"formDelete" => $form->createView(),
				"pathDelete" => $this->generateUrl('jpi_solux_caisse_achats_delete', array('id' => $achat->getId())),
				"entityLabel" => $achat->getId()
		));
    }
}
?>
