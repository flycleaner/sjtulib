<?php 
require 'config.inc.php';
$date = date('Y-m-d');
var_dump(qgzx_Job::getJobOfDate($date));
$jobs = qgzx_Job::getSignedWorkOfDate($date);
var_dump($jobs);

?>