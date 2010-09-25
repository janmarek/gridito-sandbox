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


	public function templatePrepareFilters($template)
	{
		$latteFilter = new Nette\Templates\LatteFilter;
		Gridito\TemplateMacros::register($latteFilter->getHandler());
		$template->registerFilter($latteFilter);
	}

	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		// model
		$grid->setModel(new Gridito\DoctrineModel($this->getEntityManager(), "Model\User"));

		// columns
		$grid->addColumn("id");
		$grid->addColumn("username");
		$grid->addColumn("name");
		$grid->addColumn("surname");
		$grid->addColumn("mail")->setCellRenderer(function ($row) {
			echo Nette\Web\Html::el("a")->href("mailto:$row->mail")->setText($row->mail);
		});
		$grid->addColumn("active");

		// buttons
		$grid->addButton("Tlačítko", function ($id) use ($grid) {
			$grid->flashMessage("Stisknuto tlačítko na řádku $id");
			$grid->redirect("this");
		});
	}

}
