<?
include_once("../../mainfile.php");

include_once(XOOPS_ROOT_PATH . "/modules/smartcron/include/common.php");

ob_start();

if ($xoopsModuleConfig['disable_cron']) {
	smartcron_exit_job(false);
}

if($_SERVER['REMOTE_ADDR'] !=  $xoopsModuleConfig['allowed_ip']) {
	smartcron_div_output_error('Host not allowed : ' . $_SERVER['REMOTE_ADDR']);
	smartcron_exit_job();
}

// Check lock dir for concurrency collisions avoidance
$lock_dir = XOOPS_CACHE_PATH.'/cronjob_lockdir';

smartcron_div_output_success("Starting cron job at " . date("H:i:s", time()));


if( file_exists($lock_dir) ) {
    smartcron_div_output_error("Lock dir exists, terminating process");
	smartcron_exit_job(false);
}
if( ! mkdir($lock_dir) ) {
    smartcron_div_output_error("Could not create lock dir");
    smartcron_exit_job(true);
}

$jobsObj = $smartcron_job_handler->getOutstandingJobs();

if ($jobsObj) {
	foreach ($jobsObj as $jobObj) {
		if ($jobObj) {
			smartcron_div_output_success('A job was found to be executed');
			smartcron_div_output_success('Job title: ' . $jobObj->getVar('title'));
			smartcron_div_output_success('Job description: ' . $jobObj->getVar('description'));

			$iterationObj = $jobObj->execute();
			if (!$iterationObj) {
				smartcron_div_output_error('Errors occured while executing the job');
				foreach($jobObj->getErrors() as $error) {
					smartcron_div_output_error('-- ' . $error);
				}
			} else {
				smartcron_div_output_success('Iteration conducted successfully: ' . $iterationObj->getAdminViewItemLink());
			}
		} else {
		    smartcron_div_output_success("There is no job to execute");
		}
	}
} else {
	smartcron_div_output_success("No outstanding jobs");
}

if( ! rmdir($lock_dir) ) {
    smartcron_div_output_error("Could not remove lock dir");
	smartcron_exit_job(true);
}

smartcron_div_output_success("Done at ".date("H:i:s", time()));
smartcron_exit_job();

?>