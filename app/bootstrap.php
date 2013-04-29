<?php

use Nette\Diagnostics\Debugger;

require LIBS_DIR . '/Nette/Nette/loader.php';

Debugger::enable();

if (!file_exists(TEMP_DIR)) {
	throw new Exception(sprintf('Temp dir "%s" does not exist', TEMP_DIR));
}

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
