<?php

/**
 * Homepage presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class HomepagePresenter extends BasePresenter
{
	// <editor-fold defaultstate="collapsed" desc="dataset">

	private $dataset = array(
		array(
			"username" => "jane",
			"mail" => "jane@example.com",
			"highscore" => 3,
			"active" => true,
		),
		array(
			"username" => "robin",
			"mail" => "robin@example.com",
			"highscore" => 30,
			"active" => false,
		),
		array(
			"username" => "adam",
			"mail" => "adam@example.com",
			"highscore" => 33,
			"active" => true,
		),
		array(
			"username" => "eve",
			"mail" => "evil.eve@example.com",
			"highscore" => 666,
			"active" => true,
		),
	);

	// </editor-fold>

	protected function createComponentUsersGrid($name)
	{
		$grid = new Gridito\Grid;

		$grid->setModel(new Gridito\ArrayModel($this->dataset));

		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true);
		$grid->addColumn("mail", "E-mail")->setSortable(true);
		$grid->addColumn("highscore", "Highscore")->setSortable(true);
		$grid->addColumn("active", "Aktivní")->setSortable(true);

		return $grid;
	}

}
