<?php

/**
 * Simple Doctrine example presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class DoctrineSimplePresenter extends BasePresenter
{
	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		// model
		$em = Nette\Environment::getService("Doctrine\ORM\EntityManager");
		$qb = $em->getRepository("Model\User")->createQueryBuilder("u");
		$grid->setModel(new Gridito\DoctrineQueryBuilderModel($qb));

		$grid->setItemsPerPage(5);

		// columns
		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("username", "Username")->setSortable(true);
		$grid->addColumn("name", "Name")->setSortable(true);
		$grid->addColumn("surname", "Surname")->setSortable(true);
		$grid->addColumn("mail", "E-mail", array(
			"sortable" => true,
			"renderer" => function ($row) {
				echo Nette\Web\Html::el("a")->href("mailto:$row->mail")->setText($row->mail);
			}
		));
		$grid->addColumn("active", "Active")->setSortable(true);
	}

}
