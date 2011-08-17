<?php

/**
 * Dibi datagrid presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class DibiSimplePresenter extends BasePresenter
{
	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		$db = $this->context->dibiConnection;
		$grid->setModel(new Gridito\DibiFluentModel($db->select('*')->from('users')));

		$grid->setItemsPerPage(5);

		// columns
		$grid->addColumn('id', 'ID')->setSortable(true);
		$grid->addColumn('name', 'Name')->setSortable(true);
		$grid->addColumn('surname', 'Surname')->setSortable(true);
		$grid->addColumn('mail', 'E-mail', array(
			'renderer' => function ($row) {
				echo Nette\Utils\Html::el('a')->href("mailto:$row->mail")->setText($row->mail);
			},
			'sortable' => true,
		));
		$grid->addColumn('active', 'Active', array(
			'renderer' => function ($row) {
				Gridito\Column::renderBoolean($row->active);
			},
			'sortable' => true,
		));
	}

}
