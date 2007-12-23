<?php
class SmartcronJob_type {

	var $config = false;

	function SmartcronJob_type($job_type) {
		$job_type = strtolower($job_type);
		$job_type_file = XOOPS_ROOT_PATH . '/modules/smartcron/job_types/' . $job_type . '.php';
		if (file_exists($job_type_file)) {
			include_once($job_type_file);
			$function_name = 'smartcron_' . $job_type .  '_config';
			if (function_exists($function_name)) {
				$config = $function_name();
				$this->config = $config;
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function exists() {
		return $this->config;
	}
}

class SmartcronJob_typeHandler {

	function getJob_typessArray() {
		include_once(XOOPS_ROOT_PATH . "/class/xoopslists.php");
		$aFiles = XoopsLists::getFileListAsArray(SMARTCRON_ROOT_PATH . 'job_types/');
		$ret = array();
		foreach($aFiles as $file) {
			if (substr($file, strlen($file) - 4, 4) == '.php') {
				$job_type = str_replace('.php', '', $file);
				$job_typeObj = new SmartcronJob_type($job_type);
				$ret[$job_type] = $job_typeObj->config['title'];
			}
		}
		return $ret;
	}
}
?>