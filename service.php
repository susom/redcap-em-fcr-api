<?php
namespace Stanford\TokenRelay;
/** @var \Stanford\TokenRelay\TokenRelay $module */

echo $module::emLog($_REQUEST, "Incoming Request");
$result = $module->sendEmail();
header("Content-type: application/json");
echo json_encode($result);