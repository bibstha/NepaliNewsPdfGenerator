<?php
require_once('../src/bootstrap.php');

$obj = new NNPG_Generator();
$obj->generate('Kantipur', date('Y-m-d'));
