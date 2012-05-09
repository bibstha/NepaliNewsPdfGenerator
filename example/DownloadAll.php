<?php
require_once('../src/bootstrap.php');
$params = getopt(null, array("date::"));

$date = date('Y-m-d');
if (isset($params['date']) && $params['date'] ) {
	$date = $params['date'];
}

// KTMPost
$obj = new NNPG_Generator();
$obj->generate('KTMPost', $date);

// Kantipur
$obj = new NNPG_Generator();
$obj->generate('Kantipur', $date);

// Republica
$obj = new NNPG_Generator();
$obj->generate('Republica', $date);

// Nagarik
$obj = new NNPG_Generator();
$obj->generate('Nagarik', $date);

// THT
// Nagarik
$obj = new NNPG_Generator();
$obj->generate('THT', $date);