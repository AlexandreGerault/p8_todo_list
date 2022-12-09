# TodoList app

This is an application to manage your tasks.

## Installation

### Requirements

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Makefile](https://www.gnu.org/software/make/)

### Steps

To install the application, run the following command:

```
make install
```

If you don't have `make` installed or Docker, you might take a look at the `Makefile` and run the commands manually (and
equivalents for Docker commands).

## Run the application

To run the application, run the following command:

```
make up
```

The application is then available at http://localhost:3435. If you changed the `NGINX_REWRITE_PORT` environment
variable, then you need to change the port in the URL.

## Run the tests

To run the tests, run the following command:

```
make test
```
