<?php

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";
include_once(SMARTCRON_ROOT_PATH . 'class/job_type.php');

define('_SMARTCRON_STATUS_OFFLINE', 1);
define('_SMARTCRON_STATUS_DONE', 2);
define('_SMARTCRON_STATUS_NEVER_EXECUTED', 10);
define('_SMARTCRON_STATUS_CONTINUE', 11);

class SmartcronJob extends SmartObject {

    function SmartcronJob() {
        $this->quickInitVar('jobid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true, _CO_SCRON_JOB_NAME, _CO_SCRON_JOB_NAME_DSC);
        $this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, false, _CO_SCRON_JOB_DESCRIPTION, _CO_SCRON_JOB_DESCRIPTION_DSC);
		$this->quickInitVar('status', XOBJ_DTYPE_INT, true, _CO_SCRON_JOB_STATUS, _CO_SCRON_JOB_STATUS_DSC, _SMARTCRON_STATUS_NEVER_EXECUTED);
		$this->quickInitVar('job_type', XOBJ_DTYPE_TXTBOX, true, _CO_SCRON_JOB_TYPE, _CO_SCRON_JOB_TYPE_DSC);
		$this->quickInitVar('threshold', XOBJ_DTYPE_INT, false, _CO_SCRON_JOB_THRESHOLD, _CO_SCRON_JOB_THRESHOLD_DSC, 100);
		$this->quickInitVar('breakpoint', XOBJ_DTYPE_INT, false, _CO_SCRON_JOB_BREAKPOINT, _CO_SCRON_JOB_BREAKPOINT_DSC, 0);
		$this->quickInitVar('file', XOBJ_DTYPE_TXTBOX, false, _CO_SCRON_JOB_FILE, _CO_SCRON_JOB_FILE_DSC);
		$this->quickInitVar('execute_interval', XOBJ_DTYPE_INT, false, _CO_SCRON_JOB_INTERVAL, _CO_SCRON_JOB_INTERVAL_DSC, 30);
		$this->quickInitVar('next_time', XOBJ_DTYPE_LTIME, false, _CO_SCRON_JOB_NEXT_TIME, _CO_SCRON_JOB_NEXT_TIME_DSC);
        $this->quickInitVar('data', XOBJ_DTYPE_ARRAY, false);
		$this->initCommonVar('weight');

		$this->setControl('job_type', array('itemHandler' => 'job',
                                          'method' => 'getJob_types',
                                          'module' => 'smartcron',
                                          'onSelect' => 'submit'));

		$this->setControl('status', array('itemHandler' => 'job',
                                          'method' => 'getStatus',
                                          'module' => 'smartcron'));
		$this->setControl('file', 'file');

    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('status', 'job_type'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

    function getOnlyTitle() {
    	$ret = $this->getVar('title', 'e');
    	return $ret;
    }

    function status() {
    	$smartcron_job_handler = xoops_getModuleHandler('job', 'smartcron');

    	$ret = $this->getVar('status', 'e');
    	$status_array = $smartcron_job_handler->getStatus();
    	if (isset($status_array[$ret])) {
    		return $status_array[$ret];
    	} else {
    		return $ret;
    	}
    }

    function job_type() {
    	$smartcron_job_handler = xoops_getModuleHandler('job', 'smartcron');

    	$ret = $this->getVar('job_type', 'e');
    	$job_types_array = $smartcron_job_handler->getJob_types();
    	if (isset($job_types_array[$ret])) {
    		return $job_types_array[$ret];
    	} else {
    		return $ret;
    	}
    }

    function execute() {
    	$smartcron_iteration_handler = xoops_getModuleHandler('iteration', 'smartcron');
    	$iterationObj = $smartcron_iteration_handler->create();
    	$iterationObj->setVar('start_date', time());
    	$iterationObj->setVar('jobid', $this->id());
    	$ret = $iterationObj->execute($this);
    	$iterationObj->setVar('end_date', time());
    	if (!$smartcron_iteration_handler->insert($iterationObj, true)) {
    			foreach($iterationObj->getErrors() as $error) {
    				smartcron_div_output_error($error);
    			}
    			foreach($iterationObj_jobObj->getErrors() as $error) {
    				smartcron_div_output_error($error);
    			}
    		return false;
    	} else {
			return $iterationObj;
    	}
    }
}
class SmartcronJobHandler extends SmartPersistableObjectHandler {

	var $_job_types = false;
	var $_statusArray = false;

    function SmartcronJobHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'job', 'jobid', 'title', 'description', 'smartcron');
		$mimetypes= array(
						'text/comma-separated-values',
						'text/csv',
						'application/csv',
						'application/excel',
						'application/vnd.ms-excel',
						'application/vnd.msexcel',
						'text/anytext',
						'application/x-csv'
						);
		$this->setUploaderConfig(false, $mimetypes, 300000, false, false);
    }

    function beforeSave(&$obj) {
		$error = false;
		if (isset($_POST['smart_upload_file'])) {
		    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartuploader.php";
			$uploaderObj = new SmartUploader($this->getImagePath(true), $this->_allowedMimeTypes, $this->_maxFileSize, $this->_maxWidth, $this->_maxHeight);
			foreach ($_FILES as $name=>$file_array) {
				if (isset ($file_array['name']) && $file_array['name'] != "" ) {
					if ($uploaderObj->fetchMedia($name)) {
						$uploaderObj->setTargetFileName(time()."_+_". $uploaderObj->getMediaName());
						if ($uploaderObj->upload()) {
							// Find the related field in the SmartObject
							$related_field = 'file';
							$obj->setVar($related_field, $uploaderObj->getSavedFileName());
						} else {
							$error = true;
							$obj->setErrors($uploaderObj->getErrors(false));
						}
					} else {
						$error = true;
						$obj->setErrors($uploaderObj->getErrors(false));
					}
				}
			}
		}
		if ($error) {
			return false;
		} else {
			return true;
		}
    }

    function getJob_types() {
		if (!$this->_job_types) {
			$smartcron_job_type_handler = new SmartcronJob_typeHandler();
			$job_typesArray = $smartcron_job_type_handler->getJob_typessArray();
			$this->_job_types = array(0=>'------');
			foreach($job_typesArray as $k=>$v) {
				$this->_job_types[$k]=$v;
			}
		}
		return $this->_job_types;
    }

    function getLastUpdatedDateForJobType($job_type) {
    	$sql = "SELECT MAX( iteration.end_date ) as lastupdatedate
				FROM " . $this->db->prefix('smartcron_job') . " AS job JOIN " . $this->db->prefix('smartcron_iteration') . " AS iteration
				ON job.jobid=iteration.jobid
				WHERE job.job_type = '" . $job_type . "'
				GROUP BY iteration.jobid";
    	$ret = $this->query($sql);
    	if (isset($ret[0]['lastupdatedate'])) {
    		return $ret[0]['lastupdatedate'];
    	} else {
    		return false;
    	}
	}

    function getStatus() {
    	if (!$this->_statusArray) {
			$this->_statusArray[_SMARTCRON_STATUS_NEVER_EXECUTED] = _CO_SCRON_STATUS_NEVER_EXECUTED;
			$this->_statusArray[_SMARTCRON_STATUS_CONTINUE] = _CO_SCRON_STATUS_CONTINUE;
			$this->_statusArray[_SMARTCRON_STATUS_DONE] = _CO_SCRON_STATUS_DONE;
			$this->_statusArray[_SMARTCRON_STATUS_OFFLINE] = _CO_SCRON_STATUS_OFFLINE;
    	}
		return $this->_statusArray;
    }

    function getOutstandingJobs() {
    	$criteria = new CriteriaCompo();
    	$criteria->add(new Criteria('status', _SMARTCRON_STATUS_NEVER_EXECUTED, '>='));
    	$criteria->add(new Criteria('next_time', time(), '<'));
    	$criteria->setSort('weight, jobid');
    	$ret = $this->getObjects($criteria);
    	if ($ret && count($ret) > 0) {
    		return $ret;
    	} else {
    		return false;
    	}
    }
}
?>