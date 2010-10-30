<?php

namespace Model;

/**
 * Users gridito dibi model
 *
 * @author Jan Marek
 */
class UsersGriditoDibiModel extends \Gridito\DibiFluentModel
{
	public function __construct(\DibiConnection $db)
	{
		parent::__construct($db->select("*")->from("users"));
	}



	public function filterActiveOnly()
	{
		$this->fluent->where("active = 1");
	}



	public function filterSearch($search)
	{
		$searchString = "%$search%";
		$this->fluent->where(
			"(username like %s or name like %s or surname like %s or mail like %s)",
			$searchString, $searchString, $searchString, $searchString
		);
	}

}