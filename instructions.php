<?php
namespace Stanford\TokenRelay;
/** @var \Stanford\EmailRelay\TokenRelay $module */


require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

?>

<h3>Token Relay API Instructions</h3>
    <p>
    This module allows you to create a stand alone API url that can be used for assigning API Tokens to other REDcap projects
    </p>
<br>

<h4>Endpoint</h4>
<p>
    You must send a 'POST' request to the following url to aquire a Token:
</p>
<pre>
<?php echo $module->getProjectUrl($project_id) ?>
</pre>
<br>
<h4>Example</h4>
<p>
The following parameters are valid in the body of the POST
</p>
<pre>
    participant_id: Pregenerated
    passcode:       # Code
</pre>
<br>

