<?php

namespace Thelia\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use Thelia\Model\OrderVersion;
use Thelia\Model\OrderVersionQuery;


/**
 * This class defines the structure of the 'order_version' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OrderVersionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Thelia.Model.Map.OrderVersionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'order_version';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Thelia\\Model\\OrderVersion';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Thelia.Model.OrderVersion';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 24;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 24;

    /**
     * the column name for the ID field
     */
    const ID = 'order_version.ID';

    /**
     * the column name for the REF field
     */
    const REF = 'order_version.REF';

    /**
     * the column name for the CUSTOMER_ID field
     */
    const CUSTOMER_ID = 'order_version.CUSTOMER_ID';

    /**
     * the column name for the INVOICE_ORDER_ADDRESS_ID field
     */
    const INVOICE_ORDER_ADDRESS_ID = 'order_version.INVOICE_ORDER_ADDRESS_ID';

    /**
     * the column name for the DELIVERY_ORDER_ADDRESS_ID field
     */
    const DELIVERY_ORDER_ADDRESS_ID = 'order_version.DELIVERY_ORDER_ADDRESS_ID';

    /**
     * the column name for the INVOICE_DATE field
     */
    const INVOICE_DATE = 'order_version.INVOICE_DATE';

    /**
     * the column name for the CURRENCY_ID field
     */
    const CURRENCY_ID = 'order_version.CURRENCY_ID';

    /**
     * the column name for the CURRENCY_RATE field
     */
    const CURRENCY_RATE = 'order_version.CURRENCY_RATE';

    /**
     * the column name for the TRANSACTION_REF field
     */
    const TRANSACTION_REF = 'order_version.TRANSACTION_REF';

    /**
     * the column name for the DELIVERY_REF field
     */
    const DELIVERY_REF = 'order_version.DELIVERY_REF';

    /**
     * the column name for the INVOICE_REF field
     */
    const INVOICE_REF = 'order_version.INVOICE_REF';

    /**
     * the column name for the DISCOUNT field
     */
    const DISCOUNT = 'order_version.DISCOUNT';

    /**
     * the column name for the POSTAGE field
     */
    const POSTAGE = 'order_version.POSTAGE';

    /**
     * the column name for the PAYMENT_MODULE_ID field
     */
    const PAYMENT_MODULE_ID = 'order_version.PAYMENT_MODULE_ID';

    /**
     * the column name for the DELIVERY_MODULE_ID field
     */
    const DELIVERY_MODULE_ID = 'order_version.DELIVERY_MODULE_ID';

    /**
     * the column name for the STATUS_ID field
     */
    const STATUS_ID = 'order_version.STATUS_ID';

    /**
     * the column name for the LANG_ID field
     */
    const LANG_ID = 'order_version.LANG_ID';

    /**
     * the column name for the CART_ID field
     */
    const CART_ID = 'order_version.CART_ID';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'order_version.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'order_version.UPDATED_AT';

    /**
     * the column name for the VERSION field
     */
    const VERSION = 'order_version.VERSION';

    /**
     * the column name for the VERSION_CREATED_AT field
     */
    const VERSION_CREATED_AT = 'order_version.VERSION_CREATED_AT';

    /**
     * the column name for the VERSION_CREATED_BY field
     */
    const VERSION_CREATED_BY = 'order_version.VERSION_CREATED_BY';

    /**
     * the column name for the CUSTOMER_ID_VERSION field
     */
    const CUSTOMER_ID_VERSION = 'order_version.CUSTOMER_ID_VERSION';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Ref', 'CustomerId', 'InvoiceOrderAddressId', 'DeliveryOrderAddressId', 'InvoiceDate', 'CurrencyId', 'CurrencyRate', 'TransactionRef', 'DeliveryRef', 'InvoiceRef', 'Discount', 'Postage', 'PaymentModuleId', 'DeliveryModuleId', 'StatusId', 'LangId', 'CartId', 'CreatedAt', 'UpdatedAt', 'Version', 'VersionCreatedAt', 'VersionCreatedBy', 'CustomerIdVersion', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'ref', 'customerId', 'invoiceOrderAddressId', 'deliveryOrderAddressId', 'invoiceDate', 'currencyId', 'currencyRate', 'transactionRef', 'deliveryRef', 'invoiceRef', 'discount', 'postage', 'paymentModuleId', 'deliveryModuleId', 'statusId', 'langId', 'cartId', 'createdAt', 'updatedAt', 'version', 'versionCreatedAt', 'versionCreatedBy', 'customerIdVersion', ),
        self::TYPE_COLNAME       => array(OrderVersionTableMap::ID, OrderVersionTableMap::REF, OrderVersionTableMap::CUSTOMER_ID, OrderVersionTableMap::INVOICE_ORDER_ADDRESS_ID, OrderVersionTableMap::DELIVERY_ORDER_ADDRESS_ID, OrderVersionTableMap::INVOICE_DATE, OrderVersionTableMap::CURRENCY_ID, OrderVersionTableMap::CURRENCY_RATE, OrderVersionTableMap::TRANSACTION_REF, OrderVersionTableMap::DELIVERY_REF, OrderVersionTableMap::INVOICE_REF, OrderVersionTableMap::DISCOUNT, OrderVersionTableMap::POSTAGE, OrderVersionTableMap::PAYMENT_MODULE_ID, OrderVersionTableMap::DELIVERY_MODULE_ID, OrderVersionTableMap::STATUS_ID, OrderVersionTableMap::LANG_ID, OrderVersionTableMap::CART_ID, OrderVersionTableMap::CREATED_AT, OrderVersionTableMap::UPDATED_AT, OrderVersionTableMap::VERSION, OrderVersionTableMap::VERSION_CREATED_AT, OrderVersionTableMap::VERSION_CREATED_BY, OrderVersionTableMap::CUSTOMER_ID_VERSION, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'REF', 'CUSTOMER_ID', 'INVOICE_ORDER_ADDRESS_ID', 'DELIVERY_ORDER_ADDRESS_ID', 'INVOICE_DATE', 'CURRENCY_ID', 'CURRENCY_RATE', 'TRANSACTION_REF', 'DELIVERY_REF', 'INVOICE_REF', 'DISCOUNT', 'POSTAGE', 'PAYMENT_MODULE_ID', 'DELIVERY_MODULE_ID', 'STATUS_ID', 'LANG_ID', 'CART_ID', 'CREATED_AT', 'UPDATED_AT', 'VERSION', 'VERSION_CREATED_AT', 'VERSION_CREATED_BY', 'CUSTOMER_ID_VERSION', ),
        self::TYPE_FIELDNAME     => array('id', 'ref', 'customer_id', 'invoice_order_address_id', 'delivery_order_address_id', 'invoice_date', 'currency_id', 'currency_rate', 'transaction_ref', 'delivery_ref', 'invoice_ref', 'discount', 'postage', 'payment_module_id', 'delivery_module_id', 'status_id', 'lang_id', 'cart_id', 'created_at', 'updated_at', 'version', 'version_created_at', 'version_created_by', 'customer_id_version', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Ref' => 1, 'CustomerId' => 2, 'InvoiceOrderAddressId' => 3, 'DeliveryOrderAddressId' => 4, 'InvoiceDate' => 5, 'CurrencyId' => 6, 'CurrencyRate' => 7, 'TransactionRef' => 8, 'DeliveryRef' => 9, 'InvoiceRef' => 10, 'Discount' => 11, 'Postage' => 12, 'PaymentModuleId' => 13, 'DeliveryModuleId' => 14, 'StatusId' => 15, 'LangId' => 16, 'CartId' => 17, 'CreatedAt' => 18, 'UpdatedAt' => 19, 'Version' => 20, 'VersionCreatedAt' => 21, 'VersionCreatedBy' => 22, 'CustomerIdVersion' => 23, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'ref' => 1, 'customerId' => 2, 'invoiceOrderAddressId' => 3, 'deliveryOrderAddressId' => 4, 'invoiceDate' => 5, 'currencyId' => 6, 'currencyRate' => 7, 'transactionRef' => 8, 'deliveryRef' => 9, 'invoiceRef' => 10, 'discount' => 11, 'postage' => 12, 'paymentModuleId' => 13, 'deliveryModuleId' => 14, 'statusId' => 15, 'langId' => 16, 'cartId' => 17, 'createdAt' => 18, 'updatedAt' => 19, 'version' => 20, 'versionCreatedAt' => 21, 'versionCreatedBy' => 22, 'customerIdVersion' => 23, ),
        self::TYPE_COLNAME       => array(OrderVersionTableMap::ID => 0, OrderVersionTableMap::REF => 1, OrderVersionTableMap::CUSTOMER_ID => 2, OrderVersionTableMap::INVOICE_ORDER_ADDRESS_ID => 3, OrderVersionTableMap::DELIVERY_ORDER_ADDRESS_ID => 4, OrderVersionTableMap::INVOICE_DATE => 5, OrderVersionTableMap::CURRENCY_ID => 6, OrderVersionTableMap::CURRENCY_RATE => 7, OrderVersionTableMap::TRANSACTION_REF => 8, OrderVersionTableMap::DELIVERY_REF => 9, OrderVersionTableMap::INVOICE_REF => 10, OrderVersionTableMap::DISCOUNT => 11, OrderVersionTableMap::POSTAGE => 12, OrderVersionTableMap::PAYMENT_MODULE_ID => 13, OrderVersionTableMap::DELIVERY_MODULE_ID => 14, OrderVersionTableMap::STATUS_ID => 15, OrderVersionTableMap::LANG_ID => 16, OrderVersionTableMap::CART_ID => 17, OrderVersionTableMap::CREATED_AT => 18, OrderVersionTableMap::UPDATED_AT => 19, OrderVersionTableMap::VERSION => 20, OrderVersionTableMap::VERSION_CREATED_AT => 21, OrderVersionTableMap::VERSION_CREATED_BY => 22, OrderVersionTableMap::CUSTOMER_ID_VERSION => 23, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'REF' => 1, 'CUSTOMER_ID' => 2, 'INVOICE_ORDER_ADDRESS_ID' => 3, 'DELIVERY_ORDER_ADDRESS_ID' => 4, 'INVOICE_DATE' => 5, 'CURRENCY_ID' => 6, 'CURRENCY_RATE' => 7, 'TRANSACTION_REF' => 8, 'DELIVERY_REF' => 9, 'INVOICE_REF' => 10, 'DISCOUNT' => 11, 'POSTAGE' => 12, 'PAYMENT_MODULE_ID' => 13, 'DELIVERY_MODULE_ID' => 14, 'STATUS_ID' => 15, 'LANG_ID' => 16, 'CART_ID' => 17, 'CREATED_AT' => 18, 'UPDATED_AT' => 19, 'VERSION' => 20, 'VERSION_CREATED_AT' => 21, 'VERSION_CREATED_BY' => 22, 'CUSTOMER_ID_VERSION' => 23, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'ref' => 1, 'customer_id' => 2, 'invoice_order_address_id' => 3, 'delivery_order_address_id' => 4, 'invoice_date' => 5, 'currency_id' => 6, 'currency_rate' => 7, 'transaction_ref' => 8, 'delivery_ref' => 9, 'invoice_ref' => 10, 'discount' => 11, 'postage' => 12, 'payment_module_id' => 13, 'delivery_module_id' => 14, 'status_id' => 15, 'lang_id' => 16, 'cart_id' => 17, 'created_at' => 18, 'updated_at' => 19, 'version' => 20, 'version_created_at' => 21, 'version_created_by' => 22, 'customer_id_version' => 23, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('order_version');
        $this->setPhpName('OrderVersion');
        $this->setClassName('\\Thelia\\Model\\OrderVersion');
        $this->setPackage('Thelia.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('ID', 'Id', 'INTEGER' , 'order', 'ID', true, null, null);
        $this->addColumn('REF', 'Ref', 'VARCHAR', false, 45, null);
        $this->addColumn('CUSTOMER_ID', 'CustomerId', 'INTEGER', true, null, null);
        $this->addColumn('INVOICE_ORDER_ADDRESS_ID', 'InvoiceOrderAddressId', 'INTEGER', true, null, null);
        $this->addColumn('DELIVERY_ORDER_ADDRESS_ID', 'DeliveryOrderAddressId', 'INTEGER', true, null, null);
        $this->addColumn('INVOICE_DATE', 'InvoiceDate', 'DATE', false, null, null);
        $this->addColumn('CURRENCY_ID', 'CurrencyId', 'INTEGER', true, null, null);
        $this->addColumn('CURRENCY_RATE', 'CurrencyRate', 'FLOAT', true, null, null);
        $this->addColumn('TRANSACTION_REF', 'TransactionRef', 'VARCHAR', false, 100, null);
        $this->addColumn('DELIVERY_REF', 'DeliveryRef', 'VARCHAR', false, 100, null);
        $this->addColumn('INVOICE_REF', 'InvoiceRef', 'VARCHAR', false, 100, null);
        $this->addColumn('DISCOUNT', 'Discount', 'FLOAT', false, null, null);
        $this->addColumn('POSTAGE', 'Postage', 'FLOAT', true, null, null);
        $this->addColumn('PAYMENT_MODULE_ID', 'PaymentModuleId', 'INTEGER', true, null, null);
        $this->addColumn('DELIVERY_MODULE_ID', 'DeliveryModuleId', 'INTEGER', true, null, null);
        $this->addColumn('STATUS_ID', 'StatusId', 'INTEGER', true, null, null);
        $this->addColumn('LANG_ID', 'LangId', 'INTEGER', true, null, null);
        $this->addColumn('CART_ID', 'CartId', 'INTEGER', true, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addPrimaryKey('VERSION', 'Version', 'INTEGER', true, null, 0);
        $this->addColumn('VERSION_CREATED_AT', 'VersionCreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('VERSION_CREATED_BY', 'VersionCreatedBy', 'VARCHAR', false, 100, null);
        $this->addColumn('CUSTOMER_ID_VERSION', 'CustomerIdVersion', 'INTEGER', false, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Order', '\\Thelia\\Model\\Order', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Thelia\Model\OrderVersion $obj A \Thelia\Model\OrderVersion object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getId(), (string) $obj->getVersion()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \Thelia\Model\OrderVersion object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Thelia\Model\OrderVersion) {
                $key = serialize(array((string) $value->getId(), (string) $value->getVersion()));

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Thelia\Model\OrderVersion object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 20 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 20 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)]));
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return $pks;
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? OrderVersionTableMap::CLASS_DEFAULT : OrderVersionTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (OrderVersion object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OrderVersionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OrderVersionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OrderVersionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OrderVersionTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OrderVersionTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = OrderVersionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OrderVersionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OrderVersionTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(OrderVersionTableMap::ID);
            $criteria->addSelectColumn(OrderVersionTableMap::REF);
            $criteria->addSelectColumn(OrderVersionTableMap::CUSTOMER_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::INVOICE_ORDER_ADDRESS_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::DELIVERY_ORDER_ADDRESS_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::INVOICE_DATE);
            $criteria->addSelectColumn(OrderVersionTableMap::CURRENCY_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::CURRENCY_RATE);
            $criteria->addSelectColumn(OrderVersionTableMap::TRANSACTION_REF);
            $criteria->addSelectColumn(OrderVersionTableMap::DELIVERY_REF);
            $criteria->addSelectColumn(OrderVersionTableMap::INVOICE_REF);
            $criteria->addSelectColumn(OrderVersionTableMap::DISCOUNT);
            $criteria->addSelectColumn(OrderVersionTableMap::POSTAGE);
            $criteria->addSelectColumn(OrderVersionTableMap::PAYMENT_MODULE_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::DELIVERY_MODULE_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::STATUS_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::LANG_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::CART_ID);
            $criteria->addSelectColumn(OrderVersionTableMap::CREATED_AT);
            $criteria->addSelectColumn(OrderVersionTableMap::UPDATED_AT);
            $criteria->addSelectColumn(OrderVersionTableMap::VERSION);
            $criteria->addSelectColumn(OrderVersionTableMap::VERSION_CREATED_AT);
            $criteria->addSelectColumn(OrderVersionTableMap::VERSION_CREATED_BY);
            $criteria->addSelectColumn(OrderVersionTableMap::CUSTOMER_ID_VERSION);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.REF');
            $criteria->addSelectColumn($alias . '.CUSTOMER_ID');
            $criteria->addSelectColumn($alias . '.INVOICE_ORDER_ADDRESS_ID');
            $criteria->addSelectColumn($alias . '.DELIVERY_ORDER_ADDRESS_ID');
            $criteria->addSelectColumn($alias . '.INVOICE_DATE');
            $criteria->addSelectColumn($alias . '.CURRENCY_ID');
            $criteria->addSelectColumn($alias . '.CURRENCY_RATE');
            $criteria->addSelectColumn($alias . '.TRANSACTION_REF');
            $criteria->addSelectColumn($alias . '.DELIVERY_REF');
            $criteria->addSelectColumn($alias . '.INVOICE_REF');
            $criteria->addSelectColumn($alias . '.DISCOUNT');
            $criteria->addSelectColumn($alias . '.POSTAGE');
            $criteria->addSelectColumn($alias . '.PAYMENT_MODULE_ID');
            $criteria->addSelectColumn($alias . '.DELIVERY_MODULE_ID');
            $criteria->addSelectColumn($alias . '.STATUS_ID');
            $criteria->addSelectColumn($alias . '.LANG_ID');
            $criteria->addSelectColumn($alias . '.CART_ID');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
            $criteria->addSelectColumn($alias . '.VERSION');
            $criteria->addSelectColumn($alias . '.VERSION_CREATED_AT');
            $criteria->addSelectColumn($alias . '.VERSION_CREATED_BY');
            $criteria->addSelectColumn($alias . '.CUSTOMER_ID_VERSION');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(OrderVersionTableMap::DATABASE_NAME)->getTable(OrderVersionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(OrderVersionTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(OrderVersionTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new OrderVersionTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a OrderVersion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or OrderVersion object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderVersionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Thelia\Model\OrderVersion) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OrderVersionTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(OrderVersionTableMap::ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(OrderVersionTableMap::VERSION, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = OrderVersionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { OrderVersionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { OrderVersionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the order_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OrderVersionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a OrderVersion or Criteria object.
     *
     * @param mixed               $criteria Criteria or OrderVersion object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderVersionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from OrderVersion object
        }


        // Set the correct dbName
        $query = OrderVersionQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // OrderVersionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OrderVersionTableMap::buildTableMap();
