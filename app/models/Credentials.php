<?php

namespace Model;

/**
 * Credentials
 *
 * @Entity
 *
 * @author Jan Marek
 * @table(name="credentials")
 */
class Credentials extends \Nette\Object
{
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @var string
	 * @Column(unique=true)
	 */
	private $username;

	/**
	 * @var string
	 * @Column
	 */
	private $password;



	public function getId()
	{
		return $this->id;
	}



	public function getUsername()
	{
		return $this->username;
	}



	public function setUsername($username)
	{
		$this->username = $username;
	}



	public function getPassword()
	{
		return $this->password;
	}



	public function setPassword($password)
	{
		$this->password = $password;
	}

}