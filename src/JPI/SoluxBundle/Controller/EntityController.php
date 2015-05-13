<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JPI\CoreBundle\Export\Classes\JPIExportConfig;
use Symfony\Component\HttpFoundation\Request;

abstract class EntityController extends Controller
{
	protected $manager;
	protected $bundleName;
	protected $templateShowEntity;
	
	public function __construct()
	{
		$this->templateShowEntity = false;
		$this->bundleName = 'JPISoluxBundle';
	}

	/* Getter des noms */
	protected function getPathList() {
		return $this->getManager()->getPathList();
	}
	
	protected function getEntityDisplayLabel() {
		return $this->getManager()->getEntityDisplayLabel();
	}

	protected function getEntityTranslateLabel() {
		return $this->getManager()->getEntityTranslateLabel();
	}
	
	protected function getPathShow() {
		return $this->getPathList().'_show';
	}
	
	protected function getPathAdd() {
		return $this->getPathList().'_add';
	}
	
	protected function getPathEdit() {
		return $this->getPathList().'_edit';
	}
	
	protected function getPathDelete() {
		return $this->getPathList().'_delete';
	}
	
	protected function getPathExport() {
		return $this->getPathList().'_export';
	}
	
	protected function getEntityLabelListe() {
		return $this->getEntityTranslateLabel().".liste.titre";
	}
	
	protected function getEntityLabelShow() {
		return $this->getEntityTranslateLabel().".show.titre";
	}
	
	protected function getEntityLabelAdd() {
		return $this->getEntityTranslateLabel().".add.titre";
	}
	
	protected function getEntityLabelEdit() {
		return $this->getEntityTranslateLabel().".edit.titre";
	}
	
	protected function getFileNameExport() {
		return $this->getEntityTranslateLabel().".export.titre";
	}
	
	protected function getRepository() {
		return $this->bundleName.':'.$this->getEntityDisplayLabel();
	}
	
	protected function getTemplateListe() {
		return $this->bundleName.':'.$this->getEntityDisplayLabel().':liste.html.twig';
	}
	
	protected function getTemplateAdd() {
		return $this->bundleName.':Form:add.html.twig';
	}
	
	protected function getTemplateShow() {
		if($this->templateShowEntity) {
			return $this->bundleName.':'.$this->getEntityDisplayLabel().':show.html.twig';
		} else {
			return $this->bundleName.':Form:show.html.twig';
		}
	}
				
	protected function getTemplateUpdate() {
		return $this->bundleName.':Form:edit.html.twig';
	}

	/* Utils */
	protected function getManager() {
		return $this->container->get($this->manager);
	}
		
	/**
	 * @param string $action
	 * @param string $value
	 */
	protected function setFlash($action, $value)
	{
		$this->container->get('session')->getFlashBag()->set($action, $value);
	}
	
	protected function flashMsg($type)
	{
		$action = '';
		$value = '';
	
		switch($type) {
			case 'add':
				$action = 'success';
				$value = 'Ajout effectué avec succés.';
				break;
	
			case 'update':
				$action = 'success';
				$value = 'Modification effectuée avec succés.';
				break;
	
			case 'delete':
				$action = 'success';
				$value = 'Suppression effectuée avec succés.';
				break;
		}
	
		$this->setFlash($action, $value);
	}
	
	protected function getForm($entity = null)
	{
		if(is_null($entity))
		{
			$entity = $this->getManager()->getEntity();
		}
		return $this->createForm($this->getManager()->getEntityTypeClass(), $entity);
	}
	
	protected function getShowAttributes($entity)
	{
		$content = array();
		$showAttributes = $this->getManager()->getShowAttributes();
		$lMembres = $entity->getAttributes($showAttributes["attribute"]);
		$i = 0;
		foreach($lMembres as $attribut)
		{
			array_push($content, array(
			"nom" => $showAttributes["header"][$i],
			"value" => $attribut
			));
			$i++;
		}
		return $content;
	}

	/* Actions */
	/* Liste */
	public function listeAction()
	{
		$liste = $this->getListeEntity();
		return $this->renderListeEntity($liste);
	}
	
	protected function getListeEntity()
	{
		return $this->getManager()->getListe();
	}
	
	protected function renderListeEntity($liste)
	{
		return $this->render($this->getTemplateListe(),
			array(
					"liste" => $liste,
					"pathAdd" => $this->generateUrl($this->getPathAdd()),
					"pathExport" => $this->getPathExport(),
					"entityName" => $this->getEntityLabelListe(),
					"pathShow" => $this->getPathShow()
			));
	}
	
	/* Add */
	public function addAction(Request $request)
	{
		$form = $this->getFormAdd();
	
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
				
			$this->getManager()->set($this->getManager()->getEntity());
			$this->flashMsg('add');
				
			return $this->redirectAdd();
		}
	
		return $this->renderAdd($form);
	}
	
	protected function getFormAdd()
	{
		return $this->getForm();
	}
	
	protected function redirectAdd()
	{
		return $this->redirect($this->generateUrl($this->getPathShow(), array('id' => $this->getManager()->getEntity()->getId())));
	}
	
	protected function renderAdd($form)
	{
		return 	$this->render($this->getTemplateAdd(), array(
				'form' => $form->createView(),
				'pathReturn' => $this->generateUrl($this->getPathList()),
				'entityName' => $this->getEntityLabelAdd()
		));
	}
		
	/* Show */
	protected function show($entity, $label = null)
	{	
		if(is_null($label)) {
			$entityLabel = $entity->getId();
		} else {
			$entityLabel = $label;
		}
		
		$templateVar = array(
				"pathEdit" => $this->generateUrl($this->getPathEdit(), array('id' => $entity->getId())),
				"pathDelete" => $this->generateUrl($this->getPathDelete(), array('id' => $entity->getId())),
				"entityName" => $this->getEntityLabelShow(),
				"entityLabel" => $entityLabel,
				"showContent" => $this->getShowAttributes($entity)
		);
		
		if($this->templateShowEntity) {
			$templateVar["entity"] = $entity;
		}	
	
		return $this->render($this->getTemplateShow(), $templateVar);
	}
	
	/* Edit */	
	protected function getFormUpdate()
	{
		return $this->getForm($this->getManager()->getEntity());
	}

	protected function redirectUpdate()
	{
		return $this->redirect($this->generateUrl($this->getPathShow(), array('id' => $this->getManager()->getEntity()->getId())));
	}
	
	protected function renderUpdate($form, $label = null)
	{
		if(is_null($label)) {
			$entityLabel = $entity->getId();
		} else {
			$entityLabel = $label;
		}
		
		return $this->render($this->getTemplateUpdate(),array(
				'form' => $form->createView(),
				"pathReturn" => $this->generateUrl($this->getPathShow(), array('id' => $this->getManager()->getEntity()->getId())),
				"entityName" => $this->getEntityLabelEdit(),
				"entityLabel" => $entityLabel
		));
	}

	/* Delete */	
	protected function delete($entity)
	{
		$this->getManager()->delete($entity);
	}
	
	protected function redirectDelete()
	{
		return $this->redirect($this->generateUrl($this->getPathList()));
	}
	
	protected function renderDelete($form)
	{	
		return $this->render($this->getTemplateUpdate(),array(
				'form' => $form->createView(),
				"pathReturn" => $this->generateUrl($this->getPathShow(), array('id' => $this->getManager()->getEntity()->getId())),
				"entityName" => $this->getEntityLabelEdit(),
				"entityLabel" => $entityLabel
		));
	}
	
	/* Export */
	public function exportAction($format)
	{	
		$liste = $this->getListeEntity();				
		return $this->export($format, $liste);
	}
	
	protected function export($format, $liste)
	{
		$lconfig = new JPIExportConfig($this->getFileNameExport(), $format, $this->getManager()->getExportAttributes(), $liste);
		return $this->get('jpi_core.export')->export($lconfig);
	}
}
?>
