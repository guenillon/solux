<?php
namespace JPI\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JPI\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JPI\CoreBundle\Export\Classes;
use JPI\CoreBundle\Export\Classes\JPIExportConfig;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JPI\UserBundle\Form\Type\DeleteType;

class AdminController extends Controller
{
	/**
	 * @Route("/", name="jpi_liste_user")
	 * @Method({"GET"})
	 */
    public function listeUserAction()
    {        
    	$userManager = $this->get('fos_user.user_manager');
    	$lListeUsers = $userManager->findUsers();
    	
        return $this->render('JPIUserBundle:Admin:ListeUser.html.twig', array(
      		'liste_users' => $lListeUsers
   		));
    }
    
    /**
     * @Route("/user/{id}", name="jpi_show_user_profile", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function showAction(User $user, $id)
    {
    	$form = $this->createForm(new DeleteType());
    	return $this->render('JPIUserBundle:Admin:show.html.twig', array(
    			'user' => $user,
    			'formDelete' => $form->createView()
    	));
    }
    
    /**
     * @Route("/edit/{id}", name="jpi_user_edit", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function editAction(User $user, Request $request, $id)
    {
    	$form = $this->get('form.factory')->create('jpi_user_profile', $user);

        if ($form->handleRequest($request)->isValid()) {
        	
        	// L'admin actuel ne peut pas modifier ses propores droits et redevenir simple utilisateur
        	$currentUser= $this->getUser();
        	if($currentUser->getId() == $user->getId()) {
        		$user->setRoles($currentUser->getRoles());
        	}
        	
        	$userManager = $this->get('fos_user.user_manager');
        	$userManager->updateUser($user);
        	
            $this->setFlash('fos_user_success', 'profile.flash.updated');
            return $this->redirect($this->generateUrl('jpi_show_user_profile', array('id' => $id)));
        }

        return $this->render('FOSUserBundle:Admin:editUser.html.twig',
            array('form' => $form->createView(), 'user' => $user)
        );
    }
    
    /**
     * @Route("/delete/{id}", name="jpi_user_delete", requirements={"id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, User $user, $id)
    {
    	$currentUser= $this->getUser();
    	
    	if($currentUser == $user) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    	
    	$form = $this->createForm(new DeleteType());
    	
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
    	
    		$userManager = $this->get('fos_user.user_manager');
	    	$userManager->deleteUser($user);
	    	$this->setFlash('success', 'delete.flash.success');
	    	return $this->redirect($this->generateUrl('jpi_liste_user'));
    	}
    	
    	return $this->render('JPIUserBundle:Admin:delete.html.twig', array(
    			'user' => $user,
    			'formDelete' => $form->createView()
    	));
    }
    
    /**
     * @Route("/add", name="jpi_user_add")
     * @Method({"GET", "POST"})
     */
    public function addAction()
    {
    	$form = $this->get('fos_user.registration.form');
    	$formHandler = $this->get('fos_user.registration.form.handler');
    	$process = $formHandler->process();
    	if ($process) { 		   	
    		$this->setFlash('success', 'registration.flash.user_created');
    		return $this->redirect($this->generateUrl('jpi_liste_user'));
    	}
    	
    	return $this->render('FOSUserBundle:Registration:register.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
    
    /**
     * @Route("/export.{format}", name="jpi_user_export", requirements={"format" = "%jpi.export.format%"}, defaults={"format" = "%jpi.export.default%"})
     * @Method({"GET"})
     */
    public function exportAction($format)
    {    	
    	// nom du fichier
    	$lNomFichier = "Utilisateurs";

    	// Récupération du user Manager
    	$userManager = $this->get('fos_user.user_manager');
    	// La Liste des user
    	$lListeUsers = $userManager->findUsers();
    	
    	// Création du phpExcel
    	$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
    	
    	// Alimentation du fichier
    	$phpExcelObject->setActiveSheetIndex(0)
    		->setCellValue('A1', "Nom d'utilisateur")
    		->setCellValue('B1', "Adresse e-mail");
    	
    	$i = 2;
    	foreach($lListeUsers as $lUser) {
    	$phpExcelObject->setActiveSheetIndex(0)
    		->setCellValue('A'.$i, $lUser->getUsername())
    		->setCellValue('B'.$i, $lUser->getEmail());
    		$i++;
    	}

    	$lconfig = new JPIExportConfig($lNomFichier, $format, NULL, NULL, $phpExcelObject);
    	$response = $this->get('jpi_core.export')->export($lconfig);
		return $response;
    }
    
    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
    	$this->container->get('session')->getFlashBag()->set($action, $value);
    }
}
?>
