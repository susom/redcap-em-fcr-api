# Email Relay API Instructions
This module allows you to create a project-specific API url that can be used for relaying email messages from an outside system (such as a GCP/Amazon project. It does not use the normal REDCap API tokens but a project specific token that was generated when the module was activated on this project.

Each email sent is logged to the REDCap project logs (and optionally to the specified record)


### Endpoint
You must send a 'POST' request to the following url to initiate an email:

http://your_server/api/?type=module&prefix=email_relay&id=xx&page=service&pid=yy

### Example
The following parameters are valid in the body of the POST

    token:      ##RANDOM## (this token is unique to this project)
    to:         A comma-separated list of valid email addresses (no names)
    from_name:  Jane Doe
    from_email: Jane@doe.com
    cc:         (optional) comma-separated list of valid emails
    bcc:        (optional) comma-separated list of valid emails
    subject:    A Subject
    body:       A Message Body (<b>html</b> is okay!)
    record_id:  (optional) a record_id in the project - email will be logged to this record

### IP Filters
IP Filters can be created using CIDR notation to futher restrict access to this api url

### Misc
At this point attachments are not supported.