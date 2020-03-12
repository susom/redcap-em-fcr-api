<?php
namespace Stanford\FCRApi;

use REDCap;

/**
 * Class FCRApi
 *
 * @package Stanford\FCRApi
 */
class FCRApi extends \ExternalModules\AbstractExternalModule
{
    //const TOKEN_KEY = "token_relay_api_token";
    //public $api_token;
    //private $url;
    private $enabledProjects = null;

    private $participant_id, $passcode, $action, $data;

    private $current_project;       // PID
    private $current_record;   // Array of record data

    public function __construct($project_id = null)
    {
        parent::__construct();

        // If in project context, load the object
        //global $project_id;
        //if ($project_id) {
        //    $this->emDebug("Loading for project $project_id");
        //}
        //
        //self::emDebug($this->PREFIX . " constructed $project_id");
    }

    //public function redcap_module_project_enable($version, $project_id) {
    //    if (empty($this->relay_token)) {
    //        // Generate a random token for this project
    //        $this->relay_token = generateRandomHash(10);
    //        $this->setProjectSetting(self::TOKEN_KEY, $this->relay_token, $project_id);
    //    }
    //    // self::log($this->PREFIX . " " . $version . " was enabled for project $project_id");
    //}
    //
    //public function redcap_module_project_disable($version, $project_id) {
    //    // self::log($this->PREFIX . " " . $version . " was disabled for project $project_id");
    //}


    /**
     * Parses request and sets up object
     * @return bool request valid
     */
    public function parseInput() {

        $this->emDebug("Incoming POST: ", $_POST);

        $this->participant_id   = isset($_POST['participant_id']) ? strtoupper(trim(filter_var( $_POST['participant_id'], FILTER_SANITIZE_STRING ))) : NULL ;
        $this->passcode         = isset($_POST['passcode'])       ? trim( filter_var($_POST['passcode'], FILTER_SANITIZE_STRING) ) : NULL ;
        $this->action           = isset($_POST['action'])         ? strtoupper(trim( filter_var($_POST['action'], FILTER_SANITIZE_STRING))) : NULL ;
        $this->data             = isset($_POST['data'])           ? json_decode($_POST['data'],1) : NULL ;     // JSON STRING

        $valid = (is_null($this->participant_id) || is_null($this->passcode) || is_null($this->action)) ? false : true;
        $this->emDebug($valid);

        return $valid;
    }

    /**
     * Return an error
     * @param $msg
     */
    public function returnError($msg) {
        $this->emDebug($msg);
        header("Content-type: application/json");
        echo json_encode(array("error" => $msg));
        exit();
    }


    public function doAction() {

        // Make sure user is valid and set project info
        $this->getEnabledProjects();
        if (! $this->getCurrentProject())   $this->returnError("Unable to find an active project for participant: " . $this->participant_id);
        if (! $this->activeUser())          $this->returnError("User is inactive: " . $this->participant_id);

        switch($this->action) {
            case "VERIFY":
                REDCap::logEvent($this->PREFIX, $this->action, NULL, $this->participant_id, NULL, $this->current_project);
                $result = $this->current_record;
                break;
            case "SAVEDATA":
                if (is_null($this->data)) $this->returnError("Missing data for " . $this->participant_id);
                $this->data['id']                       = $this->participant_id;
                $this->data['redcap_repeat_instrument'] = 'session_data';
                $this->data['redcap_repeat_instance']   = $this->getNextInstanceId();

                $this->emDebug("SaveData", $this->data);
                $result = REDCap::saveData($this->current_project, 'json', json_encode(array($this->data)));
                break;
            default:
                $result = array("error"=>"Unknown action: " . $this->action);
        }

        // Return result
        header("Content-type: application/json");
        echo json_encode($result);
    }

    /**
     * @return int
     */
    public function getNextInstanceId() {
        $q = \REDCap::getData($this->current_project, 'json', $this->participant_id);
        $results = json_decode($q,true);
        $i = 0;
        foreach ($results as $result) {
            if (!empty($result['redcap_repeat_instance'])) {
                $instance = $result['redcap_repeat_instance'];
                $i = max($i, $instance);
            }
        }
        $i++;
        return $i;
    }

    public function getProjectUrl($project_id) {
        $url = $this->getUrl("service.php",true, true);
        $url .= "&pid=" . $project_id;
        return $url;
    }


    public function displayEnabledProjects() {
        // Scan
        $this->getEnabledProjects();

        ?>
            <table>
                <tr>
                    <th>
                        Project ID
                    </th>
                    <th>
                        Project Name
                    </th>
                </tr>
        <?php
        foreach ($this->enabledProjects as $project) {
            echo "<tr><td><a target='_BLANK' href='" . $project['url'] . "'>" . $project['pid'] . "</a></td><td>" . $project['name'] . "</td></tr>";
        }
        ?>
            </table>
        <?php
    }


    /**
     * Load all FCR API enabled projects
     */
    public function getEnabledProjects() {
        $enabledProjects = array();
        $projects = \ExternalModules\ExternalModules::getEnabledProjects($this->PREFIX);
        //while($project = db_fetch_assoc($projects)){
        while($project = $projects->fetch_assoc()){
            $pid  = $project['project_id'];
            $name = $project['name'];
            $url  = APP_PATH_WEBROOT . 'ProjectSetup/index.php?pid=' . $project['project_id'];

            $enabledProjects[$pid] = array(
                'pid' => $pid,
                'name' => $name,
                'url' => $url
            );
        }

        $this->enabledProjects = $enabledProjects;
        $this->emDebug($this->enabledProjects, "Enabled Projects");
    }


    /**
     * Finds the currentProject for the user and passcode
     * @return bool
     */
    public function getCurrentProject() {
        foreach ($this->enabledProjects as $pid => $project_data) {
            $q = REDCap::getData($pid, 'json', $this->participant_id, array('id','pw','alias','deactivate'));
            $results = json_decode($q,true);
            $this->emDebug("Query for " . $this->participant_id . " in project " . $pid . " with " . count($results) . " results");
            foreach ($results as $result) {
                if (strtoupper($result['id']) == $this->participant_id && strtoupper($result['pw']) == strtoupper($this->passcode)) {
                    $this->emDebug("Found a match", $this->participant_id, $this->passcode);
                    $this->current_record = $result;
                    $this->current_project = $pid;
                    return true;
                }
                $this->emDebug("Got a record, but didn't match", $this->participant_id, $this->passcode, $result);
            }
        }
        return false;
    }


    /**
     * Determine if user is active or inactive
     * @return bool
     */
    public function activeUser() {
        $inactive = isset($this->current_record['deactivate___1']) && $this->current_record['deactivate___1'] == '1';
        return !$inactive;
    }




    function emLog() {
        $emLogger = \ExternalModules\ExternalModules::getModuleInstance('em_logger');
        $emLogger->emLog($this->PREFIX, func_get_args(), "INFO");
    }

    function emDebug() {
        // Check if debug enabled
        if ( $this->getSystemSetting('enable-system-debug-logging') || ( !empty($_GET['pid']) && $this->getProjectSetting('enable-project-debug-logging'))) {
            $emLogger = \ExternalModules\ExternalModules::getModuleInstance('em_logger');
            $emLogger->emLog($this->PREFIX, func_get_args(), "DEBUG");
        }
    }

    function emError() {
        $emLogger = \ExternalModules\ExternalModules::getModuleInstance('em_logger');
        $emLogger->emLog($this->PREFIX, func_get_args(), "ERROR");
    }


}