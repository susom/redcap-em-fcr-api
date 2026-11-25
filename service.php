<?php
namespace Stanford\FCRApi;
/** @var \Stanford\FCRApi\FCRApi $module */

// Do not log $_REQUEST: it holds the participant id, passcode and submitted data.
$module->emDebug("Incoming Request", "action: " . strtoupper(trim((string) ($_POST['action'] ?? ''))));

if (! $module->parseInput()) {
    $module->returnError("Invalid Request Parameters - check your syntax");
}

// Response is handled by $module
$module->doAction();
