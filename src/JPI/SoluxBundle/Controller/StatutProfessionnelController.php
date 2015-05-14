<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Controller\EntityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JPI\SoluxBundle\Entity\StatutProfessionnel;

/**
 * @Route("/statut_professionnel")
 */
class StatutProfessionnelController extends EntityController
{
	public function __construct()
	{
		parent::__construct();
		$this->manager = 'jpi_solux.manager.statut_professionnel';
	}
	
	/**
	 * @Route("/", name="jpi_solux_statut_professionnel")
	 * @Method({"GET"})
	 */
	public function listeAction()
	{
		return parent::listeAction();
	}
	
	/**
	 * @Route("/add", name="jpi_solux_statut_professionnel_add")
	 * @Method({"GET", "POST"})
	 */
	public function addAction(Request $request)
	{
		return parent::addAction($request);
	}
	
	/**
	 * @Route("/{id}", name="jpi_solux_statut_professionnel_show", requirements={"id" = "\d+"})
	 * @Method({"GET"})
	 */
	public function showAction(StatutProfessionnel $id)
	{
		$statutProfessionnel = $id;	
		return $this->show($statutProfessionnel, $statutProfessionnel->getNom());
	}
	
	/**
	 * @Route("/edit/{id}", name="jpi_solux_statut_professionnel_edit", requirements={"id" = "\d+"})
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, StatutProfessionnel $id)
	{
		$this->getManager()->setEntity($id);
	
		$form = $this->getFormUpdate();
	
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
	
			$this->getManager()->set($this->getManager()->getEntity());
			$this->flashMsg('update');
	
			return $this->redirectUpdate();
		}
	
		return $this->renderUpdate($form, $id->getNom());
	}
	
	/**
	 * @Route("/delete/{id}", name="jpi_solux_statut_professionnel_delete", requirements={"id" = "\d+"})
	 * @Method({"DELETE"})
	 */
	public function deleteAction(Request $request, StatutProfessionnel $id)
	{
		$this->getManager()->setEntity($id);
		return $this->delete($request);
	}
	
	/**
	 * @Route("/export.{format}", name="jpi_solux_statut_professionnel_export", requirements={"format" = "%jpi.export.format%"}, defaults={"format" = "%jpi.export.default%"})
	 * @Method({"GET"})
	 */
	public function exportAction($format)
	{
		return parent::exportAction($format);
	}
}
?>
