<?php

namespace Uberboom\Pdf;

/**
 * Abstract PDF
 */
abstract class AbstractPdf
{
	
	/**
	 * Full path to java.
	 *
	 * @var string
	 */
	protected $_javaPath = '/opt/jdk1.6/bin/java';


	/**
	 * Cache path.
	 *
	 * @var string
	 */
	protected $_tempPath = false;
	
	
	/**
	 * Verbose mode
	 * 
	 * @var boolean
	 */
	protected $_infoVerbose = false;


	/**
	 * Path to info log file
	 * 
	 * @var string
	 */
	protected $_infoLogFile = false;


	/**
	 * Path to error log file
	 * 
	 * @var string
	 */
	protected $_errorLogFile = false;


	/**
	 * Log java command executed
	 * 
	 * @var string
	 */
	protected $_logJavaCommand = false;


	/**
	 * Default constructur.
	 *
	 * @return	void
	 */
	public function __construct($javaPath = null, $tempPath = null)
	{
		if ($javaPath) {
			$this->setJavaPath($javaPath);
		}
		if (!is_null($tempPath)) {
			$this->setTempPath($tempPath);
		} elseif (defined('_TEMP_PATH')) {
			$this->setTempPath(_TEMP_PATH);
		}
	}


	/**
	 * Sets the full path to java vm.
	 *
	 * @param	string	$javaPath
	 *
	 * @return	void
	 */
	public function setJavaPath($javaPath)
	{
		$this->_javaPath = $javaPath;
	}


	/**
	 * Set cache path
	 * 
	 * @param	string	$tempPath   Cache path
	 * 
	 * @return  void
	 */
	public function setTempPath($tempPath)
	{
		$this->_tempPath = $tempPath;
	}

	
	/**
	 * Set info log
	 * 
	 * @param	string	$infoLogFile   Path to info log
	 * 
	 * @return  void
	 */
	public function setInfoLog($infoLogFile, $infoVerbose = false)
	{
		if ($infoLogFile) {
			$infoLogFile = strftime($infoLogFile);
		}
		$this->_infoLogFile = $infoLogFile;
		$this->_infoVerbose = $infoVerbose;
	}

	
	/**
	 * Set error log
	 * 
	 * @param	string	$errorLogFile   Path to error log
	 * 
	 * @return  void
	 */
	public function setErrorLog($errorLogFile, $logJavaCommand = false)
	{
		if ($errorLogFile) {
			$errorLogFile = strftime($errorLogFile);
		}
		$this->_errorLogFile = $errorLogFile;
		$this->_logJavaCommand = $logJavaCommand;
	}

	
}