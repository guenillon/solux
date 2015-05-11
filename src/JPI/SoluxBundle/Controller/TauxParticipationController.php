<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Controller\EntityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JPI\SoluxBundle\Entity\TauxParticipation;

/**
 * @Route("/taux_participation")
 */
class TauxParticipationController extends EntityController
{
	public function __construct()
	{
		parent::__construct();
		$this->manager = 'jpi_solux.manager.taux_participation';
	}
	
	/**
	 * @Route("/", name="jpi_solux_taux_participation")
	 * @Method({"GET"})
	 */
	public function listeAction()
	{
		return parent::listeAction();
	}
	
	/**
	 * @Route("/add", name="jpi_solux_taux_participation_add")
	 * @Method({"GET", "POST"})
	 */
	public function addAction(Request $request)
	{
		return parent::addAction($request);
	}
	
	/**
	 * @Route("/{id}", name="jpi_solux_taux_participation_show", requirements={"id" = "\d+"})
	 * @Method({"GET"})
	 */
	public function showAction(TauxParticipation $id)
	{
		$tauxParticipation = $id;
		$this->templateShowEntity = true;
	
		return $this->show($tauxParticipation, $tauxParticipation->getId());
	}
	
	/**
	 * @Route("/edit/{id}", name="jpi_solux_taux_participation_edit", requirements={"id" = "\d+"})
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, TauxParticipation $id)
	{
		$this->getManager()->setEntity($id);
	
		$form = $this->getFormUpdate();
	
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
	
			$this->getManager()->set($this->getManager()->getEntity());
			$this->flashMsg('update');
	
			return $this->redirectUpdate();
		}
	
		return $this->renderUpdate($form, $id->getId());
	}
	
	/**
	 * @Route("/delete/{id}", name="jpi_solux_taux_participation_delete", requirements={"id" = "\d+"})
	 * @Method({"GET"})
	 */
	public function deleteAction(TauxParticipation $id)
	{
		$this->delete($id);
		$this->flashMsg('delete');
		return $this->redirectDelete();
	}
	
	/**
	 * @Route("/export.{format}", name="jpi_solux_taux_participation_export", requirements={"format" = "%jpi.export.format%"}, defaults={"format" = "%jpi.export.default%"})
	 * @Method({"GET"})
	 */
	public function exportAction($format)
	{
		return parent::exportAction($format);
	}
}
?>
