<?php
namespace JPI\SoluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JPI\CoreBundle\Export\Classes\JPIExportConfig;
use JPI\SoluxBundle\Controller\EntityController;
use JPI\SoluxBundle\Entity\Famille;
use JPI\SoluxBundle\Form\FamilleType;
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
	
		/*$this->showAttributes = array(
				"header" => array("Nom", "Prénom", "Date d'entrée", "Date de sortie"),
				"attribute" => array("nom", "prenomChef", "dateEntree", "dateSortie"));*/
	
		$this->exportAttributes = array(
				"header" => array("Nom", "Prénom"),
				"attribute" => array("nom", "prenomChef"));
		parent::__construct();
	}
	
	public function showAction($id)
	{
		$entity = $this->getEntity($id);	
		$template = 'JPISoluxBundle:'.$this->entityName.':show.html.twig';
	
		return $this->render($template, array(
				"entity" => $entity,
				"pathEdit" => $this->generateUrl($this->pathEdit, array('id' => $entity->getId())),
				"pathDelete" => $this->generateUrl($this->pathDelete, array('id' => $entity->getId())),
				"entityName" => $this->entityLabelShow,
				"entityLabel" => $entity->getNom(),
				"famille" => $entity
		));
	}
	
	public function editAction(Request $request, $id)
	{
		$entity = $this->getEntity($id);
		return $this->edit($entity, $request, $this->entityTypeClass, $entity->getNom());
	}
	
	/*public function listeFamilleAction()
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
		$listeFamille = $repository->findAll();
		 
		return $this->render('JPISoluxBundle:Famille:ListeFamille.html.twig', array("listeFamille" => $listeFamille));
	}
	
	/*public function exportAction($format)
	{
		// nom du fichier
		$lNomFichier = "Familles";
	
		$repository = $this->getDoctrine()->getManager()->getRepository('JPISoluxBundle:Famille');
		$listeFamille = $repository->findAll();
		 
		// Création du phpExcel
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
		 
		// Alimentation du fichier
		$phpExcelObject->setActiveSheetIndex(0)
		->setCellValue('A1', "Nom")
		->setCellValue('B1', "Prénom")
		->setCellValue('C1', "Date d'entrée")
		->setCellValue('D1', "Date de sortie");
		 
		$i = 2;
		foreach($listeFamille as $lFamille) {
			$phpExcelObject->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $lFamille->getNom())
			->setCellValue('B'.$i, $lFamille->getPrenomChef())
			->setCellValue('C'.$i, $lFamille->getDateEntree())
			->setCellValue('D'.$i, $lFamille->getDateSortie());
			$i++;
		}
	
		$lconfig = new JPIExportConfig($lNomFichier, $format, $phpExcelObject);
		$response = $this->get('jpi_core.export')->export($lconfig);
		return $response;
	}*/
}
?>