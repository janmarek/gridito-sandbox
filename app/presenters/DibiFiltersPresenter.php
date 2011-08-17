<?php

use Nette\Application\UI\Form;

/**
 * Dibi datagrid with filters example presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class DibiFiltersPresenter extends BasePresenter
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

		$db = $this->context->DibiConnection;
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
		$grid->addColumn("username", "Username")->setSortable(true);
		$grid->addColumn("name", "Name")->setSortable(true);
		$grid->addColumn("surname", "Surname")->setSortable(true);
		$grid->addColumn("mail", "E-mail", array(
			"renderer" => function ($row) {
				echo Nette\Utils\Html::el("a")->href("mailto:$row->mail")->setText($row->mail);
			},
			"sortable" => true,
		));
		$grid->addColumn("active", "Active", array(
			"renderer" => function ($row) {
				Gridito\Column::renderBoolean($row->active);
			},
			"sortable" => true,
		));
	}



	protected function createComponentFilters($name)
	{
		$form = new Form($this, $name);
		$form->addText("search", "Search by")
			->setDefaultValue($this->getParam("search", ""));
		$form->addCheckbox("activeOnly", "Active users only")
			->setDefaultValue($this->getParam("activeOnly"));
		$form->addSubmit("s", "Filter");
		$form->onSubmit[] = array($this, "filters_submit");
	}



	public function filters_submit($form)
	{
		$this->redirect("default", (array) $form->getValues());
	}

}
