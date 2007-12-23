<?php

/**
* $Id$
* Module: SmartQuiz
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editjob($showmenu = false, $jobid = 0, $parentid = 0)
{
	global $smartcron_job_handler;

	$jobObj = $smartcron_job_handler->get($jobid);

	// get the amount of jobs so far for this job and set the weight automatically

	if (!$jobObj->isNew()){

		if ($showmenu) {
			smart_adminMenu(0, _AM_SCRON_JOBS . " > " . _CO_SOBJECT_EDITING);
		}
		smart_collapsableBar('jobedit', _AM_SCRON_JOB_EDIT, _AM_SCRON_JOB_EDIT_INFO);

		$jobObj->hideFieldFromForm(array('weight', 'status', 'threshold', 'file', 'execute_interval', 'next_time', 'breakpoint'));
		$jobObj->makeFieldReadOnly('job_type');

		$job_type = $jobObj->getVar('job_type', 'e');
		$job_typeObj =  new SmartcronJob_type($job_type);
		$job_typeConfig = $job_typeObj->config;
		if (isset($job_typeConfig['show_fields'])) {
			foreach($job_typeConfig['show_fields'] as $field_to_show) {
				$jobObj->showFieldOnForm($field_to_show);
			}
		}

		$jobObj->showFieldOnForm(array('title', 'description', 'next_time', 'execute_interval'));

		$sform = $jobObj->getForm(_AM_SCRON_JOB_EDIT, 'addjob');
		$sform->display();
		smart_close_collapsable('jobedit');
	} else {
		if ($showmenu) {
			smart_adminMenu(0, _AM_SCRON_JOBS . " > " . _CO_SOBJECT_CREATINGNEW);
		}

		smart_collapsableBar('jobcreate', _AM_SCRON_JOB_CREATE, _AM_SCRON_JOB_CREATE_INFO);

		$jobObj->hideFieldFromForm(array('title', 'description', 'weight', 'status', 'threshold', 'file', 'execute_interval', 'next_time', 'breakpoint'));

		if (isset($_POST['op'])) {
			$controller = new SmartObjectController($smartcron_job_handler);
			$controller->postDataToObject($jobObj);

			if ($_POST['op'] == 'changedField') {

				switch($_POST['changedField']) {
					case 'job_type':
						$jobObj->showFieldOnForm(array('title', 'description', 'next_time', 'execute_interval'));

						$job_type = $_POST['job_type'];
						$job_typeObj =  new SmartcronJob_type($job_type);
						$job_typeConfig = $job_typeObj->config;
						if (isset($job_typeConfig['show_fields'])) {
							foreach($job_typeConfig['show_fields'] as $field_to_show) {
								$jobObj->showFieldOnForm($field_to_show);
							}
						}
					break;
				}
			}
		}

		$jobObj->setVar('jobid', $parentid);
		$sform = $jobObj->getForm(_AM_SCRON_JOB_CREATE, 'addjob');
		$sform->display();
		smart_close_collapsable('jobcreate');
	}
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$jobid = isset($_GET['jobid']) ? intval($_GET['jobid']) : 0 ;
$parentid = isset($_GET['jobid']) ? intval($_GET['jobid']) : 0 ;

switch ($op) {
	case "mod":
	case "changedField":

		smart_xoops_cp_header();

		editjob(true, $jobid, $parentid);
		break;


	case "addjob":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartcron_job_handler);
		$controller->storeFromDefaultForm(_AM_SCRON_JOB_CREATED, _AM_SCRON_JOB_MODIFIED);

		break;

	case "del":
	    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartcron_job_handler);
		$controller->handleObjectDeletion();

		break;

	case "view" :

		$jobObj = $smartcron_job_handler->get($jobid);
		smart_xoops_cp_header();

		smart_adminMenu(0, _AM_SCRON_JOB_VIEW . ' > ' . $jobObj->getVar('title'));

		smart_collapsableBar('jobview', $jobObj->getVar('title') . ' ' . $jobObj->getEditItemLink(), _AM_SCRON_JOB_VIEW_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjectsingleview.php";
		$singleview = new SmartObjectSingleView($jobObj);
		$singleview->addRow(new SmartObjectRow('title', 'getOnlyTitle'));
		$singleview->addRow(new SmartObjectRow('status'));
		$singleview->addRow(new SmartObjectRow('job_type'));
		$singleview->addRow(new SmartObjectRow('threshold'));
		$singleview->addRow(new SmartObjectRow('breakpoint'));
		$singleview->addRow(new SmartObjectRow('next_time'));
		$singleview->render($fetchOnly);

		echo "<br />";
		smart_close_collapsable('jobview');
		echo "<br>";

		smart_collapsableBar('jobiterations', _AM_SCRON_ITERATIONS, _AM_SCRON_ITERATIONS_DSC);

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('jobid', $jobid));

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartcron_iteration_handler, $criteria, array());
		$objectTable->setTableId('jobiterations');
		$objectTable->addColumn(new SmartObjectColumn('start_date', 'center', 175, 'getStart_dateLink'));
		$objectTable->addColumn(new SmartObjectColumn('end_date', 'center', 175));
		$objectTable->addColumn(new SmartObjectColumn('log', 'left', false, 'getSummaryLog'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('jobiterations');
		echo "<br>";

		break;

	default:

		smart_xoops_cp_header();

		smart_adminMenu(0, _AM_SCRON_JOBS);

		smart_collapsableBar('createdjobs', _AM_SCRON_JOBS, _AM_SCRON_JOBS_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartcron_job_handler);
		$objectTable->setTableId('createdjobs');
		$objectTable->addColumn(new SmartObjectColumn('title', 'left', false, 'getAdminViewItemLink'));
		$objectTable->addColumn(new SmartObjectColumn('status', 'left', 150));
		$objectTable->addColumn(new SmartObjectColumn('job_type', 'left', 150));
		$objectTable->addColumn(new SmartObjectColumn('next_time', 'center', 150));

		$objectTable->addIntroButton('addjob', 'job.php?op=mod', _AM_SCRON_JOB_CREATE);

		$objectTable->addQuickSearch(array('job'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdjobs');
		echo "<br>";

		break;
}

smart_modFooter();
xoops_cp_footer();

?>