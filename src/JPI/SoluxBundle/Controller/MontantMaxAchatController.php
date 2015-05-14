<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Controller\EntityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JPI\SoluxBundle\Entity\MontantMaxAchat;

/**
 * @Route("/montant_max_achat")
 */
class MontantMaxAchatController extends EntityController
{
	public function __construct()
	{
		parent::__construct();
		$this->manager = 'jpi_solux.manager.montant_max_achat';
	}
	
	/**
	 * @Route("/", name="jpi_solux_montant_max_achat")
	 * @Method({"GET"})
	 */
	public function listeAction()
	{
		return parent::listeAction();
	}
	
	/**
	 * @Route("/add", name="jpi_solux_montant_max_achat_add")
	 * @Method({"GET", "POST"})
	 */
	public function addAction(Request $request)
	{
		return parent::addAction($request);
	}
	
	/**
	 * @Route("/{id}", name="jpi_solux_montant_max_achat_show", requirements={"id" = "\d+"})
	 * @Method({"GET"})
	 */
	public function showAction(MontantMaxAchat $id)
	{
		$montantMaxAchat = $id;
		$this->templateShowEntity = true;
	
		return $this->show($montantMaxAchat, $montantMaxAchat->getId());
	}
	
	/**
	 * @Route("/edit/{id}", name="jpi_solux_montant_max_achat_edit", requirements={"id" = "\d+"})
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, MontantMaxAchat $id)
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
	 * @Route("/delete/{id}", name="jpi_solux_montant_max_achat_delete", requirements={"id" = "\d+"})
	 * @Method({"DELETE"})
	 */
	public function deleteAction(Request $request, MontantMaxAchat $id)
	{
		$this->getManager()->setEntity($id);
		return $this->delete($request);
	}
	
	/**
	 * @Route("/export.{format}", name="jpi_solux_montant_max_achat_export", requirements={"format" = "%jpi.export.format%"}, defaults={"format" = "%jpi.export.default%"})
	 * @Method({"GET"})
	 */
	public function exportAction($format)
	{
		return parent::exportAction($format);
	}
}
?>
