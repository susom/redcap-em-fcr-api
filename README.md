# Token Relay API Instructions
This module allows you to create a stand alone API url that can be used for assigning API Tokens to other REDcap projects

### Security
Each call accepts a pregenerated Participant ID and Passcode.  To match against a pregenerated API Token Pool

### Project-Specific API Endpoint
This module must be enabled on a specific REDCap project.  It will then generate a unique endpoint url that will serve as your token replay endpoint.  You can then send a POST request to the endpoint to initiate an email.  The url will be visible on the 'Instructions' link that appears after activating the module on a project.

### Example Syntax
The following parameters are valid in the body of the POST

    participant_id: Pregenerated
    passcode:       # Code

The API will return a json object with the appropriate API Token (maybe salted?)
