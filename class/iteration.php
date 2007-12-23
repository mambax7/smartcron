<?php

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";

class SmartcronIteration extends SmartObject {

	var $_logs;
	var $_jobObj;
	var $config;

    function SmartcronIteration() {
        $this->quickInitVar('iterationid', XOBJ_DTYPE_INT, true, _CO_SCRON_ITERATION_ID);
        $this->quickInitVar('jobid', XOBJ_DTYPE_INT, true, _CO_SCRON_ITERATION_JOBID, _CO_SCRON_ITERATION_JOBID_DSC);
		$this->quickInitVar('start_date', XOBJ_DTYPE_LTIME, false, _CO_SCRON_ITERATION_START_DATE, _CO_SCRON_ITERATION_START_DATE_DSC);
		$this->quickInitVar('end_date', XOBJ_DTYPE_LTIME, false, _CO_SCRON_ITERATION_END_DATE, _CO_SCRON_ITERATION_END_DATE_DSC);
		$this->quickInitVar('log', XOBJ_DTYPE_TXTAREA, false, _CO_SCRON_ITERATION_LOG, _CO_SCRON_ITERATION_LOG_DSC);

		/*$this->initNonPersistableVar('status', XOBJ_DTYPE_INT);
		$this->initNonPersistableVar('breakpoint', XOBJ_DTYPE_INT);
		$this->initNonPersistableVar('threshold', XOBJ_DTYPE_INT);*/
    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('jobid', 'log'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

    function addToLog($text) {
    	$this->_logs[] = $text;
    }

    function jobid() {
    	$smartcron_job_handler = xoops_getModuleHandler('job', 'smartcron');
    	$ret = $this->getVar('jobid', 'e');
    	$jobObj = $smartcron_job_handler->get($ret);
    	if (!$jobObj->isNew()) {
    		$ret = $jobObj->getVar('title');
    	}
    	return $ret;
    }

	function log() {
		global $myts;
		$ret = $this->getVar('log', 'n');
		$itemsArray = explode('|', $ret);
		$log = '<ul>';
		foreach($itemsArray as $item) {
			$log .= '<li>' . $item . '</li>';
		}
		$log .= '</ul>';
		return $log;
	}

    function getJobLink() {
    	$ret = '<a href="' . SMARTCRON_ADMIN_URL . 'job.php?op=view&jobid=' . $this->getVar('jobid', 'e') . '">' . $this->getVar('jobid') . '</a>';
    	return $ret;
    }

    function getStart_dateLink() {
    	$ret = '<a href="' . SMARTCRON_ADMIN_URL . 'iteration.php?op=view&iterationid=' . $this->getVar('iterationid', 'e') . '">' . $this->getVar('start_date') . '</a>';
    	return $ret;
    }

    function getSummaryLog() {
    	$ret = $this->getVar('log', 'e');
    	$ret = xoops_substr($ret, 0, 100);
    	return $ret;
    }

    function execute(&$jobObj) {
    	$this->_jobObj = $jobObj;
		$job_type = $jobObj->getVar('job_type', 'e');

		// reset the next time so it can be set again when iteration is competed
		$this->_jobObj->setVar('next_time', 0);
		$job_typeObj =  new SmartcronJob_type($job_type);
		if (!$job_typeObj->exists()) {
			$this->setErrors(_MD_SCRON_NOT_EXISTS);
			return false;
		}

		$this->config = $job_typeObj->config;

		$execute_function_name = 'smartcron_' . $job_type . '_execute';
		if (function_exists($execute_function_name)) {
			 $ret = $execute_function_name($this);
			 return $ret;
		} else {
			$this->setErrors('The execute function for this job type was not found');
			return false;
		}
    }

}
class SmartcronIterationHandler extends SmartPersistableObjectHandler {

    function SmartcronIterationHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'iteration', 'iterationid', 'jobid', '', 'smartcron');
    }

    function beforeInsert(&$obj) {
		// If no bext_time is set and the job is not finised, let's set the next time
		if ($obj->_jobObj->getVar('next_time', 'e') == 0 && $obj->_jobObj->getVar('status', 'e') >= _SMARTCRON_STATUS_NEVER_EXECUTED) {
			// the let's set the date of the next execution
			$next_time = time() + $obj->_jobObj->getVar('execute_interval');
			$obj->_jobObj->setVar('next_time', $next_time);
			$obj->addToLog('Setting next time to be executed : ' . $obj->_jobObj->getVar('next_time'));
		}
    	$obj->setVar('log', implode('|', $obj->_logs));

    	return true;
    }

    function afterInsert(&$obj) {
    	// saving the jobObj as well
		$smartcron_job_handler = xoops_getModuleHandler('job', 'smartcron');
		$ret = $smartcron_job_handler->insert($obj->_jobObj, true);
    	return $ret;
    }
}
?>