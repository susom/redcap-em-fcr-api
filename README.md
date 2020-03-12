# The FCR Api is an EM that you can enable on multiple projects to segment research data

## Instructions
1. Enable the FCR API on your project.  Your project should resemble project 10274





### Security
Each call accepts a pregenerated Participant ID and Passcode.  To match against a pregenerated API Token Pool

### Project-Specific API Endpoint
This module must be enabled on a specific REDCap project.  It will then generate a unique endpoint url that will serve as your token replay endpoint.  You can then send a POST request to the endpoint to initiate an email.  The url will be visible on the 'Instructions' link that appears after activating the module on a project.

### Example Syntax

Incoming request contains a json object

```json
{
    "participant_id": "xxx",
    "passcode"      : "yyy",
    "action"        : "VERIFY"
}

Returns 
{"error":"error message"}
OR Record Data
{"id": "xxx", ... }
```

To save data:

```json
{
    "participant_id": "xxx",
    "passcode"      : "yyy",
    "action"        : "SAVEDATA",
    "data"          : { "key": "value", "key2": "value" }
}

Returns 
{"error":"error message"}
OR Record Data
TRUE
```


The following parameters are valid in the body of the POST

    participant_id: Pregenerated
    passcode:       # Code

The API will return a json object with the appropriate API Token (maybe salted?)
