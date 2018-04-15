<?php declare(strict_types=1);

/************************************
 * THIS SCRIPT RECEIVE ASYNC CALLS
 * AND PASS THE CALL TO THE 
 * CORRESPONDING JOB HANDLE METHOD
 ************************************/

require_once __DIR__ . '/bootstrap.php';

$Job = $_POST['job'];
$payload = json_decode($_POST['payload'], true);

$jobWorker = new $Job($app->getContainer());
file_put_contents(__DIR__ . '/log', $jobWorker->handle($payload));