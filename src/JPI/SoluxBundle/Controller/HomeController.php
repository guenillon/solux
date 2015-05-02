<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {        
        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('ROLE_ADMIN') ) 
        {
        	return $this->redirect($this->generateUrl('jpi_liste_user'));
        } 
        else if($securityContext->isGranted('ROLE_USER') ) 
        {
        	return $this->redirect($this->generateUrl('jpi_solux_caisse'));
        }
        else
        {
        	return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
    }
}
?>