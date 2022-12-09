# Contributing to the project

## How to contribute

1. Clone the repository
2. Create a new branch
3. Make your changes
4. Run the tests
5. Commit your changes
6. Push your changes
7. Create a pull request
8. Wait for the review
9. Merge your changes
10. Delete your branch
11. Celebrate
12. Repeat

## How to run the tests

To run the tests, run the following command:

```
make test
```

## How to run the application

To run the application, run the following command:

```
make up
```

The application is then available at http://localhost:3435. If you changed the `NGINX_REWRITE_PORT` environment
variable, then you need to change the port in the URL.

## Authentication

The application uses the default authentication system of Symfony. It uses the default ACL system based on roles. The
roles are:

- ROLE_USER
- ROLE_ADMIN

These roles can be used to restrict access to certain parts of the application. Protected areas are defined in
the `security.yml` file, like this:

```yaml
security:
  access_control:
    - { path: ^/login, roles: PUBLIC_ACCESS }
    - { path: ^/users, roles: ROLE_ADMIN }
    - { path: ^/, roles: ROLE_USER }
```

To get more information about this, read the security section of
the [Symfony documentation](https://symfony.com/doc/current/security.html).

In this application, the security is configured to use the `entity` provider. This means that the users are stored in a
database and the authentication is done by comparing the password stored in the database with the password entered by
the user. The password is stored in the database using the `bcrypt` algorithm.

## Which files to edit

### Architecture

The application follows the MVC pattern. The model is defined in the `src/Entity` directory. The views are defined in
the `templates` directory. The controllers are defined in the `src/Controller` directory.

### Database

#### Queries

To query data or to save data, you need to use the Doctrine ORM, and repositories. The repositories are defined in the
`src/Repository` directory. They are used to query data from the database. The repositories are used by the
controllers to get the data from the database.

#### Entities

To add a new entity, you need to create a new entity class. The entity class is responsible for defining the fields of
the entity and for defining the relationships between the entities. The entity class is used by the repositories to
query the data from the database. The entity class is also used by the form types to define the fields of the form.
Note that you need to generate migrations when you add a new entity, or edit an existing one.

### Pages and forms

To add a new page, you need to create a new controller. The controller is responsible for getting the data from the
database and passing it to the view. The controller is also responsible for handling the form submission.

To add a new form, you need to create a new form type. The form type is responsible for defining the fields of the
form and for validating the data. The form type is used by the controller to create the form and to handle the form
submission.

For more information, feel to free to read the Symfony documentation about the subject you're interested in.

## Good practices

### Coding standards

The application uses PHP CS Fixer to enforce coding standards. The coding standards are defined in the `.php_cs.dist`
and you can format your code using the following command:

```
make format
```

Also, to avoid quality issues, the application uses PHPStan to check the code. You can run the PHPStan analysis using
the following command:

```
make analyse
```

### Tests

The application uses PHPUnit to run the tests. The tests are defined in the `tests` directory. You can run the tests
using the following command:

```
make test
```

It is important to run the tests locally before pushing your changes. If the tests fail, you need to fix the code before
pushing your changes. It is also better to write tests first, before writing the code. This way, you can be sure that
the code you write is working as expected. It improves the quality of the code, and it makes the code easier to maintain
or/and refactor.

If the test suite pass locally but fail on the CI, it might be because of a difference in the environment. For example,
the CI might use a different version of PHP, or a different version of a dependency. In this case, you need to update
the `composer.json` file to use the same version of the dependency as the CI. Just keep in mind that you might have to
look for a difference in the two environments.
