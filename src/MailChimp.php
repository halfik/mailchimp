<?php
namespace MailChimp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use MailChimp\AuthorizedApps\AuthorizedApps as AuthorizedApps;
use MailChimp\Automations\Automations as Automations;
use MailChimp\Batches\Batches as Batches;
use MailChimp\CampaignFolders\CampaignFolders as CampaignFolders;
use MailChimp\Campaigns\Campaigns as Campaigns;
use MailChimp\Ecommerce\Ecommerce as Ecommerce;
use MailChimp\Lists\Lists as Lists;
use MailChimp\TemplateFolders\TemplateFolders as TemplateFolders;
use MailChimp\Templates\Templates as Templates;
use MailChimp\OAuth\OAuth as OAuth;


class MailChimp
{

    private static $mc_root;
    private static $apikey;
    private static $config = "config.ini";
    private static $config_dev = "configtest.ini";
    protected static $client;

    public function __construct()
    {
        // Setting http_errors to false since guzzle explodes for anything not 200
        $client = new Client([
            'base_uri' => self::getUrl(),
            'auth' => ['api', self::getActiveKey()],
            'cookies' => true,
            'allow_redirects' => true,
            'http_errors' => false
        ]);
        MailChimp::$client = $client;
    }

    /**
     * Get the API URL to use
    */
    private static function getUrl()
    {
        $dc = self::getDatacenter();
        return  "https://{$dc}.api.mailchimp.com/3.0/";
    }

    /**
     * Get the Datacenter from a the set API key
    */
    private static function getDatacenter()
    {
        // Determine the Datacenter from the API Key
        $dc = trim(strstr(self::getActiveKey(), "-"), "-");
        return $dc;
    }

    /**
     * Get the config file from the name set in $config
    */
    private static function getConfig()
    {
        $path_to_config = self::$config;
        $config = parse_ini_file($path_to_config, true);
        return $config;
    }

    /**
     * Find the key to use from the "active" key.
     * TODO: Is this way unnessesary?
    */
    private static function getActiveKey()
    {
        $config = self::getConfig();
        foreach ($config["apikeys"] as $api) {
            if ($api["active"]) {
                return $api["apikey"];
            }
        }
    }

    /**
    * Set the data passed for GET query parameters or POST/PUT/PATCH data
    *
    * @param array $data
    * @return array
    */
    private static function setData($method, array $data = [])
    {
        // TODO: consider sanitizing incoming data?
        foreach ($data as $key => $value) {
            // Set query parameters if method is GET
            if ($method == "GET") {
                // If the value is an array convert it to a string
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                // Set the query param to an associative array
                $params['query'][$key] = $value;
            } else {
                $params['json'][$key] = $value;
            }
        }
        return $params;
    }

    protected static function execute($method, $url, array $data = [])
    {
        if ($data) {
            $response = self::$client->request($method, $url, self::setData($method, $data));
        } else {
            $response = self::$client->request($method, $url);
        }

        $status_code = $response->getStatusCode();
        $response_body = json_decode($response->getBody()->getContents());
        // if ($status_code === 200 || $status_code === 204) {
            // return $response_body;
        // } else {

        // }
        return $response_body;
    }

    /**
     * Create the member hash
     *
     * @param string email address
     * @return string
     */
    protected static function getMemberHash($emailAddress)
    {
        return md5(strtolower($emailAddress));
    }

    public function oauth()
    {
        return new OAuth;
    }

    /** RESOURCES */

    /**
    * Get account information from the API Root
    * Available query fields:
    * array["fields"]              array       list of strings of response fields to return
    * array["exclude_fields"]      array       list of strings of response fields to exclude (not to be used with "fields")
    * @param array $query (See Above) OPTIONAL associative array of query parameters.
    * @return object
    */
    public function getAccountInfo(array $query = [])
    {
        return self::execute("GET", "", $query);
    }

    public function authorizedApps()
    {
        return new AuthorizedApps;
    }

    public function automations()
    {
        return new Automations;
    }

    public function batchOps()
    {
        return new Batches;
    }

    public function campaignFolders()
    {
        return new CampaignFolders;
    }

    public function campaigns()
    {
        return new Campaigns;
    }

    public function conversations()
    {
        return "This collection is not completed";
    }

    public function ecommerce()
    {
        /**
         * TODO: Collection in progress
         */
        return new Ecommerce;
    }

    public function fileManagerFiles()
    {
        return "This collection is not completed";
    }

    public function fileManagerFolders()
    {
        return "This collection is not completed";
    }

    public function lists()
    {
        return new Lists;
    }

    public function reports()
    {
        return "This collection is not completed";
    }

    public function templateFolders()
    {
        return new TemplateFolders;
    }

    public function templates()
    {
        /**
         * TODO: Collection in progress
         */
        return new Templates;
    }

} // End MailChimp class
