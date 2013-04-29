<?php

use Nette\Diagnostics\Debugger;

require LIBS_DIR . '/nette/Nette/loader.php';

Debugger::enable();

$configurator = new Nette\Configurator;
$configurator->loadConfig(__DIR__ . '/config.neon');

// Configure application
$application = $configurator->container->application;
$application->errorPresenter = 'Error';
//$application->catchExceptions = TRUE;


// Setup application router
$router = $application->getRouter();

$router[] = new Nette\Application\Routers\SimpleRouter(array(
	'presenter' => 'Homepage',
	'action' => 'default',
	'id' => NULL,
));


// Run the application!
$application->run();
