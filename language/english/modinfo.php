<?php

/**
* $Id$
* Module: SmartCron
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

/**  not edited by RJB on 3/10/07 */

if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

// Module Info
// The name of this module

global $xoopsModule;
define("_MI_SCRON_MD_NAME", "SmartCron");
define("_MI_SCRON_MD_DESC", "Managing cron jobs on your XOOPS site");

define("_MI_SCRON_JOBS", "Cron jobs");
define("_MI_SCRON_ITERATIONS", "Iterations");
define("_MI_SCRON_DOC", "Documentation");

define("_MI_SCRON_EXECUTE", "Execute cron");

define("_MI_SCRON_ALLOWEDIP", "IPs allowed to execute the cron jobs");
define("_MI_SCRON_ALLOWEDIPDSC", "Seperate multiple Ips with a |");

define("_MI_SCRON_NOTIFMAIL", "Notification email");
define("_MI_SCRON_NOTIFMAILDSC", "Email address where notification about iterations will be sent. If empty, then the admin mail will be used.");

define("_MI_SCRON_NOTIFON", "Notification enabled ?");
define("_MI_SCRON_NOTIFONDSC", "If set to yes then a notification will be sent whenever an iteration is conducted. Usually for debugging purposes...");

define("_MI_SCRON_DISABLE", "Disable all jobs");
define("_MI_SCRON_DISABLEDSC", "Set to Yes to stop the execution of all jobs.");

?>