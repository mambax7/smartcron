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

if( !defined("SMARTCRON_DIRNAME") ){
	define("SMARTCRON_DIRNAME", 'smartcron');
}

if( !defined("SMARTCRON_URL") ){
	define("SMARTCRON_URL", XOOPS_URL.'/modules/'.SMARTCRON_DIRNAME.'/');
}
if( !defined("SMARTCRON_ROOT_PATH") ){
	define("SMARTCRON_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.SMARTCRON_DIRNAME.'/');
}

if( !defined("SMARTCRON_IMAGES_URL") ){
	define("SMARTCRON_IMAGES_URL", SMARTCRON_URL.'images/');
}

if( !defined("SMARTCRON_ADMIN_URL") ){
	define("SMARTCRON_ADMIN_URL", SMARTCRON_URL.'admin/');
}

/** Include SmartObject framework **/
include_once XOOPS_ROOT_PATH.'/modules/smartobject/class/smartloader.php';

/*
 * Including the common language file of the module
 */
$fileName = SMARTCRON_ROOT_PATH . 'language/' . $GLOBALS['xoopsConfig']['language'] . '/common.php';
if (!file_exists($fileName)) {
	$fileName = SMARTCRON_ROOT_PATH . 'language/english/common.php';
}

include_once($fileName);

include_once(SMARTCRON_ROOT_PATH . "include/functions.php");

// Creating the SmartModule object
$smartcronModule =& smart_getModuleInfo(SMARTCRON_DIRNAME);

// Find if the user is admin of the module
$smartcron_isAdmin = smart_userIsAdmin(SMARTCRON_DIRNAME);

$myts = MyTextSanitizer::getInstance();
if(is_object($smartcronModule)){
	$smartcron_moduleName = $smartcronModule->getVar('name');
}

// Creating the SmartModule config Object
$smartcronConfig =& smart_getModuleConfig(SMARTCRON_DIRNAME);

include_once(SMARTCRON_ROOT_PATH . "class/job.php");
include_once(SMARTCRON_ROOT_PATH . "class/iteration.php");

$smartcron_job_handler = xoops_getmodulehandler('job', SMARTCRON_DIRNAME);
$smartcron_iteration_handler = xoops_getmodulehandler('iteration', SMARTCRON_DIRNAME);

?>