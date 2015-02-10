<?php
namespace JPI\CoreBundle\Export;

use JPI\CoreBundle\Export\Classes\JPIExportConfig;

class JPIExport
{	
	protected $title;
	protected $format;
	protected $phpExcelService;
	protected $exportAttributes;
	protected $data;
	protected $translator;
	protected $phpExcelObject;
	
	public function __construct(\Liuggio\ExcelBundle\Factory $phpExcelService, $translator) {
		$this->phpExcelService = $phpExcelService;
		$this->translator = $translator;
	}
	
	public function loadConfiguration(JPIExportConfig $configuration) {
		$this->title = $this->translator->trans($configuration->getTitle());
		$this->format = $configuration->getFormat();
		$this->exportAttributes = $configuration->getExportAttributes();
		$this->data = $configuration->getData();
		$this->phpExcelObject = $configuration->getPhpExcelObject();
	}

	public function export(Classes\JPIExportConfig $configuration)
  	{
  		// Chargement des informations
  		$this->loadConfiguration($configuration);
  		
  		// PAs de données préchargées, récupération des data
  		if(is_null($this->phpExcelObject) ) {
	  		// Création du phpExcel
	  		$this->phpExcelObject = $this->phpExcelService->createPHPExcelObject();
	  		
	  		// Le titre de l'onglet
	  		$this->phpExcelObject->getActiveSheet()->setTitle($this->title);
	
	  		// Les données
	  		$i = 2;
	  		foreach($this->data as $litem) {
	  			$lMembres = $litem->getAttributes($this->exportAttributes["attribute"]);
	  			$col = 'A';
	  			foreach($lMembres as $attribut)
	  			{
	  				$this->phpExcelObject->setActiveSheetIndex(0)->setCellValue($col.$i, $attribut);
	  				$col++;
	  			}
	  			$i++;
	  		}
	
	  		// Le header
	  		$i = 'A';
	  		foreach($this->exportAttributes["header"] as $nom) {
	  			$this->phpExcelObject->setActiveSheetIndex(0)->setCellValue($i.'1', $nom)->getColumnDimension($i)->setAutoSize(true);
	  			$i++;
	  		}

	  	}

	  	// L'onglet actif est le premier
	  	$this->phpExcelObject->setActiveSheetIndex(0);

    	return $this->exportHeader();
	}
		
	public function exportHeader() {
		// writer selon le format
		switch($this->format) {
			case "xls":
				$writer = $this->phpExcelService->createWriter($this->phpExcelObject, 'Excel5');
				break;
				 
			case "ods":
				$writer = $this->phpExcelService->createWriter($this->phpExcelObject, 'OpenDocument');
				break;
				 
			case "csv":
				$writer = $this->phpExcelService->createWriter($this->phpExcelObject, 'CSV')->setDelimiter(',')
				->setEnclosure('"')
				->setLineEnding("\r\n")
				->setSheetIndex(0);
				break;
				 
			case "xlsx":
			default:
				$writer = $this->phpExcelService->createWriter($this->phpExcelObject, 'Excel2007');
				break;
		}
		 
		// creation de la reponse
		$response = $this->phpExcelService->createStreamedResponse($writer);
		 
		// header selon le format
		switch($this->format) {
			case "xls":
				$response->headers->set('Content-Type', 'application/vnd.ms-excel');
				$response->headers->set('Content-Disposition', 'attachment;filename="'.$this->title.'.xls"');
				$response->headers->set('Cache-Control', 'max-age=0');
				break;
				 
			case "ods":
				$response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.spreadsheet');
				$response->headers->set('Content-Disposition', 'attachment;filename="'.$this->title.'.ods"');
				// If you're serving to IE 9, then the following may be needed
				$response->headers->set('Cache-Control', 'max-age=1');
				// If you're serving to IE over SSL, then the following may be needed
				$response->headers->set ('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				$response->headers->set ('Last-Modified', gmdate('D, d M Y H:i:s').' GMT'); // always modified
				$response->headers->set ('Cache-Control', 'cache, must-revalidate'); // HTTP/1.1
				$response->headers->set ('Pragma', 'public'); // HTTP/1.0
				break;
				 
			case "csv":
				// Les headers
				$response->headers->set('Content-Encoding', 'UTF-8');
				$response->headers->set('Content-type', 'text/csv; charset=UTF-8');
				$response->headers->set('Content-Disposition', 'attachment; filename="'.$this->title.'.csv"');
				$response->headers->set('Cache-Control', 'max-age=0');
				break;
				 
			case "xlsx":
			default:
				// Les headers
				$response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				$response->headers->set('Content-Disposition', 'attachment;filename="'.$this->title.'.xlsx"');
				$response->headers->set('Cache-Control', 'max-age=1');
				 
				$response->headers->set('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				$response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s').' GMT'); // always modified
				$response->headers->set('Cache-Control', 'cache, must-revalidate'); // HTTP/1.1
				$response->headers->set('Pragma', 'public');
				break;
		}
		
		return $response;
	}
}
?>
