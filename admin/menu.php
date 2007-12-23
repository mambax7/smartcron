<?php
/**
* $Id$
* Module: SmartCron
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

$i = -1;

$i++;
$adminmenu[$i]['title'] = _MI_SCRON_JOBS;
$adminmenu[$i]['link'] = "admin/index.php";

$i++;
$adminmenu[$i]['title'] = _MI_SCRON_ITERATIONS;
$adminmenu[$i]['link'] = "admin/iteration.php";

$i++;
$adminmenu[$i]['title'] = _MI_SCRON_DOC;
$adminmenu[$i]['link'] = "admin/doc.php";

if (isset($xoopsModule)) {

	$i = -1;

	$i++;
	$headermenu[$i]['title'] = _PREFERENCES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');

	$i++;
	$headermenu[$i]['title'] = _MI_SCRON_EXECUTE;
	$headermenu[$i]['link'] = SMARTCRON_URL . '_execute.php';

	$i++;
	$headermenu[$i]['title'] = _CO_SOBJECT_UPDATE_MODULE;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');

	$i++;
	$headermenu[$i]['title'] = _AM_SOBJECT_ABOUT;
	$headermenu[$i]['link'] = SMARTCRON_URL . "admin/about.php";
}
?>