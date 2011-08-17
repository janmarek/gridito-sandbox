<?php

abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
	protected function afterRender()
	{
		if (!$this->getSession()->isStarted()) {
			$this->getSession()->start();
		}
	}
}
