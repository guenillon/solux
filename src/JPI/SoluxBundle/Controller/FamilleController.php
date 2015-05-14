<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Controller\EntityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JPI\SoluxBundle\Entity\Famille;

/**
 * @Route("/famille")
 */
class FamilleController extends EntityController
{
	public function __construct()
	{
		parent::__construct();
		$this->manager = 'jpi_solux.manager.famille';
	}
	
	/**
	 * @Route("/", name="jpi_solux_famille")
	 * @Method({"GET"})
	 */
	public function listeAction()
	{
		return parent::listeAction();
	}
	
	/**
	 * @Route("/add", name="jpi_solux_famille_add")
	 * @Method({"GET", "POST"})
	 */
	public function addAction(Request $request)
	{
		return parent::addAction($request);
	}
	
	/**
	 * @Route("/{id}", name="jpi_solux_famille_show", requirements={"id" = "\d+"})
	 * @Method({"GET"})
	 */
	public function showAction(Famille $id)
	{
		$famille = $id;	
		$this->templateShowEntity = true;
		
		$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
		$tauxParticipation = $repository->getTauxParticipation($id);
		
		return $this->render($this->getTemplateShow(), array(
				"pathEdit" => $this->generateUrl($this->getPathEdit(), array('id' => $famille->getId())),
				"pathDelete" => $this->generateUrl($this->getPathDelete(), array('id' => $famille->getId())),
				"entityName" => $this->getEntityLabelShow(),
				"entityLabel" => $famille->getNom(),
				"famille" => $famille,
				"taux" => $tauxParticipation,
				'formDelete' => $this->getFormDelete()->createView(),
		));
	}
	
	/**
	 * @Route("/edit/{id}", name="jpi_solux_famille_edit", requirements={"id" = "\d+"})
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, Famille $id)
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
	 * @Route("/delete/{id}", name="jpi_solux_famille_delete", requirements={"id" = "\d+"})
	 * @Method({"DELETE"})
	 */
	public function deleteAction(Request $request, Famille $id)
	{
		$this->getManager()->setEntity($id);
		return $this->delete($request);
	}
	
	/**
	 * @Route("/export.{format}", name="jpi_solux_famille_export", requirements={"format" = "%jpi.export.format%"}, defaults={"format" = "%jpi.export.default%"})
	 * @Method({"GET"})
	 */
	public function exportAction($format)
	{
		return parent::exportAction($format);
	}
}
?>
