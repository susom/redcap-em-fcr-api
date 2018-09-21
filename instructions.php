<?php
namespace Stanford\FCRApi;
/** @var \Stanford\EmailRelay\FCRApi $module */


require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';
$participant_form = $module->getUrl("ParticipantInfo.zip");
$sessions_form = $module->getUrl("SessionData.zip");
$XML_PROJECT_TEMPLATE = $module->getUrl("FCR_PROJECT_AppData.REDCap.xml");
?>

<h3>These are user instructions for the FCR API app</h3>
<p>This is the API URL
<pre>
    <?php echo $module->getProjectUrl($project_id) ?>
</pre>
</p>
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
<p>
    Or use the following XML to create a project with all the instruments and settings set up already
</p>
<div>
    <?php echo "<a href='$XML_PROJECT_TEMPLATE'>FCR Redcap Project Template</a>" ?>
</div>

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
