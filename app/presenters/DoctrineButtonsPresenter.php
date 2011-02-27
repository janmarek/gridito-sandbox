<?php

/**
 * Buttons example presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class DoctrineButtonsPresenter extends BasePresenter
{
	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		// model
		$em = Nette\Environment::getService("Doctrine\ORM\EntityManager");
		$model = new Model\UsersGriditoDoctrineModel($em);
		$grid->setModel($model);

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

		// toolbar buttons
		$grid->addToolbarButton("create", "Create new user", array(
			"handler" => function () use ($grid) {
				$grid->flashMessage("Unable to create user.", "error");
				$grid->redirect("this");
			},
			"icon" => "ui-icon-plusthick",
		));
		
		$grid->addToolbarWindowButton("hello", "New window")->setHandler(function () {
			echo "Hello!";
		})->setIcon("ui-icon-newwin");

		$grid->addToolbarButton("back", "Go back to examples", array(
			"link" => $this->link("Homepage:"),
			"icon" => "ui-icon-home",
		));

		$grid->addWindowButton("detail", "Detail", array(
			"handler" => function ($user) {
				echo "<p><strong>$user->name $user->surname</strong></p>";
				echo "<table>";
				echo "<tr><th>ID</th><td>$user->id</td></tr>";
				echo "<tr><th>Username</th><td>$user->username</td></tr>";
				echo "<tr><th>E-mail</th><td>$user->mail</td></tr>";
				echo "<tr><th>Active</th><td>" . ($user->active ? "yes" : "no") . "</td></tr>";
				echo "</table>";
			},
			"icon" => "ui-icon-search",
		));

		// action buttons
		$grid->addButton("delete", "Delete", array(
			"handler" => function ($user) use ($grid) {
				$grid->flashMessage("Unable to delete user $user->name $user->surname.", "error");
				$grid->redirect("this");
			},
			"icon" => "ui-icon-closethick",
			"confirmationQuestion" => function ($user) {
				if ($user->active) {
					return "Really delete use $user->name $user->surname?";
				} else {
					return null;
				}
			},
			"showText" => false,
			"visible" => function ($user) {
				return !$user->isActive();
			},
		));
	}

}
