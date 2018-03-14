<?php
/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

if(file_exists(__DIR__.'/Tester/bootstrap.php'))
{
    require_once __DIR__.'/Tester/bootstrap.php';

    Tester\Environment::setup();
}
else
{
    exit("Nette tester not found");
}