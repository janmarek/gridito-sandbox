<?php

/**
 * Dibi datagrid presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class DoctrinePresenter extends BasePresenter
{

	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		// model
		$qb = Nette\Environment::getService("Doctrine\ORM\EntityManager")->getRepository("Model\User")->createQueryBuilder("u");
		$grid->setModel(new Gridito\DoctrineQueryBuilderModel($qb));

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
			"icon" => "ui-icon-plusthick",
			"handler" => function ($row) use ($grid) {
				$grid->flashMessage("Stisknuto tlačítko na řádku $row->id");
				$grid->redirect("this");
			}
		));
	}

}
