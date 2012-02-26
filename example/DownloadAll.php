<?php
require_once('../src/bootstrap.php');

// KTMPost
$obj = new NNPG_Generator();
$obj->generate('KTMPost', date('Y-m-d'));

// Kantipur
$obj = new NNPG_Generator();
$obj->generate('Kantipur', date('Y-m-d'));

// Republica
$obj = new NNPG_Generator();
$obj->generate('Republica', date('Y-m-d'));

// Nagarik
$obj = new NNPG_Generator();
$obj->generate('Nagarik', date('Y-m-d'));

// THT
// Nagarik
$obj = new NNPG_Generator();
$obj->generate('THT', date('Y-m-d'));