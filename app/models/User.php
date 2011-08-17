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
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

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

	/**
	 * @var \Model\Credentials
	 * @OneToOne(targetEntity="Credentials")
	 */
	private $credentials;



	public function getId()
	{
		return $this->id;
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



	public function isActive()
	{
		return $this->active;
	}



	public function setActive($active)
	{
		$this->active = $active;
	}



	public function getCredentials()
	{
		return $this->credentials;
	}

}