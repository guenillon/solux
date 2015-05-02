<?php
namespace JPI\SoluxBundle\Controller;

use JPI\SoluxBundle\Controller\EntityController;
use JPI\SoluxBundle\Entity\Famille;
use JPI\SoluxBundle\Form\Type\FamilleType;
use Symfony\Component\HttpFoundation\Request;

class FamilleController extends EntityController
{
	public function __construct()
	{
		$this->entityClass = new Famille();
		$this->entityTypeClass = new FamilleType();
	
		$this->entityLabel = "famille";
		$this->entityName = "Famille";
	
		$this->pathList = 'jpi_solux_famille';
	
		$this->repository = 'JPISoluxBundle:Famille';

		$this->exportAttributes = array(
				"header" => array("Nom", "Prénom"),
				"attribute" => array("nom", "prenomChef"));
		parent::__construct();
	}
	
	public function showAction($id)
	{
		$entity = $this->getEntity($id);	
		$template = 'JPISoluxBundle:'.$this->entityName.':show.html.twig';
		
		$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
		$tauxParticipation = $repository->getTauxParticipation($id);
	
		return $this->render($template, array(
				"pathEdit" => $this->generateUrl($this->pathEdit, array('id' => $entity->getId())),
				"pathDelete" => $this->generateUrl($this->pathDelete, array('id' => $entity->getId())),
				"entityName" => $this->entityLabelShow,
				"entityLabel" => $entity->getNom(),
				"famille" => $entity,
				"taux" => $tauxParticipation
		));
	}
	
	public function editAction(Request $request, $id)
	{
		$entity = $this->getEntity($id);
		return $this->edit($entity, $request, $this->entityTypeClass, $entity->getNom());
	}
}
?>
