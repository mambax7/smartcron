<?php

/**
* $Id$
* Module: SmartClone
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/


function smartcron_smartmaillight_config() {
	$config = array();
	$config['title'] = 'SmartMailLight Send Messages';
	$config['description'] = 'Sending the messages that need to be sent in by SmartMailLight';
	$config['show_fields'] = array(
								'threshold',
								);

	return $config;
}

function smartcron_smartmaillight_execute(&$iterationObj) {

	include_once(XOOPS_ROOT_PATH . '/modules/smartmaillight/class/mailer.php');

	$smartmaillight_mailer = new SmartmaillightMailer();
	$logs=array();

	$smartmaillight_mailer->execute();
	foreach($smartmaillight_mailer->getLogs() as $log) {
		$iterationObj->addToLog($log);
	}

	$data = $iterationObj->_jobObj->getVar('data');

	$iterationObj->_jobObj->setVar('status', _SMARTCRON_STATUS_CONTINUE);
	$iterationObj->_jobObj->setVar('data', $data);
}

?>