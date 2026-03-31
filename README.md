OIDC EXPERIMENT
===============

## OIDC works in 2 phases:

1. Authentication request
2. Token request

## Authentication request

1. the RP sends a request to the OP at /authorize.
2. according to the outcome, whether it's success or failure, the OP sends a 302 redirect back to the callback URI (RP) with the proper parameters.

## Token request

1. given that Authentication request was successful, we now have an Authentication "code".
2. The next step is to send this along with a few other parameters, to the /token endpoint.
2. the endpoint will return a JSON containing:
    - an access token
    - an id token (JWT)
    - (maybe) a refresh token
    - a few other fields
3. finally, the RP needs to do some validations on the data it got. Then, we can use the JWT.

## Next steps

- implement 3.1.3.2.  Token Request Validation
- accomodate 3.1.2.5.  Successful Authentication Response
  by adding parameters to the request_uri according to oauth2's spec

