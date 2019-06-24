<?php
namespace Stanford\FCRApi;
/** @var \Stanford\FCRApi\FCRApi $module */


require APP_PATH_DOCROOT . "ControlCenter/header.php";

?>

<h3>FCR API</h3>
    <p>
        Instructions to go here?
    </p>
<br>

<h4>Endpoint</h4>
<p>
    Please configure the mobile app to use the following url:
</p>
<pre>
<?php echo $module->getUrl("service.php",true, true ) ?>
</pre>
<br>
<h4>Enabled Projects</h4>
<div>
    <?php echo $module->displayEnabledProjects() ?>
</div>

<?php


// $results = \REDCap::getData(20,'json');
// print "<code>" . print_r($results,true). "</code>";
//
//
// $data = json_encode(
//     array(
//             array(
//                 'id' => '123',
//                 'device_id' => 'c dev',
//                 'duration' => 'c dur',
//                 'start_time' => 'c start_time',
//                 'end_time' => 'c end_time',
//                 "redcap_repeat_instrument" => "sessions",
//                 "redcap_repeat_instance" => "4"
//             )
//     )
// );
//
// print $data;

//$result = REDCap::saveData($this->current_project, 'json', $this->data);
