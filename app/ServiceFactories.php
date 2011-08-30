<?php

/**
 * Service factories
 *
 * @author Jan Marek
 */
class ServiceFactories
{
	public static function createDibiConnection()
	{
		return new DibiConnection(array(
			"driver" => "sqlite3",
			"file" => APP_DIR . "/models/users.s3db",
		));
	}



	public static function createEntityManager()
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


	public static function createDoctrineUsersModel($container)
	{
		$em = $container->entityManager;
		$qb = $em->getRepository('Model\User')->createQueryBuilder('u');
		/* @var $qb \Doctrine\ORM\QueryBuilder */
		$qb->leftJoin('u.credentials', 'c');
		$qb->addSelect('c');
		$model = new Gridito\DoctrineQueryBuilderModel($qb);

		return $model;
	}

}