<?php

/**
 * Service factories
 *
 * @author Jan Marek
 */
class ServiceFactories
{
	public function createDibiConnection()
	{
		return new DibiConnection(array(
			"driver" => "sqlite3",
			"file" => APP_DIR . "/models/users.s3db",
		));
	}


	
	public function createEntityManager()
	{
		$config = new Doctrine\ORM\Configuration;

		// annotations
		$annotationDriver = $config->newDefaultAnnotationDriver(APP_DIR . '/models');
		$config->setMetadataDriverImpl($annotationDriver);
		$config->setProxyNamespace('GriditoExample\Doctrine\Proxy');
		$config->setProxyDir(TEMP_DIR . '/cache');

		// cache
		$cache = new Doctrine\Common\Cache\ArrayCache;
		$config->setMetadataCacheImpl($cache);
		$config->setQueryCacheImpl($cache);

		// entity manager
		return Doctrine\ORM\EntityManager::create(array(
			"driver" => "pdo_sqlite",
			"path" => APP_DIR . "/models/users.s3db",
		), $config);
	}

}