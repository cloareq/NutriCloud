<?php
echo 'Test Pro: ';
$output = shell_exec("php phpunit.phar -c app/ src/NC/ProBundle/Tests/");
echo $output;
?>
