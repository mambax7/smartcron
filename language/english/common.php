<?php

/**
* $Id$
* Module: SmartCron
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

/**  Text edited by RJB on 3/10/07 */

if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

// job.php
define("_CO_SCRON_JOB_NAME", "Name");
define("_CO_SCRON_JOB_NAME_DSC", "");
define("_CO_SCRON_JOB_DESCRIPTION", "Description");
define("_CO_SCRON_JOB_DESCRIPTION_DSC", "");
define("_CO_SCRON_JOB_LOG", "Log");
define("_CO_SCRON_JOB_LOG_DSC", "");
define("_CO_SCRON_JOB_STATUS", "Status");
define("_CO_SCRON_JOB_STATUS_DSC", "");
define("_CO_SCRON_JOB_TYPE", "Type");
define("_CO_SCRON_JOB_TYPE_DSC", "");
define("_CO_SCRON_JOB_THRESHOLD", "Threshold");
define("_CO_SCRON_JOB_THRESHOLD_DSC", "");
define("_CO_SCRON_JOB_BREAKPOINT", "Breakpoint");
define("_CO_SCRON_JOB_BREAKPOINT_DSC", "");
define("_CO_SCRON_JOB_FILE", "File");
define("_CO_SCRON_JOB_FILE_DSC", "");
define("_CO_SCRON_JOB_NEXT_TIME", "Next execute time");
define("_CO_SCRON_JOB_NEXT_TIME_DSC", "");
define("_CO_SCRON_JOB_INTERVAL", "Interval (In seconds)");
define("_CO_SCRON_JOB_INTERVAL_DSC", "60 = 1 minute<br />300 = 5 minutes<br />600 = 10 minutes<br />1 800 = 30 minutes<br />3 600 = 1 hour<br />86 400 = 1 day");


define("_CO_SCRON_STATUS_NEVER_EXECUTED", "Never executed");
define("_CO_SCRON_STATUS_CONTINUE", "To be continued");
define("_CO_SCRON_STATUS_DONE", "Done");
define("_CO_SCRON_STATUS_OFFLINE", "Offline");

define("_CO_SCRON_ITERATION_ID", "Iteration ID");
define("_CO_SCRON_ITERATION_JOBID", "Job");
define("_CO_SCRON_ITERATION_JOBID_DSC", "");
define("_CO_SCRON_ITERATION_START_DATE", "Start timestamp");
define("_CO_SCRON_ITERATION_START_DATE_DSC", "");
define("_CO_SCRON_ITERATION_END_DATE", "End timestamp");
define("_CO_SCRON_ITERATION_END_DATE_DSC", "");
define("_CO_SCRON_ITERATION_LOG", "Logs");
define("_CO_SCRON_ITERATION_LOG_DSC", "");

?>