<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JPI\CoreBundle\Export\Classes\JPIExportConfig;
use JPI\SoluxBundle\Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class EntityController extends Controller
{
	protected $pathList;
	protected $pathShow;
	protected $pathAdd;
	protected $pathEdit;
	protected $pathDelete;
	protected $pathExport;
	protected $fileNameExport;
	protected $repository;
	protected $entityName;
	protected $entityLabel;
	protected $showAttributes = array("header" => array("Id"), "attribute" => array("id"));
	protected $exportAttributes = array("header" => array("Id"), "attribute" => array("id"));
	protected $entityClass;
	protected $entityTypeClass;
	protected $entityLabelListe;
	protected $entityLabelShow;
	protected $entityLabelAdd;
	protected $entityLabelEdit;

	public function __construct()
	{
		$this->generateParam();
	}
	
	protected function generateParam()
	{
		$this->pathShow = $this->pathList.'_show';
		$this->pathAdd = $this->pathList.'_add';
		$this->pathEdit = $this->pathList.'_edit';
		$this->pathDelete = $this->pathList.'_delete';
		$this->pathExport = $this->pathList.'_export';
		

		$this->entityLabelListe = $this->entityLabel.".liste.titre";
		$this->entityLabelShow = $this->entityLabel.".show.titre";
		$this->entityLabelAdd = $this->entityLabel.".add.titre";
		$this->entityLabelEdit = $this->entityLabel.".edit.titre";
		
		$this->fileNameExport = $this->entityLabel.".export.titre";
	}
	
	protected function getRepository($repository = NULL)
	{
		if(is_null($repository))
		{
			$repository = $this->repository;
		}
		return $this->getDoctrine()->getManager()->getRepository($repository);
	}

	protected function getEntity($id) {
		$entity = $this->getRepository()->find($id);
		if (null === $entity) {
			throw new NotFoundHttpException("L'élément d'id ".$id." n'existe pas.");
		}
		return $entity;
	}
	
	public function listeAction()
	{
		$repository = $this->getRepository();
		$liste = $repository->findAll();
		 
		return $this->render('JPISoluxBundle:'.$this->entityName.':liste.html.twig', array(
				"liste" => $liste,
				"nombreEntity" => count($liste),
				"pathAdd" => $this->generateUrl($this->pathAdd),
				"pathExport" => $this->pathExport,
				"entityName" => $this->entityLabelListe,
				"pathShow" => $this->pathShow				
		));
	}

	public function showAction($id)
	{
		$entity = $this->getEntity($id);
		$content = $this->getShowAttributes($entity);
		
		return $this->show($entity, $content);
	}
	
	protected function getShowAttributes($entity)
	{
		$content = array();
		$lMembres = $entity->getAttributes($this->showAttributes["attribute"]);
		$i = 0;
		foreach($lMembres as $attribut)
		{
			array_push($content, array(
			"nom" => $this->showAttributes["header"][$i],
			"value" => $attribut
			));
			$i++;
		}
		return $content;
	}
	
	protected function show($entity, $content, $twig = null, $label = null)
	{
		if(is_null($twig) || !$twig) {
			$template = 'JPISoluxBundle:Form:show.html.twig';
		} else {
			$template = 'JPISoluxBundle:'.$this->entityName.':show.html.twig';
		}
		
		if(is_null($label)) {
			$entityLabel = $entity->getId();
		} else {
			$entityLabel = $label;			
		}
		
		return $this->render($template, array(
				"entity" => $entity,
				"pathEdit" => $this->generateUrl($this->pathEdit, array('id' => $entity->getId())),
				"pathDelete" => $this->generateUrl($this->pathDelete, array('id' => $entity->getId())),
				"entityName" => $this->entityLabelShow,
				"entityLabel" => $entityLabel,
				"showContent" => $content
		));
	}
	
	public function exportAction($format)
	{	
		$repository = $this->getRepository();
		$liste = $repository->findAll();
				
		return $this->export($format, $liste);
	}
	
	protected function export($format, $liste)
	{
		$lconfig = new JPIExportConfig($this->fileNameExport, $format, $this->exportAttributes, $liste);
		return $this->get('jpi_core.export')->export($lconfig);
	}
	
	public function addAction(Request $request) 
	{
		return $this->add($this->entityClass, $request, $this->entityTypeClass);
	}
	
	protected function add($entity, Request $request, $formType)
	{
		$form = $this->createForm($formType, $entity);
		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
				
			$request->getSession()->getFlashBag()->add('success', 'Ajout effectué avec succés.');
			return $this->redirect($this->generateUrl($this->pathShow, array('id' => $entity->getId())));
		}
		
		return $this->render('JPISoluxBundle:Form:add.html.twig', array(
				'form' => $form->createView(),
				'pathReturn' => $this->generateUrl($this->pathList),
				'entityName' => $this->entityLabelAdd
		));
	}
    
    public function editAction(Request $request, $id)
    {
		$entity = $this->getEntity($id);
		return $this->edit($entity, $request, $this->entityTypeClass);
    }
    
    protected function edit($entity, Request $request, $formType, $label = null)
    {
    	$form = $this->createForm($formType, $entity);
    	
    	if ($form->handleRequest($request)->isValid()) {
    		 
    		$em = $this->getDoctrine()->getManager();
    		$em->flush();
    		 
    		$this->setFlash('success', 'Modification effectuée avec succés.');
    		return $this->redirect($this->generateUrl($this->pathShow, array('id' => $entity->getId())));
    	}
    	
    	if(is_null($label)) {
    		$entityLabel = $entity->getId();
    	} else {
    		$entityLabel = $label;
    	}
    	
    	return $this->render('JPISoluxBundle:Form:edit.html.twig',array(
    			'form' => $form->createView(),
    			"pathReturn" => $this->generateUrl($this->pathShow, array('id' => $entity->getId())),
    			"entityName" => $this->entityLabelEdit,
    			"entityLabel" => $entityLabel
    			)
    	);
    }
    
    public function deleteAction($id)
    {    	 
    	$entity = $this->getEntity($id);
    	$this->delete($entity);
      	$this->deleteMsg();
    	return $this->deleteRedirect();
    }
    
    protected function delete($entity)
    {
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($entity);
      	$em->flush();
    }

    protected function deleteMsg()
    {
    	$this->setFlash('success', 'Suppression effectuée avec succés.');
    }
    
    protected function deleteRedirect()
    {
    	return $this->redirect($this->generateUrl($this->pathList));
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