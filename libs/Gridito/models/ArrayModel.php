<?php

namespace Gridito;

/**
 * Array model (experimental)
 *
 * @author Jan Marek
 * @license MIT
 */
class ArrayModel extends \Nette\Object implements IModel
{
	private $pk = "__gridito_array_model_id";

	private $data;

	private $sortColumn;

	private $sortType;

	private $offset;

	private $limit;


	public function __construct(array $data)
	{
		for ($i = 0; $i < count($data); $i++) {
			$o = $data[$i];
			$o[$this->pk] = $i;
			$this->data[$i] = (object) $o;
		}
	}


	public function count()
	{
		return count($this->data);
	}



	public function getIterator()
	{
		$data = $this->data;

		// sorting
		if ($this->sortColumn) {
			$column = $this->sortColumn;
			$type = $this->sortType;
			usort($data, function ($a, $b) use ($column, $type) {
				if ($a->$column == $b->$column) {
					return 0;
				}

				if ($a->$column < $b->$column) {
					return $type === IModel::ASC ? -1 : 1;
				} else {
					return $type === IModel::ASC ? 1 : -1;
				}
			});
		}

		// paging
		array_slice($data, $this->offset, $this->limit, true);

		return new \ArrayIterator($data);
	}



	public function processActionParam($param)
	{
		return $this->data[$param];
	}



	public function setLimit($offset, $limit)
	{
		$this->offset = $offset;
		$this->limit = $limit;
	}



	public function setSorting($column, $type)
	{
		$this->sortColumn = $column;
		$this->sortType = $type;
	}



	public function setupGrid(Grid $grid)
	{
		$grid->setPrimaryKey($this->pk);
	}

}