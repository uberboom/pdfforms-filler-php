<?php

	require_once '../vendor/autoload.php';

	$javaPath = '/usr/bin/java';
	$tempPath = '/tmp';
	$pdfForms = new Uberboom\Pdf\Forms($javaPath, $tempPath);

	$infoLogFile = $tempPath . '/pdf_forms_info.log';
	$pdfForms->setInfoLog($infoLogFile, true);

	$errorLogFile = $tempPath . '/pdf_forms_error.log';
	$logJavaCommand = true;
	$pdfForms->setErrorLog($errorLogFile, $logJavaCommand);

	$pdfTemplate = realpath(dirname(__FILE__)) . '/formtest.pdf';
	$pdfTarget   = realpath(dirname(__FILE__)) . '/formtest.output.pdf';

	// set field values
	$pdfForms->setFieldValue('ASSOCIATES DEGREE', 'On');
	$pdfForms->setFieldValue('Address_1', 'Address Line 1', true);
	$pdfForms->setFieldValue('Address_2', 'Address Line 2');
	$pdfForms->setFieldValue('BACHELORS DEGREE', 'On');
	$pdfForms->setFieldValue('Birthdate', '09/27/1977');
	$pdfForms->setFieldValue('COLLEGE NO DEGREE', 'On');
	$pdfForms->setFieldValue('City', 'City');
	$pdfForms->setFieldValue('Emergency_Contact', 'Em-Contact');
	$pdfForms->setFieldValue('Emergency_Phone', 'Em-Phone');
	$pdfForms->setFieldValue('Emergency_Relationship', 'Em-Relation');
	$pdfForms->setFieldValue('HIGH SCHOOL DIPLOMA', 'On');
	$pdfForms->setFieldValue('MASTERS DEGREE', 'On');
	$pdfForms->setFieldValue('Name_First', 'Firstname');
	$pdfForms->setFieldValue('Name_Last', 'Lastname');
	$pdfForms->setFieldValue('Name_Middle', 'M.');
	$pdfForms->setFieldValue('Name_Prefix', 'Prefix');
	$pdfForms->setFieldValue('Name_Suffix', 'Suffix');
	$pdfForms->setFieldValue('OTHER DOCTORATE', 'On');
	$pdfForms->setFieldValue('PHD', 'On');
	$pdfForms->setFieldValue('PROFESSIONAL DEGREE', 'On');
	$pdfForms->setFieldValue('SSN', 'SSN');
	$pdfForms->setFieldValue('STATE', 'State');
	$pdfForms->setFieldValue('Sex', 'FEMALE');
	$pdfForms->setFieldValue('TRADE CERTIFICATE', 'On');
	$pdfForms->setFieldValue('Telephone_Home', '(0)-HOME');
	$pdfForms->setFieldValue('Telephone_Work', '(0)-WORK');
	$pdfForms->setFieldValue('ZIP', '1234ZIP');
	
	// add sample text
	$pdfForms->addText('ABCDEF', 50, 57, 780, 'code_128.ttf');
	
	// configuration
	$config = array(
		'fonts' => realpath(dirname(__FILE__)),
	);

	$result = $pdfForms->fillFormFields($pdfTemplate, $pdfTarget, $config);
	
	// show generated pdf in browser
	if ($result === true) {
		header('content-type: application/pdf');
		readfile($pdfTarget);
		// unlink($pdfTarget);
	} else {
		var_dump($result);
	}
