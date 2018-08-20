<?php
namespace Stanford\TokenRelay;

use REDCap;
use Message;

/**
 * Class TokenRelay
 *
 * @package Stanford\TokenRelay
 */
class TokenRelay extends \ExternalModules\AbstractExternalModule
{
    const TOKEN_KEY = "token_relay_api_token";
    public $api_token;
    private $url;

    public function __construct()
    {
        parent::__construct();

        // If in project context, load the object
        global $project_id;
        if ($project_id) {
            self::emLog("Loading " . self::TOKEN_KEY . " for project $project_id");
            $this->email_token = $this->getProjectSetting(self::TOKEN_KEY, $project_id);
        }

        self::emLog($this->PREFIX . " constructed $project_id");
    }

    public function redcap_module_project_enable($version, $project_id) {
        if (empty($this->relay_token)) {
            // Generate a random token for this project
            $this->relay_token = generateRandomHash(10);
            $this->setProjectSetting(self::TOKEN_KEY, $this->relay_token, $project_id);
        }
        // self::log($this->PREFIX . " " . $version . " was enabled for project $project_id");
    }

    public function redcap_module_project_disable($version, $project_id) {
        // self::log($this->PREFIX . " " . $version . " was disabled for project $project_id");
    }


    public function getProjectUrl($project_id) {
        $url = $this->getUrl("service",true, true);
        $url .= "&pid=" . $project_id;
        return $url;
    }

    /**
     * Takes a string of emails and returns a validated string of emails
     * @param $list of emails
     * @return array with true|false and result
     */


    // Checks if the IP is valid given an IP or CIDR range
    // e.g. 192.168.123.1 = 192.168.123.1/30
    public static function ipCIDRCheck ($CIDR) {
        $ip = trim($_SERVER['REMOTE_ADDR']);

        // Convert IPV6 localhost into IPV4
        if ($ip == "::1") $ip = "127.0.0.1";

        if(strpos($CIDR, "/") === false) $CIDR .= "/32";
        list ($net, $mask) = explode("/", $CIDR);
        $ip_net  = ip2long($net);
        $ip_mask = ~((1 << (32 - $mask)) - 1);
        $ip_ip = ip2long($ip);
        $ip_ip_net = $ip_ip & $ip_mask;
        return ($ip_ip_net == $ip_net);
    }


    function emLog() {
        $emLogger = \ExternalModules\ExternalModules::getModuleInstance('em_logger');
        $emLogger->emLog($this->PREFIX, func_get_args(), "INFO");
    }

    function emDebug() {
        // Check if debug enabled
        if ($this->getSystemSetting('enable-system-debug-logging') || $this->getProjectSetting('enable-project-debug-logging')) {
            $emLogger = \ExternalModules\ExternalModules::getModuleInstance('em_logger');
            $emLogger->emLog($this->PREFIX, func_get_args(), "DEBUG");
        }
    }

    function emError() {
        $emLogger = \ExternalModules\ExternalModules::getModuleInstance('em_logger');
        $emLogger->emLog($this->PREFIX, func_get_args(), "ERROR");
    }


}