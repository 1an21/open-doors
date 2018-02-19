**Rest API with JWT Authentication** 

Register:
`curl -X POST http://localhost:8000/register -d _username=test -d _password=test`

Get token and login:
`curl -X POST http://localhost:8000/login_check -d _username=test -d _password=test`


This links are available: 

/locks (Allowed Method: GET, POST)

/locks/{id} (Allowed Method: GET, DELETE, PUT, PATCH)

/keys (Allowed Method: GET, POST) 

/keys/{id} (Allowed Method: GET, DELETE, PUT, PATCH)

/employees (Allowed Method: GET, POST)

/employees/{id} (Allowed Method: GET, DELETE, PUT, PATCH)

/employees/{id}/keys (Allowed Method: GET)

/employees/{id}/keys/{id} (Allowed Method: GET, DELETE, PUT, PATCH)

locks/{id}/availablekeys (Allowed Method: GET)

locks/{id}/availablekeys/{id} (Allowed Method: GET, DELETE, PUT, PATCH)

/login_check

/register