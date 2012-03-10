PHP\Manipulator
==============

[![Build Status](https://secure.travis-ci.org/robo47/PHP-Manipulator.png)](robo47/PHP-Manipulator)


Table of Contents
-----------------

* About PHP\Manipulator
* Requirements
* Installation
* License
* Usage
* Project directory/file structure


About PHP\Manipulator
---------------------

A command-line tool for manipulating php code.

PHP\Manipulator is build to be easily extendable with your own Actions and Actionsets
[for example for configure multiple Actions forming a Coding-Standard or a
special task for you project] and configurable via a simple xml-file allowing to
choose which files should be changed and which action(set)s should be used on those files.

Code ist hosted at http://www.github.com/robo47/php-manipulator and directly
installable via my PEAR-server pear.robo47.net.


Requirements
------------

PHP\Manipulator requires PHP 5.3+ using features like namespaces and Closures.

extensions:

* spl
* tokenizer
* pcre
* dom

libraries:

* Symfony 2.0
 * Console
 * Finder
* PHPUnit 3.6 (unittests only)

configuration:

(both only if the code you want to manipulate contains them!)
short_open_tag = On
asp_tags = On


Installation
------------

Create a composer.json 

    ``` json
    {
        "require": {
            "robo47/php-manipulator": "*"
        }
    }
    ```

Download and run composer

    ``` sh
    curl -s http://getcomposer.org/installer | php
    php composer.phar install
    ```

License
-------

MIT

See file LICENSE


Usage
-----

Running phpmanipulator:

    bin/phpmanipulator

Run actions from a config:

    bin/phpmanipulator runActions ./config.xml

Show tokens of a script

    bin/phpmanipulator showTokens /path/to/script.php


Project directory/file structure
--------------------------------

<pre>
+-bin/phpmanipulator.php        # the phpmanipulator binary
+-helper/                       # Directory with templates for creating new Classes
+-src/
| +-PHP/                        # Here the actual PHP\Manipulators Code is located
+-tests/
| +-Baa/                        # Directory with Dummy-Files for testing Components, the Config and Loading
| +-Foo/                        # Directory with Dummy-Files for testing Components, the Config and Loading
| +-Tests
| | +-Constraint/               # Tests for special Constraints used in the tests
| | +-Stub/                     # Tests for Stub-Objects used in the tests
| | +-PHP/                      # HERE are the actual unittests for PHP\Manipulator
| | +-TestCase.php              # The base-TestCase Class with additional asserts using the new Constraints and some other methods
| | +-Util.php                  # Helper-Methods used in the Constraints and for Debugging while riding new Code
| +-_fixtures/                  # Containing all the php/xml-files used in the unittests ordered in subdirectories for each namespace below \PHP\Manipulator
| +- TestHelper.php             # Bootstrap-File for unittests setting include-path and setting up the Symfony-Autoloader
| +-phpunit.xml                 # Default config only Running Tests in /tests/Tests/PHP
| +-phpunitTests.xml            # Config only running tests of "testing-components" like the Constraints, the Stubs, the TestCase and the Util-class.
+-LICENSE                       # File containing the used New BSD-License for PHP\Manipulator
+-README                        # The file you are currently reading
+-TODO                          # List of todos, whishes, ideas and plans
+-build.xml                     # ant-build-script used mainly by hudson for running all tools around a build (phpunit, phpcpd, phpcs, phplint, phpunit, ... )
+-helper.php                    # Simple cli-script to create new Classes + unitests + empty fixture-files
+-phpmanipulator.xml            # Default configuration for running phpManipulator for enforcing coding-standard for itself by formatting/indenting code the right way (formatting not like expected yet!)
</pre>


helper script
-------------

The helper.php is only meant for development, it allows an easy and fast creation of new empty dummys for Actions,
TokenConstraints, TokenManipulators, ContainerConstraints and TokenFinders including a unittest-file-dummy and empty fixture-files via
the command line.