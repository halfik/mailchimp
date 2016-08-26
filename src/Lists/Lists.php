<?php
namespace MailChimp\Lists;

use MailChimp\MailChimp as MailChimp;
use MailChimp\Lists\Interests as Interests;
use MailChimp\Lists\Members as Members;
use MailChimp\Lists\MergeFields as MergeFields;
use MailChimp\Lists\Segments as Segments;
use MailChimp\Lists\SignupForms as SignupForms;
use MailChimp\Lists\Webhooks as Webhooks;

class Lists extends MailChimp
{

    /**
     * Get a list of lists for the account
     * Available query fields:
     * array["fields"]                  array       list of strings of response fields to return
     * array["exclude_fields"]          array       list of strings of response fields to exclude (not to be used with "fields")
     * array["count"]                   int         number of records to return
     * array["offset"]                  int         number of records from a collection to skip.
     * array["before_date_created"]     string      Restrict response to lists created before the set date.
     *                                              ISO 8601 time format: 2015-10-21T15:41:36+00:00.
     * array["since_date_created"]      string      Restrict results to lists created after the set date.
     *                                              ISO 8601 time format: 2015-10-21T15:41:36+00:00.
     * array["before_campaign_last_sent"] string    Restrict results to lists created before the last campaign send date.
     *                                              ISO 8601 time format: 2015-10-21T15:41:36+00:00.
     * array["since_campaign_last_sent"] string     Restrict results to lists created after the last campaign send date.
     *                                              ISO 8601 time format: 2015-10-21T15:41:36+00:00.
     * email                            string      Restrict results to lists that include a specific subscriber’s email address.`
     *
     * @param array $query (See Above) OPTIONAL associative array of query parameters.
     * @return object
     */
    public function getLists(array $query = [])
    {
        return self::execute("GET", "lists", $query);
    }

    /**
     * Get a single campaign
     *
     * array["fields"]              array       list of strings of response fields to return
     * array["exclude_fields"]      array       list of strings of response fields to exclude (not to be used with "fields")
     *
     * @param string $list_id for the list instance
     * @param array $query (See Above) OPTIONAL associative array of query parameters.
     * @return object list instance
     */
    public function getList($list_id, array $query = [])
    {
        return self::execute("GET", "lists/{$list_id}", $query);
    }

    /**
     * Create a list
     * array["data"]
     *      ["name"]                string      required
     *      ["permission_reminder"] string      required
     *      ["contact"]             array       required
     *          ["company"]         string      required
     *          ["address1"]        string      required
     *          ["address2"]        string
     *          ["city"]            string      required
     *          ["state"]           string      required
     *          ["zip"]             string      required
     *          ["country"]         string      required
     *          ["phone"]           string
     *      ["use_archive_bar"]     boolean
     *      ["campaign_defaults"]   array       required
     *          ["from_name"]       string      required
     *          ["from_email"]      string      required
     *          ["subject"]         string      required
     *          ["language"]        string      required
     *      ["notify_on_subscribe"] string      The email address to send subscribe notifications to.
     *      ["notify_on_unsubscribe"] string    The email address to send unsubscribe notifications to.
     *      ["email_type_option"]   boolean
     *      ["visibility"]          string      Whether this list is public or private.
     *                                          Possible Values: pub,prv
     * @param array $data (See Above)
     * @return object created list information
     */
    public function createList($name, $permission_reminder, $email_type_option, array $campaign_defaults = [], array $contact = [], array $optional_settings = null)
    {
        $data = [
            "name" => $name,
            "permission_reminder" => $permission_reminder,
            "email_type_option" => $email_type_option,
            "campaign_defaults" => $campaign_defaults,
            "contact" => $contact
        ];

        if (isset($optional_settings)) {
            foreach ($optional_settings as $key => $value) {
                switch (strtolower($key))
                {
                    case "visibility":
                        $data["visibility"] = $value;
                        break;
                    case "notify_on_subscribe":
                        $data["notify_on_subscribe"] = $value;
                        break;
                    case "notify_on_unsubscribe":
                        $data["notify_on_unsubscribe"] = $value;
                        break;
                    case "use_archive_bar":
                        $data["use_archive_bar"] = $value;
                        break;
                    default:
                        break;
                }
            }
        }
        return self::execute("POST", "lists", $data);
    }

    /**
     * Update an existing list
     *
     * @param string $list_id list id for list to edit
     * @param array $data fields to update (See structure from createList)
     * @return object updated list
     */
    public function updateList($list_id, array $data = [])
    {
        return self::execute("PATCH", "lists/{$list_id}", $data);
    }

    /*
     * Batch Sub/Unsub members
     */
    public function batchMembers($list_id, array $batch = [], $updateExisting = false)
    {

        $b = ["members" => $batch, "update_existing" => $updateExisting];
        return self::execute("POST", "lists/{$list_id}", $b);
    }

    /**
     * Get all abuse reports for a specific list.
     *
     * Available query fields:
     * array["fields"]              array       list of strings of response fields to return
     * array["exclude_fields"]      array       list of strings of response fields to exclude (not to be used with "fields")
     * array["count"]               int         number of records to return
     * array["offset"]              int         number of records from a collection to skip.
     * @param string $list_id list id for list to edit
     * @param array $query fields to update (See structure from createList)
     * @return object
     */
    public function getAbuseReports($list_id, array $query = [])
    {
        return self::execute("GET", "lists/{$list_id}/abuse-reports", $query);
    }

    /**
     * Get details about a specific abuse report.
     *
     * Available query fields:
     * array["fields"]              array       list of strings of response fields to return
     * array["exclude_fields"]      array       list of strings of response fields to exclude (not to be used with "fields")
     * @param string $list_id list id for list to edit
     * @param string $reportId
     * @param array $query fields to update (See structure from createList)
     * @return object
     */
    public function getAbuseReport($list_id, $report_id, array $query = [])
    {
        return self::execute("GET", "lists/{$list_id}/abuse-reports/{$report_id}", $query);
    }

    /**
     * Get recent daily, aggregated activity stats for your list.
     * For example, view unsubscribes, signups, total emails sent, opens, clicks, and more, for up to 180 days.
     *
     * Available query fields:
     * array["fields"]              array       list of strings of response fields to return
     * array["exclude_fields"]      array       list of strings of response fields to exclude (not to be used with "fields")
     * @param string $list_id list id for list to edit
     * @param array $query fields to update (See structure from createList)
     * @return object
     */
    public function getActivity($list_id, array $query = [])
    {
        return self::execute("GET", "lists/{$list_id}/activity", $query);
    }

    /**
     * Get a list of the top email clients based on user-agent strings.
     *
     * Available query fields:
     * array["fields"]              array       list of strings of response fields to return
     * array["exclude_fields"]      array       list of strings of response fields to exclude (not to be used with "fields")
     * @param string $list_id list id for list to edit
     * @param array $query fields to update (See structure from createList)
     * @return object
     */
    public function getClients($list_id, array $query = [])
    {
        return self::execute("GET", "lists/{$list_id}/clients", $query);
    }

    /**
     * Get a month-by-month summary of a specific list’s growth activity.
     *
     * Available query fields:
     * array["fields"]              array       list of strings of response fields to return
     * array["exclude_fields"]      array       list of strings of response fields to exclude (not to be used with "fields")
     * array["count"]               int         number of records to return
     * array["offset"]              int         number of records from a collection to skip.
     * @param string $list_id list id for list to edit
     * @param array $query fields to update (See structure from createList)
     * @return object
     */
    public function getGrowthHistory($list_id, array $query = [])
    {
        return self::execute("GET", "lists/{$list_id}/growth-history", $query);
    }

    /**
     * Get a summary of a specific list’s growth activity for a specific month and year.
     *
     * Available query fields:
     * array["fields"]              array       list of strings of response fields to return
     * array["exclude_fields"]      array       list of strings of response fields to exclude (not to be used with "fields")
     * @param string $list_id list id for list to edit
     * @param string $month format: yyyy-mm
     * @param array $query fields to update (See structure from createList)
     * @return object
     */
    public function getGrowthHistoryMonth($list_id, $month, array $query = [])
    {
        return self::execute("GET", "lists/{$list_id}/growth-history/{$month}", $query);
    }



    /**
     * Delete a list
     *
     * @param string list id
     */
    public function deleteList($list_id)
    {
        return self::execute("DELETE", "lists/{$list_id}");
    }


    /**
     *  Instantiate lists subresources
     */
     public function interests()
     {
         return new Interests;
     }

    public function members()
    {
        return new Members;
    }

    public function mergeFields()
    {
        return new MergeFields;
    }

    public function segments()
    {
        return new Segments;
    }

    public function signupForms()
    {
        return new signupForms;
    }

    public function webhooks()
    {
        return new Webhooks;
    }

}
