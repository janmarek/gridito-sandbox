<?php

/**
 * Custom rendering example
 *
 * @author Jan Marek
 * @license MIT
 */
class DoctrineCustomRenderingPresenter extends BasePresenter
{
	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		$grid->setModel($this->context->doctrineUsersModel);

		$grid->setHighlightOrderedColumn(FALSE);

		$grid->setRowClass(function ($iterator, $row) {
			$classes = array();
			$classes[] = $iterator->isOdd() ? 'odd' : 'even';
			if (!$row->active) $classes[] = 'inactive';
			return empty($classes) ? null : implode(' ', $classes);
		});

		// columns
		$grid->addColumn('id', 'ID')->setSortable(true);
		$grid->addColumn('c.username', 'Username')->setSortable(true)->setCellClass('important');
		$grid->addColumn('name', 'Name')->setSortable(true);
		$grid->addColumn('surname', 'Surname')->setSortable(true);
		$grid->addColumn('mail', 'E-mail', array(
			'renderer' => function ($row) {
				echo Nette\Utils\Html::el('a')->href("mailto:$row->mail")->setText($row->mail);
			},
			'sortable' => true,
		));
		$grid->addColumn('active', 'Active')->setSortable(true);

		// buttons
		$grid->addButton('button', 'Button', array(
			'icon' => 'icon-tick',
			'handler' => function ($row) use ($grid) {
				$grid->flashMessage("Button $row->name $row->surname pressed.");
				$grid->redirect('this');
			}
		));

		$grid->addWindowButton('winbtn', 'Window', array(
			'handler' => function ($row) {
				echo "$row->name $row->surname<br>($row->mail)";
			},
			'icon' => 'icon-window',
		));
	}

}