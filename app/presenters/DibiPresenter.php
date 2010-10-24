<?php

use Nette\Application\AppForm;

/**
 * Dibi datagrid presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class DibiPresenter extends BasePresenter
{
	/**
	 * @var bool
	 * @persistent
	 */
	public $activeOnly;

	/**
	 * @var string
	 * @persistent
	 */
	public $search;

	public function renderDefault()
	{
		$this->template->filters = $this["filters"];
	}



	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		// dibi connection
		$db = new DibiConnection(array(
			"driver" => "sqlite3",
			"file" => APP_DIR . "/models/users.s3db",
		));

		// model
		$fluent = $db->select("*")->from("users");

		// filters
		$activeOnly = $this->getParam("activeOnly", false);
		if ($activeOnly) {
			$fluent->where("active = %b", $activeOnly);
		}
		$search = $this->getParam("search", false);
		if ($search) {
			$searchString = "%$search%";
			$fluent->where(
				"(username like %s or name like %s or surname like %s or mail like %s)",
				$searchString, $searchString, $searchString, $searchString
			);
		}

		$grid->setModel(new Gridito\DibiFluentModel($fluent));

		// columns
		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true);
		$grid->addColumn("name", "Jméno")->setSortable(true);
		$grid->addColumn("surname", "Příjmení")->setSortable(true);
		$grid->addColumn("mail", "E-mail", array(
			"renderer" => function ($row) {
				echo Nette\Web\Html::el("a")->href("mailto:$row->mail")->setText($row->mail);
			},
			"sortable" => true,
		));
		$grid->addColumn("active", "Aktivní", array(
			"renderer" => function ($row) {
				Gridito\Column::renderBoolean($row->active);
			},
			"sortable" => true,
		));

		// buttons
		$grid->addButton("button", "Tlačítko")->setHandler(function ($id) use ($grid) {
			$grid->flashMessage("Stisknuto tlačítko na řádku $id");
			$grid->redirect("this");
		});
	}



	protected function createComponentFilters($name)
	{
		$form = new AppForm($this, $name);
		$form->addText("search", "Hledaný výraz")
			->setDefaultValue($this->getParam("search", ""));
		$form->addCheckbox("activeOnly", "Pouze aktivní uživatelé")
			->setDefaultValue($this->getParam("activeOnly", false));
		$form->addSubmit("s", "Filtrovat");
		$form->onSubmit[] = array($this, "filters_submit");
	}



	public function filters_submit($form)
	{
		$this->redirect("default", $form->getValues());
	}

}
