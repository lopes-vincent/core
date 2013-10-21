<?php

namespace Thelia\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\ProfileResource as ChildProfileResource;
use Thelia\Model\ProfileResourceQuery as ChildProfileResourceQuery;
use Thelia\Model\Map\ProfileResourceTableMap;

/**
 * Base class that represents a query for the 'profile_resource' table.
 *
 *
 *
 * @method     ChildProfileResourceQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildProfileResourceQuery orderByProfileId($order = Criteria::ASC) Order by the profile_id column
 * @method     ChildProfileResourceQuery orderByResourceId($order = Criteria::ASC) Order by the resource_id column
 * @method     ChildProfileResourceQuery orderByRead($order = Criteria::ASC) Order by the read column
 * @method     ChildProfileResourceQuery orderByWrite($order = Criteria::ASC) Order by the write column
 * @method     ChildProfileResourceQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildProfileResourceQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildProfileResourceQuery groupById() Group by the id column
 * @method     ChildProfileResourceQuery groupByProfileId() Group by the profile_id column
 * @method     ChildProfileResourceQuery groupByResourceId() Group by the resource_id column
 * @method     ChildProfileResourceQuery groupByRead() Group by the read column
 * @method     ChildProfileResourceQuery groupByWrite() Group by the write column
 * @method     ChildProfileResourceQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildProfileResourceQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildProfileResourceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildProfileResourceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildProfileResourceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildProfileResourceQuery leftJoinProfile($relationAlias = null) Adds a LEFT JOIN clause to the query using the Profile relation
 * @method     ChildProfileResourceQuery rightJoinProfile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Profile relation
 * @method     ChildProfileResourceQuery innerJoinProfile($relationAlias = null) Adds a INNER JOIN clause to the query using the Profile relation
 *
 * @method     ChildProfileResourceQuery leftJoinResource($relationAlias = null) Adds a LEFT JOIN clause to the query using the Resource relation
 * @method     ChildProfileResourceQuery rightJoinResource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Resource relation
 * @method     ChildProfileResourceQuery innerJoinResource($relationAlias = null) Adds a INNER JOIN clause to the query using the Resource relation
 *
 * @method     ChildProfileResource findOne(ConnectionInterface $con = null) Return the first ChildProfileResource matching the query
 * @method     ChildProfileResource findOneOrCreate(ConnectionInterface $con = null) Return the first ChildProfileResource matching the query, or a new ChildProfileResource object populated from the query conditions when no match is found
 *
 * @method     ChildProfileResource findOneById(int $id) Return the first ChildProfileResource filtered by the id column
 * @method     ChildProfileResource findOneByProfileId(int $profile_id) Return the first ChildProfileResource filtered by the profile_id column
 * @method     ChildProfileResource findOneByResourceId(int $resource_id) Return the first ChildProfileResource filtered by the resource_id column
 * @method     ChildProfileResource findOneByRead(int $read) Return the first ChildProfileResource filtered by the read column
 * @method     ChildProfileResource findOneByWrite(int $write) Return the first ChildProfileResource filtered by the write column
 * @method     ChildProfileResource findOneByCreatedAt(string $created_at) Return the first ChildProfileResource filtered by the created_at column
 * @method     ChildProfileResource findOneByUpdatedAt(string $updated_at) Return the first ChildProfileResource filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildProfileResource objects filtered by the id column
 * @method     array findByProfileId(int $profile_id) Return ChildProfileResource objects filtered by the profile_id column
 * @method     array findByResourceId(int $resource_id) Return ChildProfileResource objects filtered by the resource_id column
 * @method     array findByRead(int $read) Return ChildProfileResource objects filtered by the read column
 * @method     array findByWrite(int $write) Return ChildProfileResource objects filtered by the write column
 * @method     array findByCreatedAt(string $created_at) Return ChildProfileResource objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildProfileResource objects filtered by the updated_at column
 *
 */
abstract class ProfileResourceQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Thelia\Model\Base\ProfileResourceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\Thelia\\Model\\ProfileResource', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildProfileResourceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildProfileResourceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \Thelia\Model\ProfileResourceQuery) {
            return $criteria;
        }
        $query = new \Thelia\Model\ProfileResourceQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$id, $profile_id, $resource_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildProfileResource|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProfileResourceTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ProfileResourceTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildProfileResource A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, PROFILE_ID, RESOURCE_ID, READ, WRITE, CREATED_AT, UPDATED_AT FROM profile_resource WHERE ID = :p0 AND PROFILE_ID = :p1 AND RESOURCE_ID = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildProfileResource();
            $obj->hydrate($row);
            ProfileResourceTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildProfileResource|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ProfileResourceTableMap::ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ProfileResourceTableMap::PROFILE_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(ProfileResourceTableMap::RESOURCE_ID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ProfileResourceTableMap::ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ProfileResourceTableMap::PROFILE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(ProfileResourceTableMap::RESOURCE_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ProfileResourceTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ProfileResourceTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfileResourceTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the profile_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProfileId(1234); // WHERE profile_id = 1234
     * $query->filterByProfileId(array(12, 34)); // WHERE profile_id IN (12, 34)
     * $query->filterByProfileId(array('min' => 12)); // WHERE profile_id > 12
     * </code>
     *
     * @see       filterByProfile()
     *
     * @param     mixed $profileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByProfileId($profileId = null, $comparison = null)
    {
        if (is_array($profileId)) {
            $useMinMax = false;
            if (isset($profileId['min'])) {
                $this->addUsingAlias(ProfileResourceTableMap::PROFILE_ID, $profileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($profileId['max'])) {
                $this->addUsingAlias(ProfileResourceTableMap::PROFILE_ID, $profileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfileResourceTableMap::PROFILE_ID, $profileId, $comparison);
    }

    /**
     * Filter the query on the resource_id column
     *
     * Example usage:
     * <code>
     * $query->filterByResourceId(1234); // WHERE resource_id = 1234
     * $query->filterByResourceId(array(12, 34)); // WHERE resource_id IN (12, 34)
     * $query->filterByResourceId(array('min' => 12)); // WHERE resource_id > 12
     * </code>
     *
     * @see       filterByResource()
     *
     * @param     mixed $resourceId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByResourceId($resourceId = null, $comparison = null)
    {
        if (is_array($resourceId)) {
            $useMinMax = false;
            if (isset($resourceId['min'])) {
                $this->addUsingAlias(ProfileResourceTableMap::RESOURCE_ID, $resourceId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($resourceId['max'])) {
                $this->addUsingAlias(ProfileResourceTableMap::RESOURCE_ID, $resourceId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfileResourceTableMap::RESOURCE_ID, $resourceId, $comparison);
    }

    /**
     * Filter the query on the read column
     *
     * Example usage:
     * <code>
     * $query->filterByRead(1234); // WHERE read = 1234
     * $query->filterByRead(array(12, 34)); // WHERE read IN (12, 34)
     * $query->filterByRead(array('min' => 12)); // WHERE read > 12
     * </code>
     *
     * @param     mixed $read The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByRead($read = null, $comparison = null)
    {
        if (is_array($read)) {
            $useMinMax = false;
            if (isset($read['min'])) {
                $this->addUsingAlias(ProfileResourceTableMap::READ, $read['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($read['max'])) {
                $this->addUsingAlias(ProfileResourceTableMap::READ, $read['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfileResourceTableMap::READ, $read, $comparison);
    }

    /**
     * Filter the query on the write column
     *
     * Example usage:
     * <code>
     * $query->filterByWrite(1234); // WHERE write = 1234
     * $query->filterByWrite(array(12, 34)); // WHERE write IN (12, 34)
     * $query->filterByWrite(array('min' => 12)); // WHERE write > 12
     * </code>
     *
     * @param     mixed $write The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByWrite($write = null, $comparison = null)
    {
        if (is_array($write)) {
            $useMinMax = false;
            if (isset($write['min'])) {
                $this->addUsingAlias(ProfileResourceTableMap::WRITE, $write['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($write['max'])) {
                $this->addUsingAlias(ProfileResourceTableMap::WRITE, $write['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfileResourceTableMap::WRITE, $write, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ProfileResourceTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ProfileResourceTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfileResourceTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ProfileResourceTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ProfileResourceTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfileResourceTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Profile object
     *
     * @param \Thelia\Model\Profile|ObjectCollection $profile The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByProfile($profile, $comparison = null)
    {
        if ($profile instanceof \Thelia\Model\Profile) {
            return $this
                ->addUsingAlias(ProfileResourceTableMap::PROFILE_ID, $profile->getId(), $comparison);
        } elseif ($profile instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProfileResourceTableMap::PROFILE_ID, $profile->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProfile() only accepts arguments of type \Thelia\Model\Profile or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Profile relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function joinProfile($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Profile');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Profile');
        }

        return $this;
    }

    /**
     * Use the Profile relation Profile object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\ProfileQuery A secondary query class using the current class as primary query
     */
    public function useProfileQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProfile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Profile', '\Thelia\Model\ProfileQuery');
    }

    /**
     * Filter the query by a related \Thelia\Model\Resource object
     *
     * @param \Thelia\Model\Resource|ObjectCollection $resource The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function filterByResource($resource, $comparison = null)
    {
        if ($resource instanceof \Thelia\Model\Resource) {
            return $this
                ->addUsingAlias(ProfileResourceTableMap::RESOURCE_ID, $resource->getId(), $comparison);
        } elseif ($resource instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProfileResourceTableMap::RESOURCE_ID, $resource->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByResource() only accepts arguments of type \Thelia\Model\Resource or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Resource relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function joinResource($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Resource');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Resource');
        }

        return $this;
    }

    /**
     * Use the Resource relation Resource object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\ResourceQuery A secondary query class using the current class as primary query
     */
    public function useResourceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinResource($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Resource', '\Thelia\Model\ResourceQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildProfileResource $profileResource Object to remove from the list of results
     *
     * @return ChildProfileResourceQuery The current query, for fluid interface
     */
    public function prune($profileResource = null)
    {
        if ($profileResource) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ProfileResourceTableMap::ID), $profileResource->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ProfileResourceTableMap::PROFILE_ID), $profileResource->getProfileId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(ProfileResourceTableMap::RESOURCE_ID), $profileResource->getResourceId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the profile_resource table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProfileResourceTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ProfileResourceTableMap::clearInstancePool();
            ProfileResourceTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildProfileResource or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildProfileResource object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProfileResourceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ProfileResourceTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        ProfileResourceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ProfileResourceTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildProfileResourceQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ProfileResourceTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildProfileResourceQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ProfileResourceTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildProfileResourceQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ProfileResourceTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildProfileResourceQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ProfileResourceTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildProfileResourceQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ProfileResourceTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildProfileResourceQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ProfileResourceTableMap::CREATED_AT);
    }

} // ProfileResourceQuery
