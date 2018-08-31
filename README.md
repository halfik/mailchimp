# MailChimp API v3.0 PHP Wrapper

PHP wrapper for the MailChimp API v 3.0.
Package is fork of *https://github.com/nblakefriend/MailChimp-API3.0-Wrapper*

### Dependencies
- GuzzleHttp
- PHP > 5.4
- Laravel >= 5.5

*Project still in the works. More documentation to come*

### Installing
Add this to your application composer.json:

     "repositories": [
            {
                "type": "vcs",
                "url": "http://github.com/halfik/mailchimp"
            }
        ],
         "require": {
                "halfik/mailchimp": "dev-mas"
            },
        

### Getting Started
1. Add provider to your app.php config file:

       \Halfik\MailChimp\MailChimpProvider::class,
       
2. And publish config:

     php artisan vendor:publish --tag=config
       

3. You should set your mailchimp api key in .env

    MAILCHIMP_APIKEY=key_here
    
4. You can also set default list by adding it to .env

    MAILCHIMP_LIST_ID
    
You can add more list to config file

### Using the Wrapper

You can you IoC to create MailChimp instance:

     $mc = \App::make('halfik.mailchimp')

Each MailChimp collections *(lists, campaigns, e-commerce etc.)* is accessed using a method found at the bottom of the `MailChimp.php` file that instantiates the collection's class.

**For example:**
*Assuming your MailChimp instance is stored in the `$mc` variable*

#### Lists
`$mc->lists()->getLists();`

This would return the response from calling /lists
http://developer.mailchimp.com/documentation/mailchimp/reference/lists/#read-get_lists

#### E-commerce
Adding a new store customer:

`$mc->ecommerce()->customers()->addCustomer("STORE123", "CUST123", "freddie@freddiesjokes.com", true);`

This would create a new customer to the store with id `STORE123` with the customer id `CUST123` and the email address `freddie@freddiesjokes.com` and an opt-in status of true which subscribes the customer to the list.

**Collection Reference**
* authorizedApps()
* automations()
* batchOps()
* campaignFolders()
* campaigns()
* conversations()
* ecommerce()
*   - ecommerce()->carts()
*   - ecommerce()->customers()
*   - ecommerce()->orders()
*   - ecommerce()->products()
* fileManager()
* lists()
* reports()
* templateFolders()
* templates()

**[See complete list of available methods for each class/collection here](https://nblakefriend.github.io/MailChimp-API3.0-Wrapper/index.html)**

Docs also able to be run locally from the `docs/index.html`
