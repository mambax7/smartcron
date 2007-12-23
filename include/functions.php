<?php

/**
* $Id$
* Module: SmartCron
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

function smartcron_div_output_error($msg, $exit=false)
{
	echo "<div style='padding-left: 5px; padding-bottom: 2px; color: red; font-weight: bold'>$msg</div>";
	if ($exit) {
		die();
	}
}

function smartcron_div_output_success($msg, $exit=false)
{
	echo "<div style='padding-left: 5px; padding-bottom: 2px; color: green; font-weight: bold'>$msg</div>";
	if ($exit) {
		die();
	}
}

function smartcron_exit_job($notify_admin=false) {
	global $xoopsConfig, $smartcronConfig;

	if ($notify_admin || (isset($smartcronConfig['notif_enabled']) && $smartcronConfig['notif_enabled'])) {

		$toemail = isset($smartcronConfig['notify_email']) ? $smartcronConfig['notify_email'] : $xoopsConfig['adminmail'];

		$output = ob_get_flush();
		$xoopsMailer =& getMailer();
		$xoopsMailer->multimailer->IsHTML(true);
		$xoopsMailer->useMail();
		$xoopsMailer->setBody($output);
		$xoopsMailer->setToEmails($toemail);
		$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
		$xoopsMailer->setFromName($xoopsConfig['sitename']);
		$xoopsMailer->setSubject('Cron Job Results');
		$xoopsMailer->send();
	}
	die;
}

?>