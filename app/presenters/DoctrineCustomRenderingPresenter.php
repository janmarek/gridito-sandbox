<?php

/**
 * DibiCustomRendering
 *
 * @author Jan Marek
 */
class DoctrineCustomRenderingPresenter extends BasePresenter
{
	protected function createComponentGrid($name)
	{
		$grid = new Gridito\Grid($this, $name);

		$qb = Nette\Environment::getService("Doctrine\ORM\EntityManager")->getRepository("Model\User")->createQueryBuilder("u");
		$grid->setModel(new Gridito\DoctrineQueryBuilderModel($qb));

		$grid->setRowClass(function ($iterator, $row) {
			$classes = array();
			$classes[] = $iterator->isOdd() ? "odd" : "even";
			if (!$row->active) $classes[] = "inactive";
			return empty($classes) ? null : implode(" ", $classes);
		});

		// columns
		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true)->setCellClass("important");
		$grid->addColumn("name", "Jméno")->setSortable(true);
		$grid->addColumn("surname", "Příjmení")->setSortable(true);
		$grid->addColumn("mail", "E-mail", array(
			"renderer" => function ($row) {
				echo Nette\Web\Html::el("a")->href("mailto:$row->mail")->setText($row->mail);
			},
			"sortable" => true,
		));
		$grid->addColumn("active", "Aktivní")->setSortable(true);

		// buttons
		$grid->addButton("button", "Tlačítko", array(
			"icon" => "icon-tick",
			"confirmationQuestion" => function ($row) {
				return "Opravdu stisknout u uživatele $row->name $row->surname?";
			},
			"handler" => function ($row) use ($grid) {
				$grid->flashMessage("Stisknuto tlačítko na řádku $row->name $row->surname");
				$grid->redirect("this");
			}
		));

		$grid->addWindowButton("winbtn", "Okno", array(
			"handler" => function ($row) {
				echo "$row->name $row->surname<br>($row->mail)";
			},
			"icon" => "icon-window",
		));
	}
}