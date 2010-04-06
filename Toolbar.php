<?php

namespace Gridito;

use Nette\ComponentContainer;

/**
 * Toolbar
 *
 * @author Jan Marek
 * @license MIT
 */
class Toolbar extends ComponentContainer {

	/** @var int */
	private $buttonCount = 0;


	/**
	 * Is empty
	 * @return bool
	 */
	public function isEmpty() {
		return count($this->getComponents()) === 0;
	}


	/**
	 * Render toolbar buttons
	 * @param mixed $record
	 */
	public function render($record = null) {
		foreach ($this->getComponents() as $component) {
			$component->render($record);
		}
	}


	/**
	 * Add button
	 * @param string $label
	 * @param callback $actionHandler
	 * @param string $icon
	 * @return Button
	 */
	public function addButton($label, $actionHandler, $icon = null) {
		$button = new Button($this, ++$this->buttonCount);
		$button->setLabel($label);
		$button->setHandler($actionHandler);
		if ($icon) $button->setIcon($icon);
		return $button;
	}


	/**
	 * Add window button
	 * @param string $label
	 * @param callback $actionHandler
	 * @param string $icon
	 * @return WindowButton
	 */
	public function addWindowButton($label, $actionHandler, $icon = null) {
		$button = new WindowButton($this, ++$this->buttonCount);
		$button->setLabel($label);
		$button->setHandler($actionHandler);
		if ($icon) $button->setIcon($icon);
		return $button;
	}

}