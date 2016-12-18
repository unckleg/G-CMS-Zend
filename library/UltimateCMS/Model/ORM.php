<?php

class UltimateCMS_Model_ORM
{
    protected static $_dbTable;
    protected static $_dbTableMetaData;

    const COLUMN_ASC = Zend_Db_Select::SQL_ASC;
    const COLUMN_DESC = Zend_Db_Select::SQL_DESC;

    const DATE_EQUAL = '=';
    const DATE_BETWEEN = 'between';
    const DATE_BEFORE = '<';
    const DATE_AFTER = '>';

    const TYPE_NUMERIC = array('int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'decimal', 'float', 'double', 'real', 'bit', 'boolean', 'serial');
    const TYPE_TEXT = array('char', 'varchar', 'tinytext', 'longtext', 'binary', 'varbinary', 'tinyblob', 'mediumblob', 'blob', 'longblob', 'enum', 'set');
    const TYPE_DATE = array('date', 'datetime', 'timestamp', 'time', 'year');

    const TO_ARRAY = 'toArray';

    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

    /**
     * @param Zend_Db_Table_Abstract $dbObject
     * @return mixed
     * @throws Zend_Db_Table_Exception
     */
    protected static function getTableName(Zend_Db_Table_Abstract $dbObject)
    {
        return $dbObject->info(Zend_Db_Table_Abstract::NAME);
    }

    /**
     * @param \Zend_Db_Table_Abstract $dbObject
     * @return array
     */
    protected static function getTableMetaData(Zend_Db_Table_Abstract $dbObject)
    {
        $metaData = $dbObject->info(Zend_Db_Table_Abstract::METADATA);
        return $metaData;
    }

    /**
     * <p>Array $parameters is keeping search parameters.
     * Array $parameters must be in following format:
     *  ORM::Mapper_Search($dbObject, array(
     *     'filters' => array(
     *          'status' => 1,
     *          'id' => array(1, 2, 3),
     *          'name' => 'somename'
     *      ),
     *     'orders' => array(
     *          'username' => ORM::COLUMN_ASC', | key is column, if value is ASC then ORDER BY ASC
     *          'date_created' => 'ORM::COLUMN_DESC | key is column, if value is DESC then ORDER BY DESC
     *      ),
     *      'limit' => 20, | limit result set to 20 rows
     *      'page' => 3 | Start from page 3. If no limit isset, page is ignored
     *  ), <b>ORM::TO_ARRAY</b>);  | If you want to convert result to array, else remove constant</p>
     * @param Zend_Db_Table_Abstract $dbObject
     * @param array $parameters Asoc array with keys "filters", "orders", "limit" and "page".
     * @return Zend_Db_Table_Rowset_Abstract The row results per the Zend_Db_Adapter fetch mode.
     * @throws Exception
     */
    public static function Mapper_Search(Zend_Db_Table_Abstract $dbObject, array $parameters, $fetchType = null)
    {
        $select = $dbObject->select();
        $tableMetaData = self::getTableMetaData($dbObject);

        if (isset($parameters['filters'])) {
            $filters = $parameters['filters'];
            self::Mapper_Filters($filters, $select, $dbObject);
        }
        if (isset($parameters['orders'])) {
            $orders = $parameters['orders'];
            foreach ($orders as $field => $direction) {
                if (array_key_exists($field, $tableMetaData)) {
                    if ($direction === self::COLUMN_DESC) {
                        $select->order($field .' '. self::COLUMN_DESC);
                    } else {
                        $select->order($field .' '. self::COLUMN_ASC);
                    }
                } else
                    throw new Exception('Provided column: ' . $orders[$field] . ' was not found in table');
            }
        }
        if (isset($parameters['limit'])) {
            if (isset($parameters['pagination'])) {
                // if page isset do limit by page
                $select->limitPage($parameters['pagination'], $parameters['limit']);
            } else {
                // page is not set do regular limit
                $select->limit($parameters['limit']);
            }
        }
        if (isset($fetchType) && $fetchType === self::TO_ARRAY) {
            return $dbObject->fetchAll($select)->toArray();
        } else {
            return $dbObject->fetchAll($select);
        }
    }

    /**
     * Prepares and executes an SQL statement with bound data.
     * @param string $sql
     * @param array $params An array of data to bind to the placeholders.
     * @return array
     * @throws Exception
     */
    public static function Mapper_SearchBySqlQuery($sql, array $params = array())
    {
        if(!$sql) {
            throw new Exception('Provided sql parameter is empty.');
        } else {
            $db = Zend_Db_Table::getDefaultAdapter();
            $stmt = $db->query($sql, $params);
            $result = $stmt->fetchAll();
            return $result;
        }
    }

    /**
     * @param Zend_Db_Table_Abstract $dbObject
     * @param string $fetchType
     * @return Zend_Db_Table_Rowset_Abstract The row results per the Zend_Db_Adapter fetch mode.
     */
    public static function Mapper_SearchAll(Zend_Db_Table_Abstract $dbObject, $fetchType = null)
    {
        $allData = $dbObject->fetchAll();
        if (count($allData) > 0) {
            if (isset($fetchType) && $fetchType === self::TO_ARRAY) {
                return $allData->toArray();
            } else
                return $allData;
        } else
            return array();
    }

    /**
     * Return Zend_Db_Table_Row_Abstract|null The row results per the
     * Zend_Db_Adapter fetch mode, or null if no row found.
     * If $fetchType is passed as constant as method arg object is converted to array.
     * @param Zend_Db_Table_Abstract $dbObject
     * @param array $parameters
     * @param string $fetchType
     * @return array|object
     * @throws Exception
     */
    public static function Mapper_SearchByOne(Zend_Db_Table_Abstract $dbObject, array $parameters, $fetchType = null)
    {
        $select = $dbObject->select();
        $meta = self::getTableMetaData($dbObject);

        if(isset($parameters['filters'])) {
            self::Mapper_Filters($parameters['filters'], $select, $dbObject);
        }
        if (isset($parameters['orders'])) {
            $orders = $parameters['orders'];
            foreach ($orders as $field => $direction) {
                if (array_key_exists($field, $meta)) {
                    if ($direction === self::COLUMN_DESC) {
                        $select->order($field .' '. self::COLUMN_DESC);
                    } else {
                        $select->order($field .' '. self::COLUMN_ASC);
                    }
                } else
                    throw new Exception('Provided column: ' . $orders[$field] . ' was not found in table');
            }
        }
        $row = $dbObject->fetchRow($select);
        if (count($row) > 0) {
            if (isset($fetchType) && $fetchType === self::TO_ARRAY) {
                return $row->toArray();
            } else {
                return $row;
            }
        }
    }

    public static function Mapper_SearchByNot()
    {
    }

    /**
     *
     * @param Zend_Db_Table_Abstract $dbObject
     * @param array $parameters See function search $parameters['filters']
     * @return int Count of rows that match $filters
     */
    public static function Mapper_Count(Zend_Db_Table_Abstract $dbObject, array $parameters)
    {
        $select = $dbObject->select();
        self::Mapper_Filters($parameters['filters'], $select, $dbObject);

        // reset previously set columns for resultset
        // and set one column/field to fetch and it is COUNT function
        $select->reset('columns');
        $select->from(self::getTableName($dbObject), 'COUNT(*) AS total');

        $dbRow = $dbObject->fetchRow($select);
        return $dbRow['total'];
    }

    /**
     * Fill $select object with WHERE conditions in combination with table field type and build query
     * @param array $filters
     * @param Zend_Db_Table_Select $select
     * @param Zend_Db_Table_Abstract $dbObject
     * @throws Exception
     */
    public static function Mapper_Filters(array $filters, Zend_Db_Table_Select $select, Zend_Db_Table_Abstract $dbObject)
    {
        $meta = self::getTableMetaData($dbObject);

        foreach ($filters as $field => $value) {
            if (array_key_exists($field, $meta)) {
                if (in_array($meta[$field]['DATA_TYPE'], self::TYPE_NUMERIC)) {
                    if (is_array($value)) {
                        $select->where($field . ' IN (?)', $value);
                    } else {
                        $select->where($field . ' = ?', $value);
                    }
                } elseif(in_array($meta[$field]['DATA_TYPE'], self::TYPE_TEXT)) {
                    if (is_array($value)) {
                        foreach ($value as $criteria) {
                            $select->orwhere($field . ' LIKE (?)', "%$criteria%");
                        }
                    } else {
                        $select->where($field . ' LIKE ?', "%$value%");
                    }
                } elseif(in_array($meta[$field]['DATA_TYPE'], self::TYPE_DATE)) {
                    if (count($value) === 2) {
                        $select->where($field .' '. $value[0]. ' ? ', $value[1]);
                    } elseif(count($value) === 3) {
                        if ($value[0] == self::DATE_BETWEEN) {
                            $select->where($field . self::DATE_AFTER . ' ?', $value[1])
                                ->where($field . self::DATE_BEFORE . ' ?', $value[2]);
                        } else
                            throw new Exception('Provided date format method arguments is not valid. '
                                . 'See Document block for more info.');
                    }
                }
            } else
                throw new Exception('Provided column: ' . $field . ' was not found in table');
        }
    }

    /**
     *
     * @param string $fetchType
     * @param array $parameters
     * @throws Exception
     * @return array|object Zend_Db_Table_Rowset_Abstract The row results per the Zend_Db_Adapter fetch mode.
     */
    public static function Mapper_Join($fetchType = null, array ...$parameters)
    {
        if (count($parameters) <= 0 || $parameters == NULL || $parameters == '') {
            throw new Exception ('Parameters not valid.');
        } else {
            $dbObject = $parameters[0][0];
            $dbObject instanceof Zend_Db_Table_Abstract;
            $select = $dbObject->select();
            $select->setIntegrityCheck(false);

            $previousTableName;
            $previousFieldFirstObject;
            $previousFieldSecondObject;

            $objectCount = (count($parameters) - 1);
            $i = 0;
            foreach ($parameters as $key => $parameter) {
                $meta = self::getTableMetaData($parameter[0]);
                $condition = ($i == $objectCount);

                if (!$condition && !empty($parameter[1]) && !array_key_exists($parameter[1], $meta)) {
                    throw new Exception('Type message.');
                }

                $tableName = self::getTableName($parameter[0]);

                $tableAliases = array();
                foreach ($meta as $field => $value) {
                    $tableAliases[$tableName.'.'.$field] = $field;
                }

                if($key == 0) {
                    $select->from($tableName);
                } else {
                    $select->join($tableName,
                        "$tableName.$previousFieldSecondObject "
                        ." = ".
                        "$previousTableName.$previousFieldFirstObject",
                        $tableAliases);
                }
                $previousTableName = $tableName;

                if(!$condition) {
                    $previousFieldFirstObject = $parameter[1];
                    $previousFieldSecondObject = $parameter[2];
                }
                $i++;
            }
            if (isset($fetchType) && $fetchType === self::TO_ARRAY) {
                $row = $dbObject->fetchAll($select)->toArray();
            } else {
                $row = $dbObject->fetchAll($select);
            }
            return $row;
        }
    }

    public static function Mapper_Save()
    {
    }
}