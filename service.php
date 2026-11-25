<?php
namespace Stanford\FCRApi;
/** @var \Stanford\FCRApi\FCRApi $module */

// Do not log $_REQUEST: it holds the participant id, passcode and submitted data.
// The action is caller input and is logged before authentication: log a fixed label only.
$action_label = strtoupper(trim((string) ($_POST['action'] ?? '')));
$module->emDebug("Incoming Request", "action: " . (in_array($action_label, ['VERIFY', 'SAVEDATA'], true) ? $action_label : 'other'));

if (! $module->parseInput()) {
    $module->returnError("Invalid Request Parameters - check your syntax");
}

// Response is handled by $module
$module->doAction();
