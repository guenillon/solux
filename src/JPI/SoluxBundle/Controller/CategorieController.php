<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Controller\EntityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JPI\SoluxBundle\Entity\Categorie;

/**
 * @Route("/categorie")
 */
class CategorieController extends EntityController
{
	public function __construct()
	{		
		parent::__construct();
		$this->manager = 'jpi_solux.manager.categorie';
	}
	
	/**
	 * @Route("/", name="jpi_solux_categorie")
	 * @Method({"GET"})
	 */
	public function listeAction()
	{
		return parent::listeAction();
	}
	
	/**
	 * @Route("/add", name="jpi_solux_categorie_add")
	 * @Method({"GET", "POST"})
	 */
	public function addAction(Request $request)
	{
		return parent::addAction($request);
	}
	
	/**
	 * @Route("/{id}", name="jpi_solux_categorie_show", requirements={"id" = "\d+"})
	 * @Method({"GET"})
	 */
	public function showAction(Categorie $id)
	{
		$categorie = $id;
		$this->templateShowEntity = true;
		
		return $this->show($categorie, $categorie->getNom());
	}
	
	/**
	 * @Route("/edit/{id}", name="jpi_solux_categorie_edit", requirements={"id" = "\d+"})
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, Categorie $id)
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
	 * @Route("/delete/{id}", name="jpi_solux_categorie_delete", requirements={"id" = "\d+"})
	 * @Method({"GET"})
	 */
	public function deleteAction(Categorie $id)
	{
		$this->delete($id);
		$this->flashMsg('delete');
		return $this->redirectDelete();
	}
	
	/**
	 * @Route("/export.{format}", name="jpi_solux_categorie_export", requirements={"format" = "%jpi.export.format%"}, defaults={"format" = "%jpi.export.default%"})
	 * @Method({"GET"})
	 */
	public function exportAction($format)
	{
		return parent::exportAction($format);
	}
}
?>
