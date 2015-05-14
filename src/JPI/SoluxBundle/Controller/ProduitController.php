<?php
namespace JPI\SoluxBundle\Controller;

use JPI\SoluxBundle\Controller\EntityController;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Entity\Produit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/produit")
 */
class ProduitController extends EntityController
{
	public function __construct()
	{
		parent::__construct();
		$this->manager = 'jpi_solux.manager.produit';
	}
	
	/**
	 * @Route("/", name="jpi_solux_produit")
	 * @Method({"GET"})
	 */
	public function listeAction()
	{
		return parent::listeAction();
	}
	
	/**
	 * @Route("/add", name="jpi_solux_produit_add")
	 * @Method({"GET", "POST"})
	 */
	public function addAction(Request $request)
	{
		return parent::addAction($request);
	}
	
	/**
	 * @Route("/{id}", name="jpi_solux_produit_show", requirements={"id" = "\d+"})
	 * @Method({"GET"})
	 */
	public function showAction(Produit $id)
	{
		$produit = $id;
		$this->templateShowEntity = true;
	
		return $this->show($produit, $produit->getNom());
	}
	
	/**
	 * @Route("/edit/{id}", name="jpi_solux_produit_edit", requirements={"id" = "\d+"})
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, Produit $id)
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
	 * @Route("/delete/{id}", name="jpi_solux_produit_delete", requirements={"id" = "\d+"})
	 * @Method({"DELETE"})
	 */
	public function deleteAction(Request $request, Produit $id)
	{
		$this->getManager()->setEntity($id);
		return $this->delete($request);
	}
	
	/**
	 * @Route("/export.{format}", name="jpi_solux_produit_export", requirements={"format" = "%jpi.export.format%"}, defaults={"format" = "%jpi.export.default%"})
	 * @Method({"GET"})
	 */
	public function exportAction($format)
	{
		return parent::exportAction($format);
	}
}
?>
