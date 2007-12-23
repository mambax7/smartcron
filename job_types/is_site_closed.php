<?php

/**
* $Id$
* Module: SmartClone
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/


function smartcron_is_site_closed_config() {
	$config = array();
	$config['title'] = 'Is Site Closed';
	$config['description'] = 'Sending an email to the site admin when a site is closed for an unusual amount of time';


	// max seconds a site can be closed without sending a notification to admin
	$config['max_seconds'] = 60*30;

	// When shall we notify the admin again ?
	$config['notify_every'] = 60*30;
	return $config;
}

function smartcron_is_site_closed_execute(&$iterationObj) {

	global $xoopsConfig;

	if (!$xoopsConfig['closesite']) {
		$iterationObj->addToLog('Site is open. No problem.');
		$iterationObj->_jobObj->setVar('status', _SMARTCRON_STATUS_CONTINUE);
		return false;
	} else {
		// the site is closed
		if ($iterationObj->_jobObj->getVar('status', 'e') == _SMARTCRON_STATUS_NEVER_EXECUTED) {
			$iterationObj->addToLog('Detecting site is closed for the first time');
			// then it is the first time we detect that the site is closed
			$data['close_time'] = time();

		} else {
			// it is not the first time we detect the site is closed
			//s let get the time when we first detected it
			$data = $iterationObj->_jobObj->getVar('data');
			$closed_time = $data['close_time'];
			$max_seconds = $iterationObj->config['max_seconds'];
			$iterationObj->addToLog('Max seconds a site can be closed is ' . $max_seconds);
			$elapsed = time() - $closed_time;
			$iterationObj->addToLog('The site was closed at ' . formattimestamp($closed_time));
			$iterationObj->addToLog('Now, it is ' . formattimestamp(time()));
			$iterationObj->addToLog('Currently, ' . $elapsed . ' seconds passed since the closing of the site');

			if ($elapsed > $max_seconds) {
				// the maximum time has been reached, let's notify the site admin
				$xoopsMailer =& getMailer();
				//$xoopsMailer->multimailer->IsHTML(true);
				$xoopsMailer->useMail();
				$xoopsMailer->setBody('The site ' . $xoopsConfig['sitename'] . ' has been closed for more then ' . $elapsed . ' seconds. Please advise.');
				$xoopsMailer->setToEmails('marcan@smartfactory.ca');
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject($xoopsConfig['sitename'] . ' was closed for too long !');
				$xoopsMailer->send();
				$iterationObj->addToLog('Site was closed for too long, notifying admin');

				// When shall we notify the admin again ?
				$notify_every = $iterationObj->config['notify_every'];
				$next_time = time() + $notify_every;
				$iterationObj->_jobObj->setVar('next_time', $next_time);
				$iterationObj->addToLog('Setting next time to be executed : ' . $iterationObj->_jobObj->getVar('next_time'));
			}
		}
	}

	$iterationObj->_jobObj->setVar('status', _SMARTCRON_STATUS_CONTINUE);
	$iterationObj->_jobObj->setVar('data', $data);
}

?>