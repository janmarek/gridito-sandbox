<?php

namespace Gridito;

use Nette\Templates\LatteMacros, Nette\Templates\LatteFilter;

/**
 * TemplateMacros
 *
 * @author Jan Marek
 */
class TemplateMacros extends \Nette\Object
{
	private static $grid;



	public static function register(LatteMacros $macros)
	{
		$macros->macros["gridito"] = '<?php %Gridito\TemplateMacros::macroBegin% ?>';
		$macros->macros["griditocolumn"] = '<?php %Gridito\TemplateMacros::macroColumn% ?>';
		$macros->macros["/gridito"] = '<?php Gridito\TemplateMacros::end() ?>';
	}



	public static function macroBegin($content)
	{
		$name = LatteMacros::formatString(LatteMacros::fetchToken($content));
		return "Gridito\TemplateMacros::begin($name, \$control)";
	}



	public static function begin($grid, $control)
	{
		if ($grid instanceof Grid) {
			self::$grid = $grid;
		} else {
			self::$grid = $control[$grid];
		}
	}



	public static function macroColumn($content)
	{
		$name = LatteMacros::formatString(LatteMacros::fetchToken($content));
		$options = LatteMacros::formatArray($content);
		return "Gridito\TemplateMacros::column($name, $options)";
	}



	public static function column($name, $options)
	{
		$columns = self::$grid["columns"];

		if (isset($options["text"])) {
			$columns->getComponent($name)->setLabel($options["text"]);
		}
		if (isset($options["sortable"])) {
			$columns->getComponent($name)->setSortable($options["sortable"]);
		}
	}


	
	public static function end()
	{
		self::$grid->render();
	}
}