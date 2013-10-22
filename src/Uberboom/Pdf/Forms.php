<?php

namespace Uberboom\Pdf;

/**
 * PDF form filler
 */
class Forms extends AbstractPdf
{
	/**
	 * Argument parser exception
	 * @const int
	 */
	const ERROR_ARG_PARSER = 1;

	/**
	 * Argument “--template” missing exception
	 * @const int
	 */
	const ERROR_ARG_TEMPLATE = 2;

	/**
	 * Argument “--target” missing exception
	 * @const int
	 */
	const ERROR_ARG_TARGET = 3;

	/**
	 * Argument “--xml” missing exception
	 * @const int
	 */
	const ERROR_ARG_XML = 4;

	/**
	 * PDF IO Exception
	 * @const int
	 */
	const ERROR_PDF_IO = 31;

	/**
	 * PDF Other Exception
	 * @const int
	 */
	const ERROR_PDF_OTHER = 32;

	/**
	 * XML Parser Exception
	 * @const int
	 */
	const ERROR_XML_PARSER = 21;

	/**
	 * PDF IO Exception while reading PDF fields (only in verbose mode)
	 * @const int
	 */
	const ERROR_PDF_FIELDS_IO = 41;

	/**
	 * PDF Other Exception while reading PDF fields (only in verbose mode)
	 * @const int
	 */
	const ERROR_PDF_FIELDS_OTHER = 42;
	
	/**
	 * Name of JAR file
	 * @const string
	 */
	const JAR = 'bin/PdfForms_1.0-jar-with-dependencies.jar';


	/**
	 * SimpleXML element holding the form values
	 * @var SimpleXMLElement
	 */
	protected $_xmlObj;


	/**
	 * Default constructur.
	 *
	 * @return	void
	 */
	public function __construct($javaPath = null, $tempPath = null)
	{
		parent::__construct($javaPath, $tempPath);

		$xmlBaseDoc = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<fields></fields>
XML;
		$this->_xmlObj = new \SimpleXMLElement($xmlBaseDoc);

	}


	/**
	 * Fill pdf form fields
	 * 
	 * @return boolean
	 */
	public function fillFormFields($pdfTemplate, $pdfTarget, array $config = array())
	{
		if (!$this->_tempPath) {
			throw new Exception('PDF Cache path not set');
		}
		
		if (!file_exists($this->_javaPath)) {
			throw new Exception('Java executable not found');
		}

		$sysTempDir = sys_get_temp_dir();

		// todo write temporary xml data file
		$tempFileXml = tempnam($sysTempDir, __CLASS__ . '_xml');
		$xml = $this->_xmlObj->asXML();
		// header('content-type: text/xml; charset=utf8');
		// echo $xml; die();
		file_put_contents($tempFileXml, $xml);

		// Create shellcommand
		$vendorPath = realpath(dirname(__FILE__) . '/' . self::JAR);
		$command = $this->_javaPath . ' -Xmx256m -jar ' . $vendorPath;
		$command .= ' --template ' . escapeshellarg($pdfTemplate) . '';
		$command .= ' --xml ' . escapeshellarg($tempFileXml) . '';
		$command .= ' --target ' . escapeshellarg($pdfTarget) . '';
		if (isset($config['fonts'])) {
			$command .= ' --fonts ' . escapeshellarg($config['fonts']);
		}
		if ($this->_infoLogFile) {
			if ($this->_infoVerbose) {
				$command .= ' --verbose';
			}
			$command .= ' > ' . escapeshellarg($this->_infoLogFile);
		}
		if (!empty($this->_errorLogFile)) {
			$command .= ' 2>>' . escapeshellarg($this->_errorLogFile);
		}

		// log java command executed
		if ($this->_logJavaCommand && $this->_errorLogFile) {
			error_log($command . "\n", 3, $this->_errorLogFile);
		}
		
		// log java command executed
		if ($this->_infoLogFile) {
			error_log($command . "\n", 3, $this->_infoLogFile);
		}
		
		// Execute command
		$output = null;
		$returnVar = null;
		exec($command, $output, $returnVar);
		if ($returnVar !== 0) {
			throw new \Exception('Execution of PdfForms failed with return code ' . $returnVar);
		}
		
		return file_exists($pdfTarget);

	}

	
	/**
	 * Set field value
	 *
	 * @param string $key       Field name
	 * @param string $value     Value
	 * @param string $readonly  True if field should be set to read-only
	 * 
	 * @return void
	 */
	public function setFieldValue($key, $value, $readonly = false)
	{
		$fieldNode = $this->_xmlObj->addChild('field');
		$fieldNode->key = $key;
		$fieldNode->value = $value;
		$fieldNode->type = 'field';
		$fieldNode->readonly = ($readonly) ? 'true' : 'false';
	}
	
	
	/**
	 * Add text
	 *
	 * @param string $value     Text
	 * @param string $fontSize  Font size
	 * @param string $x         Position X
	 * @param string $y         Position Y
	 * @param string $font      Font name
	 * 
	 * @return void
	 */
	public function addText($value, $fontSize, $x, $y, $font = null)
	{
		$fieldNode = $this->_xmlObj->addChild('field');
		$fieldNode->key = null;
		$fieldNode->value = $value;
		$fieldNode->type = 'text';
		$fieldNode->readonly = 'false';

		$configNode = $fieldNode->addChild('config');
		$configNode->addChild('size', $fontSize);
		$configNode->addChild('x', $x);
		$configNode->addChild('y', $y);
		if (!is_null($font)) {
			$configNode->addChild('font', $font);
		}
	}

	
}