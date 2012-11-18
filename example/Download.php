<?php
require_once('../src/bootstrap.php');
$papers = array(
	'ktmpost' => 'KTMPost',
	'kantipur' => 'Kantipur',
	'republica' => 'Republica',
	'nagarik' => 'Nagarik',
	'tht' => 'THT',
);

$paramsKeys = array_merge( array("date::"), array_keys($papers));
$paramsVals = getopt(null, $paramsKeys);

$date = date('Y-m-d');
if (isset($paramsVals['date']) && $paramsVals['date'] ) {
	$date = $paramsVals['date'];
}

$papersToDownload = array_intersect_key($papers, $paramsVals);
if (empty($papersToDownload)) {
	print <<<EOF
No papers specified for download. Please enter atleast one paper from the following list.

Usage: php Download.php [OPTIONS]

OPTIONS:
--ktmpost   for Kathmandu post
--kantipur  for Kantipur
--republica for Republica
--nagarik   for Nagarik
--tht       for TheHimalayanTimes

--date=Y-m-d for specific date download 



EOF;
}

foreach ($papersToDownload as $paper) {
	$obj = new NNPG_Generator();
	$obj->generate($paper, $date);
}
