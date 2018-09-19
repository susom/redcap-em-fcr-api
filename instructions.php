<?php
namespace Stanford\FCRApi;
/** @var \Stanford\EmailRelay\FCRApi $module */


require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

$participant_form = $module->getUrl("participant_info.zip");
$sessions_form = $module->getUrl("sessions.zip");

?>

<h3>These are user instructions for the FCR API app</h3>

<p>
    You can download the proper instruments to have in your project here:
</p>
<div>
    <?php echo "<a href='$participant_form'>participant_info.zip</a>" ?>
</div>
<div>
    <?php echo "<a href='$sessions_form'>sessions.zip</a>" ?>
</div>
<p>
    Ensure that sessions is configured as a repeating instrument and that the project is NOT longitudinal.
</p>



<?php
if (\REDCap::isLongitudinal()) {
    ?>
    <div class="alert alert-danger">
        This project is currently longitudinal and this plugin will not work correctly!
    </div>
    <?php
}

if (\REDCap::getRecordIdField() !== "id") {
    ?>
    <div class="alert alert-danger">
        The record id field for this project must be called 'id' - please rename it.
    </div>
    <?php
}
?>
