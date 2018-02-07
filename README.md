**Rest API with JWT Authentication** 

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