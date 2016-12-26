<?php

echo 'Test Pro: '. PHP_EOL;
$output = shell_exec("php phpunit.phar -c app/ src/NC/ProBundle/Tests/");
echo $output;

echo 'Test Patient: '. PHP_EOL;
$output = shell_exec("php phpunit.phar -c app/ src/NC/PatientBundle/Tests/Patient/");
echo $output;

echo 'Test Alergy: '. PHP_EOL;
$output = shell_exec("php phpunit.phar -c app/ src/NC/PatientBundle/Tests/Alergy/");
echo $output;

echo 'Test Medication: '. PHP_EOL;
$output = shell_exec("php phpunit.phar -c app/ src/NC/PatientBundle/Tests/Medication/");
echo $output;

echo 'Test Goal: '. PHP_EOL;
$output = shell_exec("php phpunit.phar -c app/ src/NC/PatientBundle/Tests/Goal/");
echo $output;

echo 'Test Faq: '. PHP_EOL;
$output = shell_exec("php phpunit.phar -c app/ src/NC/FaqBundle/Tests/");
echo $output;

echo 'Test Message: '. PHP_EOL;
$output = shell_exec("php phpunit.phar -c app/ src/NC/MessageBundle/Tests/");
echo $output;

?>
