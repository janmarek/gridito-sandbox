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
	public $activeOnly = false;

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

		$db = Nette\Environment::getService("DibiConnection");
		$model = new Model\UsersGriditoDibiModel($db);

		if ($this->getParam("activeOnly")) {
			$model->filterActiveOnly();
		}

		$search = $this->getParam("search", false);
		if ($search) {
			$model->filterSearch($search);
		}

		$grid->setModel($model);

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
		$grid->addButton("button", "Tlačítko", array(
			"icon" => "ui-icon-plusthick",
			"confirmationQuestion" => function ($row) {
				return "Opravdu stisknout u uživatele $row->name $row->surname?";
			},
			"handler" => function ($row) use ($grid) {
				$grid->flashMessage("Stisknuto tlačítko na řádku $row->name $row->surname");
				$grid->redirect("this");
			}
		));
	}



	protected function createComponentFilters($name)
	{
		$form = new AppForm($this, $name);
		$form->addText("search", "Hledaný výraz")
			->setDefaultValue($this->getParam("search", ""));
		$form->addCheckbox("activeOnly", "Pouze aktivní uživatelé")
			->setDefaultValue($this->getParam("activeOnly"));
		$form->addSubmit("s", "Filtrovat");
		$form->onSubmit[] = array($this, "filters_submit");
	}



	public function filters_submit($form)
	{
		$this->redirect("default", $form->getValues());
	}

}
