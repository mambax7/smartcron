<?php
$module_handler =& xoops_gethandler('module');
$smartcronModule =& $module_handler->getByDirname('smartcron');
if ($smartcronModule && $smartcronModule->getVar('isactive')) {
	$http = ((strpos(XOOPS_URL, "https://")) === false) ? ("http://") : ("https://");
	$phpself = $_SERVER['PHP_SELF'];
	$httphost = $_SERVER['HTTP_HOST'];
	$querystring = $_SERVER['QUERY_STRING'];
	$currenturl = $http . $httphost . $phpself;
	if ($currenturl == XOOPS_URL . '/modules/smartcron/_execute.php') {
		$xoopsConfig['closesite'] = 0;
	}
}
?>