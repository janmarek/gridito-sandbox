<?php

/**
 * Doctrine custom template
 *
 * @author Jan Marek
 * @license MIT
 */
class DoctrineCustomTemplatePresenter extends BasePresenter
{
	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		$em = $this->context->getService("Doctrine\ORM\EntityManager");
		$model = new Model\UsersGriditoDoctrineModel($em);
		$grid->setModel($model);

		// columns
		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("username", "Username")->setSortable(true);
		$grid->addColumn("name", "Name")->setSortable(true);
		$grid->addColumn("surname", "Surname")->setSortable(true);
		$grid->addColumn("mail", "E-mail")->setSortable(true);
		$grid->addColumn("active", "Active")->setSortable(true);

		$grid->setItemsPerPage(5);

		// buttons
		$grid->addButton("button", "Button", array(
			"icon" => "ui-icon-plusthick",
			"handler" => function ($row) use ($grid) {
				$grid->flashMessage("Button $row->name $row->surname pressed.");
				$grid->redirect("this");
			}
		));

		$grid->getTemplate()->setFile(__DIR__ . "/../templates/DoctrineCustomTemplate/@grid.phtml");
	}
}