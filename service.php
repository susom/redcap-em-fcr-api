<?php
namespace Stanford\FCRApi;
/** @var \Stanford\FCRApi\FCRApi $module */


echo $module->emLog($_REQUEST, "Incoming Request");


if (! $module->parseInput()) {
    $module->returnError("Invalid Request Parameters - check your syntax");
}

$result = $module->doAction();






//header("Content-type: application/json");
//echo json_encode($result);