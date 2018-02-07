**Rest API with JWT Authentication** 

Generate SSH keys:

`$ mkdir -p var/jwt #`
`$ openssl genrsa -out var/jwt/private.pem -aes256 4096`
`$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem`

Register:
`curl -X POST http://localhost:8000/register -d _username=test -d _password=test`

Get token and login:
`curl -X POST http://localhost:8000/login_check -d _username=test -d _password=test`


Use CRUD:

POST:`curl -X POST -H "Authorization: Bearer TOKEN" http://localhost:8000/sensors`

GET ALL: `curl -X GET -H "Authorization: Bearer TOKEN" http://localhost:8000/sensors`

GET: `curl -X POST -H "Authorization: Bearer TOKEN" http://localhost:8000/sensors/{id}`

DELETE `curl -X DELETE -H "Authorization: Bearer TOKEN" http://localhost:8000/sensors/{id}`


This links are available: 

/sensors (Allowed Method: GET, POST, PUT, PATCH, DELETE)

/keys (Allowed Method:GET, POST, PUT, PATCH, DELETE) 

/users (Allowed Method: GET, DELETE)
