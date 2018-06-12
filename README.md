# Email Relay API Instructions
This module allows you to create a project-specific API url that can be used for relaying email messages from an outside system (such as a GCP/Amazon project. 

Each email sent is logged to the REDCap project logs (and optionally to the specified record)

### Security
Two security mechanisms are in place to try and control access to this API

##### Email Tokens
Each project uses a unique token as a shared key to authenticate against the relay endpoint.  It does not use the normal REDCap API user tokens.

##### IP Filters
IP Filters can be created when configuring the module for a given project.  You can use either an static IP or a range of IPs using CIDR notation.  If configured, only these IP addresses will be able to use the API.


### Project-Specific API Endpoint
This module must be enabled on a specific REDCap project.  It will then generate a unique endpoint url that will serve as your email replay endpoint.  You can then send a POST request to the endpoint to initiate an email.  The url will be visible on the 'Instructions' link that appears after activating the module on a project.

### Example Syntax
The following parameters are valid in the body of the POST

    email_token: ##RANDOM## (this token is unique to this project)
    to:          A comma-separated list of valid email addresses (no names)
    from_name:   Jane Doe
    from_email:  Jane@doe.com
    cc:          (optional) comma-separated list of valid emails
    bcc:         (optional) comma-separated list of valid emails
    subject:     A Subject
    body:        A Message Body (<b>html</b> is okay!)
    record_id:   (optional) a record_id in the project - email will be logged to this record

The API will return a json object with either `result: true|false` or `error: error message`

### Misc
At this point attachments are not supported.