## Testing Steps
1. Install PHPUnit with composer
** Global**
```
composer global require --dev phpunit/phpunit
```
PS: Global Composer is in C:\Users\user\AppData\Roaming\Composer folder
**To test **
```
phpunit --version
```
Create phpunit.xml [Sample Reference](https://raw.githubusercontent.com/drmonkeyninja/phpunit-simple-example/master/phpunit.xml)
```
<?xml version="1.0" encoding="UTF-8"?>

<phpunit bootstrap = "vendor/autoload.php"
    backupGlobals               = "false"
    testdox               		= "true"
    backupStaticAttributes      = "false"
    colors                      = "true"
    convertErrorsToExceptions   = "true"
    convertNoticesToExceptions  = "true"
    convertWarningsToExceptions = "true"
    processIsolation            = "false"
    stopOnFailure               = "false">
    <testsuites>
        <testsuite name="Project Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>


</phpunit>
```
**Put SampleTest.php in `tests` folder**
```
<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SampleTest extends TestCase
{
	// assertion asserts that something matching what you expect to do
    public function testTrueAssertsToTrue()
	{
		$this->assertTrue(true);
	}
	
}
```
Then you could run 'phpunit'
```
phpunit
```




## Local
**Instal**
```
composer  require --dev phpunit/phpunit
```
**Local Testing**
```
"vendor/bin/phpunit" --version
```
**insert in composer.json**
```
"autoload": {
        "classmap": [
            "src/"
        ]
    },
    
```
Also Run
```
composer dump-autoload -o
```

To Test without phpunit.xml, you must specify tests directory
```
"vendor/bin/phpunit"  tests
```
OR for testing with documents
```
"vendor/bin/phpunit" --testdox tests
```