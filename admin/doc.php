<?php

/**
* $Id$
* Module: SmartQuiz
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

include_once(XOOPS_ROOT_PATH . '/class/template.php');
$xoopsTpl = new XoopsTpl();

smart_xoops_cp_header();

smart_adminMenu(2, _AM_SCRON_JOBS);

$templateFilename = SMARTCRON_ROOT_PATH . 'templates/smartcron_setup_cron_' . $xoopsConfig['language'] . '.html';
if (!file_exists($templateFilename)) {
	$templateFilename = SMARTCRON_ROOT_PATH . 'templates/smartcron_setup_cron_english.html';
}

$xoopsTpl->assign('cronjob', 'wget --output-document=- ' . SMARTCRON_URL . '_execute.php');
$xoopsTpl->display($templateFilename);

smart_modFooter();
xoops_cp_footer();

?>