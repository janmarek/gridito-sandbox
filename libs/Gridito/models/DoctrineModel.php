<?php

namespace Gridito;

/**
 * Doctrine model
 *
 * @author Martin Sadový
 * @license MIT
 */
class DoctrineModel extends Nette\Object implements IModel
{
	// <editor-fold defaultstate="collapsed" desc="variables">

	/**
	 * @var string|null
	 */
	protected $primaryKey;

	/**
	 * @var Doctrine\ORM\QueryBuilder
	 */
	protected $query;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	// </editor-fold>



	/**
	 * @param Doctrine\ORM\EntityManager Entity manager
	 * @param string entity name
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em, $entity)
	{
		if (!is_string($entity)) {
			throw new \InvalidArgumentException("Argument must be string, " . gettype($entity) . " given.");
		} elseif (!class_exists($entity)) {
			throw new \InvalidArgumentException("Class (Entity) not found!");
		}

		$this->query = $em->createQueryBuilder()->from($entity, 'e');
		
		try {
			$this->primaryKey = $em->getClassMetadata($entity)->getSingleIdentifierFieldName();
		} catch (\Doctrine\ORM\Mapping\MappingException $e) {

		}
	}



	/**
	 * Get primary key
	 * @return string
	 */
	public function getPrimaryKey()
	{
		if ($this->primaryKey === NULL) {
			throw new \InvalidStateException("Set first primary key!");
		}
		return $this->primaryKey;
	}



	/**
	 * Set primary key
	 * @param string key name
	 */
	public function setPrimaryKey($key)
	{
		if (!is_string($key)) {
			throw new \InvalidArgumentException("Argument must be string, " . gettype($entity) . " given.");
		}
		$this->primaryKey = $key;
	}



	/**
	 * Get query builder
	 * @return Doctrine\ORM\QueryBuilder
	 */
	public function getQuery()
	{
		return $this->query;
	}



	/**
	 * @param int Offset
	 * @param int Limit
	 */
	public function setLimit($offset, $limit)
	{
		if ($limit !== null) {
			$this->query->setMaxResults($limit);
		}
		
		if ($offset !== null) {
			$this->query->setFirstResult($offset);
		}
	}



	/**
	 * @param string Column name
	 * @param string ASC or DESC
	 */
	public function setSorting($column, $type)
	{
		$this->query->addOrderBy('e.' . $column, $type);
	}



	/**
	 * Process action param
	 * @param mixed primary value
	 * @return (Entity)
	 */
	public function processActionParam($param)
	{
		if ($param === null) {
			return null;
		}
		try {
			return $this->query->select('e')
				->where('e.' . $this->getPrimaryKey() . ' = :primaryValue')
				->setMaxResults(1)
				->setFirstResult(0)
				->setParameter('primaryValue', $param)
				->getQuery()
				->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return false;
			Debug::processException($e);
		}
	}



	/**
	 * Setup grid
	 * @param Gridito\Grid
	 */
	public function setupGrid(Grid $grid)
	{
		$grid->setPrimaryKey($this->getPrimaryKey());
	}



	/**
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->query->select('e')->getQuery()->getResult());
	}



	/**
	 * @return int
	 */
	public function count()
	{
		return $this->query->select('count(e) fullcount')->getQuery()->getSingleResult(\Doctrine\ORM\Query::HYDRATE_SINGLE_SCALAR);
	}

}