<?php

/**
 * Homepage presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class HomepagePresenter extends BasePresenter
{
	protected function createComponentDibiGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		// dibi connection
		$db = new DibiConnection(array(
			"driver" => "sqlite3",
			"file" => APP_DIR . "/models/users.s3db",
		));

		// model
		$grid->setModel(new Gridito\DibiFluentModel($db->select("*")->from("users")));

		// columns
		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true);
		$grid->addColumn("name", "Jméno")->setSortable(true);
		$grid->addColumn("surname", "Příjmení")->setSortable(true);
		$grid->addColumn("mail", "E-mail", function ($row) {
			echo Nette\Web\Html::el("a")->href("mailto:$row->mail")->setText($row->mail);
		})->setSortable(true);

		// buttons
		$grid->addButton("Tlačítko", function ($id) use ($grid) {
			$grid->flashMessage("Stisknuto tlačítko na řádku $id");
			$grid->redirect("this");
		});
	}

}
