<?php

namespace Gridito;

use Nette\ComponentContainer, Nette\Environment, Nette\Paginator;

/**
 * Grid
 *
 * @author Jan Marek
 * @license MIT
 */
class Grid extends \Nette\Application\Control
{
	// <editor-fold defaultstate="collapsed" desc="variables">

	/** @var IModel */
	private $model;

	/** @var string */
	private $primaryKey = "id";

	/** @var Paginator */
	private $paginator;

	/** @var int */
	private $defaultItemsPerPage = 20;

	/**
	 * @var int
	 * @persistent
	 */
	public $page = 1;

	/**
	 * @var string
	 * @persistent
	 */
	public $sortColumn = null;

	/**
	 * @var string
	 * @persistent
	 */
	public $sortType = null;

	/** @var string */
	private $ajaxClass = "ajax";

	/** @var int */
	private $toolbarButtonId = 0;

	/** @var int */
	private $actionButtonId = 0;

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="constructor">

	public function __construct(\Nette\IComponentContainer $parent = null, $name = null)
	{
		parent::__construct($parent, $name);

		$this->addComponent(new ComponentContainer, "toolbar");
		$this->addComponent(new ComponentContainer, "actions");
		$this->addComponent(new ComponentContainer, "columns");
		
		$this->paginator = new Paginator;
		$this->paginator->setItemsPerPage($this->defaultItemsPerPage);

	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="getters & setters">

	/**
	 * Get model
	 * @return IModel
	 */
	public function getModel()
	{
		return $this->model;
	}


	
	/**
	 * Set model
	 * @param IModel model
	 * @return Grid
	 */
	public function setModel(IModel $model)
	{
		$model->setupGrid($this);
		$this->getPaginator()->setItemCount(count($model));
		$this->model = $model;
		return $this;
	}



	/**
	 * Get primary key name
	 * @return string
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}



	/**
	 * Set primary key name
	 * @param string primary key name
	 * @return Grid
	 */
	public function setPrimaryKey($primaryKey)
	{
		$this->primaryKey = $primaryKey;
		return $this;
	}



	/**
	 * Get items per page
	 * @return int
	 */
	public function getItemsPerPage()
	{
		return $this->getPaginator()->getItemsPerPage();
		return $this;
	}



	/**
	 * Set items per page
	 * @param int items per page
	 * @return Grid
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->getPaginator()->setItemsPerPage($itemsPerPage);
		return $this;
	}



	/**
	 * Get ajax class
	 * @return string
	 */
	public function getAjaxClass()
	{
		return $this->ajaxClass;
	}



	/**
	 * Set ajax class
	 * @param string ajax class
	 * @return Grid
	 */
	public function setAjaxClass($ajaxClass)
	{
		$this->ajaxClass = $ajaxClass;
		return $this;
	}



	/**
	 * Get paginator
	 * @return Nette\Paginator
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}



	/**
	 * Get security token
	 * @return string
	 */
	public function getSecurityToken()
	{
		$session = Environment::getSession(__CLASS__ . "-" . __METHOD__);

		if (empty($session->securityToken)) {
			$session->securityToken = md5(uniqid(mt_rand(), true));
		}

		return $session->securityToken;
	}



	/**
	 * Has toolbar
	 * @return bool
	 */
	public function hasToolbar()
	{
		return count($this["toolbar"]->getComponents()) > 0;
	}



	/**
	 * Has actions
	 * @return bool
	 */
	public function hasActions()
	{
		return count($this["actions"]->getComponents()) > 0;
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="signals">

	/**
	 * Handle change page signal
	 * @param int $page
	 */
	public function handleChangePage($page)
	{
		if ($this->presenter->isAjax()) {
			$this->invalidateControl();
		}
	}



	public function handleSort($sortColumn, $sortType)
	{
		if ($this->presenter->isAjax()) {
			$this->invalidateControl();
		}
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="rendering">

	/**
	 * Render grid
	 */
	public function render()
	{
		$this->paginator->setPage($this->page);
		$this->model->setLimit($this->paginator->getOffset(), $this->paginator->getLength());

		if ($this->sortColumn && $this["columns"][$this->sortColumn]->isSortable()) {
			$this->model->setSorting($this->sortColumn, $this->sortType);
		}

		$this->template->render();
	}
	
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="component factories">

	/**
	 * Add column
	 * @param string name
	 * @param string label
	 * @param callback renderer
	 * @return Column
	 */
	public function addColumn($name, $label, $renderer = null)
	{
		$column = new Column($this["columns"], $name);
		$column->setLabel($label);
		if ($renderer) $column->setCellRenderer($renderer);
		return $column;
	}



	/**
	 * Add action button
	 * @param string button name
	 * @param callback handler
	 * @param string jQuery UI icon
	 * @return Button
	 */
	public function addButton($label, $handler, $icon = null)
	{
		$button = $this->createButton("\Gridito\Button", $label, $handler, $icon);
		$this["actions"]->addComponent($button, ++$this->actionButtonId);
		return $button;
	}



	/**
	 * Add action window button
	 * @param string button name
	 * @param callback handler
	 * @param string jQuery UI icon
	 * @return WindowButton
	 */
	public function addWindowButton($label, $handler, $icon = null)
	{
		$button = $this->createButton("\Gridito\WindowButton", $label, $handler, $icon);
		$this["actions"]->addComponent($button, ++$this->actionButtonId);
		return $button;
	}



	/**
	 * Add action button to toolbar
	 * @param string button name
	 * @param callback handler
	 * @param string jQuery UI icon
	 * @return Button
	 */
	public function addToolbarButton($label, $handler, $icon = null)
	{
		$button = $this->createButton("\Gridito\Button", $label, $handler, $icon);
		$this["toolbar"]->addComponent($button, ++$this->toolbarButtonId);
		return $button;
	}



	/**
	 * Add window button to toolbar
	 * @param string button name
	 * @param callback handler
	 * @param string jQuery UI icon
	 * @return WindowButton
	 */
	public function addToolbarWindowButton($label, $handler, $icon = null)
	{
		$button = $this->createButton("\Gridito\WindowButton", $label, $handler, $icon);
		$this["toolbar"]->addComponent($button, ++$this->toolbarButtonId);
		return $button;
	}

	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="helpers">

	/**
	 * Create template
	 * @return Template
	 */
	protected function createTemplate()
	{
		return parent::createTemplate()->setFile(__DIR__ . "/templates/grid.phtml");
	}



	/**
	 * Set page
	 * @param int page
	 */
	private function setPage($page)
	{
		$paginator = $this->getPaginator();
		$paginator->setPage($page);
		$this->model->setLimit($paginator->getOffset(), $paginator->getLength());
	}



	/**
	 * Create button
	 * @param string button class name
	 * @param string label
	 * @param callback handler
	 * @param string icon
	 * @return BaseButton
	 */
	private function createButton($class, $label, $handler, $icon = null)
		{
		$button = new $class;
		$button->setLabel($label);
		$button->setHandler($handler);
		if ($icon) $button->setIcon($icon);
		return $button;
	}

	// </editor-fold>
	
}