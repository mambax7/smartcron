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

$modversion['name'] = _MI_SCRON_MD_NAME;
$modversion['version'] = 1.0;
$modversion['description'] = _MI_SCRON_MD_DESC;
$modversion['author'] = "INBOX International";
$modversion['credits'] = "The SmartFactory";
$modversion['help'] = "";
$modversion['license'] = "GNU General Public License (GPL)";
$modversion['official'] = 0;
$modversion['image'] = "images/module_logo.gif";
$modversion['dirname'] = "smartcron";

// Added by marcan for the About page in admin section
$modversion['developer_website_url'] = "http://smartfactory.ca";
$modversion['developer_website_name'] = "The SmartFactory";
$modversion['developer_email'] = "info@smartfactory.ca";
$modversion['status_version'] = "Beta 1";
$modversion['status'] = "Beta";
$modversion['date'] = "unreleased";

$modversion['people']['developers'][] = "[url=http://smartfactory.ca/userinfo.php?uid=1]marcan[/url] (Marc-André Lanciault)";
$modversion['people']['developers'][] = "[url=http://smartfactory.ca/userinfo.php?uid=112]felix[/url] (Félix Tousignant)";

$modversion['warning'] = _CO_SOBJECT_WARNING_BETA;

$modversion['author_word'] = "";

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['onInstall'] = "include/onupdate.inc.php";
$modversion['onUpdate'] = "include/onupdate.inc.php";

$modversion['tables'][0] = "smartcron_meta";
$modversion['tables'][1] = "smartcron_job";
$modversion['tables'][2] = "smartcron_iteration";

// Search
$modversion['hasSearch'] = 0;

// Menu
$modversion['hasMain'] = 1;

/*
$modversion['blocks'][1]['file'] = "new_adds.php";
$modversion['blocks'][1]['name'] = _MI_SCRON_NEW_ADDS;
$modversion['blocks'][1]['show_func'] = "new_adds_show";
$modversion['blocks'][1]['edit_func'] = "new_adds_edit";
$modversion['blocks'][1]['template'] = "smartcron_new_adds.html";

*/
global $xoopsModule;
// Templates
$i = 0;

/*$i++;
$modversion['templates'][$i]['file'] = 'smartcron_header.html';
$modversion['templates'][$i]['description'] = 'Header template of all pages';
*/

// Retreive the group user list, because the autpmatic group_multi config formtype does not include Anonymous group :-(
$member_handler =& xoops_gethandler('member');
$groups_array = $member_handler->getGroupList();
foreach($groups_array as $k=>$v) {
	$select_groups_options[$v] = $k;
}
// Config Settings (only for modules that need config settings generated automatically)
$i = 0;

$i++;
$modversion['config'][$i]['name'] = 'allowed_ip';
$modversion['config'][$i]['title'] = '_MI_SCRON_ALLOWEDIP';
$modversion['config'][$i]['description'] = '_MI_SCRON_ALLOWEDIPDSC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] =  '127.0.0.1';

$i++;
$modversion['config'][$i]['name'] = 'disable_cron';
$modversion['config'][$i]['title'] = '_MI_SCRON_DISABLE';
$modversion['config'][$i]['description'] = '_MI_SCRON_DISABLEDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] =  0;

$i++;
$modversion['config'][$i]['name'] = 'notif_enabled';
$modversion['config'][$i]['title'] = '_MI_SCRON_NOTIFON';
$modversion['config'][$i]['description'] = '_MI_SCRON_NOTIFONDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] =  1;

$i++;
$modversion['config'][$i]['name'] = 'notify_email';
$modversion['config'][$i]['title'] = '_MI_SCRON_NOTIFMAIL';
$modversion['config'][$i]['description'] = '_MI_SCRON_NOTIFMAILDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] =  '';


// Notification
$modversion['hasNotification'] = 0;
//$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
//$modversion['notification']['lookup_func'] = 'smartcron_notify_iteminfo';

?>