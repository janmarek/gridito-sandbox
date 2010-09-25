<?php

namespace Model;

/**
 * User
 *
 * @Entity
 *
 * @author Jan Marek
 * @table(name="users")
 */
class User extends \Nette\Object
{
	// <editor-fold defaultstate="collapsed" desc="variables">

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

	/**
	 * @var string
	 * @Column
	 */
	private $name;

	/**
	 * @var string
	 * @Column
	 */
	private $surname;

	/**
	 * @var string
	 * @Column(unique=true)
	 */
	private $mail;
	
	/**
	 * @var bool
	 * @Column(type="boolean")
	 */
	private $active;

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="getters & setters">

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



	public function getName()
	{
		return $this->name;
	}



	public function setName($name)
	{
		$this->name = $name;
	}



	public function getSurname()
	{
		return $this->surname;
	}



	public function setSurname($surname)
	{
		$this->surname = $surname;
	}



	public function getMail()
	{
		return $this->mail;
	}



	public function setMail($mail)
	{
		$this->mail = $mail;
	}



	public function getActive()
	{
		return $this->active;
	}



	public function setActive($active)
	{
		$this->active = $active;
	}

	// </editor-fold>
}