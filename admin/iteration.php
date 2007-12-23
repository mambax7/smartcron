<?php

/**
* $Id$
* Module: SmartQuiz
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$iterationid = isset($_GET['iterationid']) ? intval($_GET['iterationid']) : 0 ;
$parentid = isset($_GET['iterationid']) ? intval($_GET['iterationid']) : 0 ;

switch ($op) {

	case "view" :

		$iterationObj = $smartcron_iteration_handler->get($iterationid);
		smart_xoops_cp_header();

		smart_adminMenu(1, _AM_SCRON_ITERATION_VIEW . ' > Iteration ' . $iterationObj->getVar('iterationid'));

		smart_collapsableBar('iterationview', 'Iteration ' . $iterationObj->getVar('iterationid'), _AM_SCRON_ITERATION_VIEW_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjectsingleview.php";
		$singleview = new SmartObjectSingleView($iterationObj);
		$singleview->addRow(new SmartObjectRow('iterationid'));
		$singleview->addRow(new SmartObjectRow('jobid', 'getJobLink'));
		$singleview->addRow(new SmartObjectRow('start_date'));
		$singleview->addRow(new SmartObjectRow('end_date'));
		$singleview->addRow(new SmartObjectRow('log'));
		$singleview->render($fetchOnly);

		echo "<br />";
		smart_close_collapsable('iterationview');
		echo "<br>";

		break;

	default:

		smart_xoops_cp_header();

		smart_adminMenu(1, _AM_SCRON_ITERATIONS);

		smart_collapsableBar('createditerations', _AM_SCRON_ITERATIONS, _AM_SCRON_ITERATIONS_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartcron_iteration_handler);
		$objectTable->setTableId('createditerations');
		$objectTable->addColumn(new SmartObjectColumn('start_date', 'center', 175, 'getStart_dateLink'));
		$objectTable->addColumn(new SmartObjectColumn('end_date', 'center', 175));
		$objectTable->addColumn(new SmartObjectColumn('log', 'left', false, 'getSummaryLog'));
		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createditerations');
		echo "<br>";

		break;
}

smart_modFooter();
xoops_cp_footer();

?>