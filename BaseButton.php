<?php

namespace Gridito;

use Nette\Application\PresenterComponent;
use Nette\Application\ForbiddenRequestException;
use Nette\Web\Html;

/**
 * Button base
 *
 * @author Jan Marek
 * @license MIT
 */
abstract class BaseButton extends PresenterComponent {

	// <editor-fold defaultstate="collapsed" desc="variables">

	/** @var string */
	private $label;

	/** @var callback */
	private $handler;

	/** @var string */
	private $icon;
	
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="getters & setters">

	/**
	 * Get label
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}


	/**
	 * Set label
	 * @param string $label
	 * @return BaseButton
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}



	public function getHandler() {
		return $this->handler;
	}

	public function setHandler($cb) {
		$this->handler = $cb;
	}

	public function getIcon() {
		return $this->icon;
	}

	public function setIcon($icon) {
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @return Grid
	 */
	public function getGrid() {
		return $this->getParent()->getParent();
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="signals">

	public function handleClick($token, $pk = null) {
		$grid = $this->getGrid();

		if ($token !== $this->getGrid()->getSecurityToken()) {
			throw new ForbiddenRequestException("Security token does not match. Possible CSRF attack.");
		}

		call_user_func($this->handler, $grid->getModel()->processActionParam($pk));
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="rendering">
	
	protected function getButtonLink($row) {
		$grid = $this->getGrid();

		$params["token"] = $grid->getSecurityToken();

		if ($row) {
			$params["pk"] = $row->{$grid->getPrimaryKey()};
		}

		return $this->link("click!", $params);
	}

	protected function createButton($row = null) {
		$el = Html::el("a")
			->href($this->getButtonLink($row))
			->setText($this->label);

		if ($this->icon) {
			$el->icon("ui-icon-" . $this->icon);
		}

		return $el;
	}

	public function render($row = null) {
		echo $this->createButton($row);
	}

	// </editor-fold>

}