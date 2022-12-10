# Quality

## Initial quality of the application

The application is a web application that allows users to create and manage their own events. The application is
developed in the context of a school project.

It uses a classic Symfony MVC architecture. It uses Symfony 3.1 and PHP until 5.5.9. As of 2022 these are outdated
technologies.

This CodeClimate analysis reports that there are no technical debts used to code smells or duplication.

Though a PHPStan analysis with the maximum level compatible with the project version of PHP reports 5 errors:

```text
$ ./vendor/bin/phpstan analyse src --level=7
 9/9 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

 ------ ------------------------------------------------------- 
  Line   src/AppBundle/Controller/UserController.php            
 ------ ------------------------------------------------------- 
  33     Call to an undefined method object::encodePassword().  
  57     Call to an undefined method object::encodePassword().  
 ------ ------------------------------------------------------- 

 ------ ------------------------------------------------------------------- 
  Line   src/AppBundle/Controller/SecurityController.php                    
 ------ ------------------------------------------------------------------- 
  18     Call to an undefined method object::getLastAuthenticationError().  
  19     Call to an undefined method object::getLastUsername().             
 ------ ------------------------------------------------------------------- 

 ------ ---------------------------------------------------------- 
  Line   src/AppBundle/Entity/Task.php                             
 ------ ---------------------------------------------------------- 
  45     Class DateTime referenced with incorrect case: Datetime.  
 ------ ---------------------------------------------------------- 

                                                                                                                        
 [ERROR] Found 5 errors                                                                                                 
                                                                                                                        
```

Four of the five errors seem to be related to the use of the container instead of dependency injection, making the
static analysis struggle to determine types.

Also, reading the source code reveals that services are accessed directly from the container instead of being injected.
This is a bad practice that should be avoided nowadays.

## Strategy to solve this issues

To solve these quality issues, we need to establish a strategy. The strategy is to:

1. Upgrade the application to the latest Symfony and the latest PHP versions
2. Fix the PHPStan errors
3. Increase the PHPStan level to the maximum (from 7 to 9)
4. Fix the new PHPStan errors
5. Fix the SRP violations by using repositories and voters for access control.
6. Check potential new errors from CodeClimate
7. Fix the new CodeClimate errors

## Result

The last CodeClimate analysis can be read [here](https://codeclimate.com/github/AlexandreGerault/p8_todo_list).
