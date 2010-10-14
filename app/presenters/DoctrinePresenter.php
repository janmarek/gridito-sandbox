<?php

/**
 * Dibi datagrid presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class DoctrinePresenter extends BasePresenter
{
	// <editor-fold defaultstate="collapsed" desc="entity manager">

	private $em;

	protected function getEntityManager()
	{
		if (empty($this->em)) {
			$config = new Doctrine\ORM\Configuration;

			// annotations
			$annotationDriver = $config->newDefaultAnnotationDriver(APP_DIR . '/models');
			$config->setMetadataDriverImpl($annotationDriver);
			$config->setProxyNamespace('GriditoExample\Doctrine\Proxy');
			$config->setProxyDir(APP_DIR . '/temp');

			// cache
			$cache = new Doctrine\Common\Cache\ArrayCache;
			$config->setMetadataCacheImpl($cache);
			$config->setQueryCacheImpl($cache);

			// entity manager
			$this->em = Doctrine\ORM\EntityManager::create(array(
				"driver" => "pdo_sqlite",
				"path" => APP_DIR . "/models/users.s3db",
			), $config);
		}

		return $this->em;
	}
	
	// </editor-fold>

	

	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		// model
		$grid->setModel(new Gridito\DoctrineModel($this->getEntityManager(), "Model\User"));

		// columns
		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true);
		$grid->addColumn("name", "Jméno")->setSortable(true);
		$grid->addColumn("surname", "Příjmení")->setSortable(true);
		$grid->addColumn("mail", "Mail", array(
			"sortable" => true,
			"renderer" => function ($row) {
				echo Nette\Web\Html::el("a")->href("mailto:$row->mail")->setText($row->mail);
			}
		));
		$grid->addColumn("active", "Aktivní")->setSortable(true);

		// buttons
		$grid->addButton("button", "Tlačítko", array(
			"icon" => "plusthick",
			"handler" => function ($id) use ($grid) {
				$grid->flashMessage("Stisknuto tlačítko na řádku $id");
				$grid->redirect("this");
			}
		));
	}

}
