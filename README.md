# Video Game Api

### Prerequisites
To run this application you will need to have the following installed locally:

- Docker Compose
- Composer

### Getting Started
To pull this application to your local machine execute the following command:
```
$ git clone git@github.com:joesweeny/video-game-api.git
```

This service is built using PHP7.3 and served using docker-compose. To begin you will need to install the
relevant PHP dependencies by executing the following command:
```
$ composer install --ignore-platform-reqs
```
The next step is to build the services defined in the `docker-compose.yml` file by executing the following
helper script:
```
$ bin/docker-dev-up -d --build
```
Executing the above command performs the following:
- Builds the relevant services to run this application (Nginx reverse proxy serving requests to the PHP application
degine in the api docker-composer service).
- Runs the required database migrations (within the migrate service).
- Also runs the database seeder command to seed the database with the data provided in the test specification.

#### Note
Executing `bin/docker-dev-up -d --build` returns to the terminal the host and port to access the API.

### API Usage
The service exposes the following endpoints:

##### List all games
- GET /game

The following query parameters are supported to filter the game data:
- name (current support is for exact match only)
- publisher (current support is for exact match only)
- release_date (format to be provided in the format Y-m-d i.e. 2020-02-07)

* Please note support for query parameters only supports AND clauses so if all parameters are provided only records
matching ALL parameters will be returned.

Example:
```
GET /game?name=FIFA%2020
```

##### List all users
- GET /user

##### List all comments for a user
- GET /user/:id/comments

The path parameter `:id` is a Uuid string. To save you diving into the database the following Users are
currently supported:

- Dave Clark (36f1cd28-6fb0-405e-aa14-2a4ea9e89701)
- Patricia Summer (19bacf9a-5350-4803-a9e4-48991e93ffad)
- Thomas Jeffrey (86d15df5-6a19-4489-b286-1f02575564ed)

Example:
```
GET /user/36f1cd28-6fb0-405e-aa14-2a4ea9e89701/comments
```

### Testing
This application is backed by both unit and integration tests. Tests are run against an in memory SQLite 
database. To run the whole test suite execute the following command:
```
$ bin/test
```
