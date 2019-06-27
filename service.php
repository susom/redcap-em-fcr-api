<?php
namespace Stanford\FCRApi;
/** @var \Stanford\FCRApi\FCRApi $module */


echo $module->emLog($_REQUEST, "Incoming Request");


if (! $module->parseInput()) {
    $module->returnError("Invalid Request Parameters - check your syntax");
}

// Response is handled by $module
$module->doAction();
