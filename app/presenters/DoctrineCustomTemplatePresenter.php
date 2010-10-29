<?php

/**
 * Doctrine custom template
 *
 * @author Jan Marek
 */
class DoctrineCustomTemplatePresenter extends BasePresenter
{
	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		$qb = Nette\Environment::getService("Doctrine\ORM\EntityManager")->getRepository("Model\User")->createQueryBuilder("u");
		$grid->setModel(new Gridito\DoctrineQueryBuilderModel($qb));

		// columns
		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true);
		$grid->addColumn("name", "Jméno")->setSortable(true);
		$grid->addColumn("surname", "Příjmení")->setSortable(true);
		$grid->addColumn("mail", "E-mail")->setSortable(true);
		$grid->addColumn("active", "Aktivní")->setSortable(true);

		$grid->setItemsPerPage(3);

		// buttons
		$grid->addButton("button", "Tlačítko", array(
			"icon" => "ui-icon-plusthick",
			"confirmationQuestion" => function ($row) {
				return "Opravdu stisknout tlačítko u uživatele $row->name $row->surname?";
			},
			"handler" => function ($row) use ($grid) {
				$grid->flashMessage("Stisknuto tlačítko na řádku $row->name $row->surname");
				$grid->redirect("this");
			}
		));

		$grid->getTemplate()->setFile(__DIR__ . "/../templates/DoctrineCustomTemplate/@grid.phtml");
	}
}