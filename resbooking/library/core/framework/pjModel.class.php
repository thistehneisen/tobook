<?php
require_once FRAMEWORK_PATH . 'pjObject.class.php';
/**
 * Main model
 *
 * @package ebc
 * @subpackage ebc.core.framework
 */
class pjModel extends pjObject
{
/**
 * If debug mode is set to true, the application print SQL statements
 *
 * @access public
 * @var bool
 */
    var $debug = false;
/**
 * Tells if transaction is started
 *
 * @access private
 * @var bool
 */
    var $transactionStarted = false;
/**
 * Database schema
 *
 * @access public
 * @var array
 */
    var $schema = array();
/**
 * Primary key of table
 *
 * @access public
 * @var string
 */
    var $primaryKey = null;
/**
 * Table name
 *
 * @access public
 * @var string
 */
    var $table = null;
/**
 * Prefix of table names
 *
 * @access public
 * @var string
 */
    var $prefix = null;
/**
 * Hold SQL joins
 *
 * @access public
 * @var array
 */
    var $joins = array();
/**
 * Hold SQL sub-queries
 *
 * @access public
 * @var array
 */
    var $subqueries = array();
/**
 * List of comparison types
 *
 * @access private
 * @var array
 */
    var $comparisonTypes = array('=', '!=', '<>', '>', '<', '>=', '<=', '<=>', 'IS', 'IS NOT', 'IS NULL', 'IS NOT NULL', 'IN', 'NOT IN', 'ISNULL', 'LIKE', 'BETWEEN');
/**
 * List of join types
 *
 * @access private
 * @var array
 */
    var $joinTypes = array('left', 'inner', 'cross', 'right', 'natural', 'straight');
/**
 * Constructor
 */
    function pjModel()
    {
        if (defined('DEFAULT_PREFIX'))
        {
            $this->prefix = DEFAULT_PREFIX;
        }
    }
/**
 * Get current model' table name
 *
 * @access public
 * @return string
 */
    function getTable()
    {
        return $this->prefix . $this->table;
    }
/**
 * Execute 'START TRANSACTION' query. Use in conjunction with transaction-safe storage engines (InnoDB)
 *
 * @access public
 * @return bool
 */
    function begin()
    {
        if (!$this->transactionStarted && mysql_query("START TRANSACTION"))
        {
            $this->transactionStarted = true;
            return true;
        }
        return false;
    }
/**
 * Execute 'COMMIT' query. Use in conjunction with transaction-safe storage engines (InnoDB)
 *
 * @access public
 * @return bool
 */
    function commit()
    {
        if ($this->transactionStarted && mysql_query("COMMIT"))
        {
            $this->transactionStarted = false;
            return true;
        }
        return false;
    }
/**
 * Execute 'ROLLBACK' query. Use in conjunction with transaction-safe storage engines (InnoDB)
 *
 * @access public
 * @return bool
 */
    function rollback()
    {
        if ($this->transactionStarted && mysql_query("ROLLBACK"))
        {
            $this->transactionStarted = false;
            return true;
        }
        return false;
    }
/**
 * Execute 'ROLLBACK TO SAVEPOINT' query. Use in conjunction with transaction-safe storage engines (InnoDB)
 *
 * @param string $identifier
 * @access public
 * @return bool
 */
    function rollbackToSavepoint($identifier)
    {
        if ($this->transactionStarted && mysql_query("ROLLBACK TO SAVEPOINT " . $identifier))
        {
            return true;
        }
        return false;
    }
/**
 * Execute 'RELEASE SAVEPOINT' query. Use in conjunction with transaction-safe storage engines (InnoDB)
 *
 * @param string $identifier
 * @access public
 * @return bool
 */
    function releaseSavepoint($identifier)
    {
        if ($this->transactionStarted && mysql_query("RELEASE SAVEPOINT " . $identifier))
        {
            return true;
        }
        return false;
    }
/**
 * Execute 'SAVEPOINT' query. Use in conjunction with transaction-safe storage engines (InnoDB)
 *
 * @param string $identifier
 * @access public
 * @return bool
 */
    function savepoint($identifier)
    {
        if ($this->transactionStarted && mysql_query("SAVEPOINT " . $identifier))
        {
            return true;
        }
        return false;
    }
/**
 * Execute 'SET autocommit...' query. Use in conjunction with transaction-safe storage engines (InnoDB)
 *
 * @param int $value
 * @access public
 * @return bool
 */
    function autocommit($value = 0)
    {
        if (!in_array($value, array(0,1))) return false;
        if (!$this->transactionStarted && mysql_query("SET autocommit = " . $value))
        {
            $this->transactionStarted = true;
            return true;
        }
        return false;
    }

    protected function getOwnerId()
    {
        @session_start();
        if (isset($_SESSION['owner_id'])) {
            return (int) $_SESSION['owner_id'];
        }
    }
/**
 * Execute 'SELECT...LIMIT 1' query
 *
 * @param mixed|array $value
 * @access public
 * @return array
 */
    function get($value)
    {
        $a_arr = array();
        $arr = array();
        if (!is_array($value))
        {
            $value = $this->escape($value, $this->primaryKey);
            $sql_conditions = " AND `t1`.`$this->primaryKey` = '$value'";
        } else {
            $key = key($value);
            $val = $this->escape($value[$key], $key);
            $sql_conditions = " AND `t1`.`$key` = '$val'";
        }

        // Oh, yes
        if ($ownerId = $this->getOwnerId()) {
            $sql_conditions .= " AND `t1`.`owner_id` = $ownerId";
        }
        
        $j = $this->buildJoins($a_arr);
        $sql_join = $j['join'];
        $sql_join_fields = $j['fields'];

        $sql_subquery = null;
        if (count($this->subqueries) > 0)
        {
            foreach ($this->subqueries as $v)
            {
                $sql_subquery .= ", (" . $v['query'] . ") AS `" . $v['alias'] . "`";
                $a_arr[] = $v['alias'];
            }
        }
        
        $query = "SELECT `t1`.* $sql_subquery $sql_join_fields FROM `".$this->getTable()."` AS `t1` $sql_join WHERE 1=1 $sql_conditions LIMIT 1";
        if ($this->debug) echo '<pre>'.$query.'</pre>';
        $r = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($r) == 1)
        {
            $row = mysql_fetch_assoc($r);
            $f = $this->showColumns($this->getTable());
            for($j = 0; $j < count($f); $j++)
            {
                $arr[$f[$j]['field']] = $row[$f[$j]['field']];
            }
            if (count($a_arr) > 0)
            {
                foreach ($a_arr as $v)
                {
                    $arr[$v] = $row[$v];
                }
            }
        }
        return $arr;
    }
/**
 * Build joins
 *
 * @param array $a_arr
 * @access private
 * @return array
 */
    function buildJoins(&$a_arr)
    {
        $sql_join = null;
        $sql_join_fields = null;
        if (count($this->joins) > 0)
        {
            $s_arr = array();
            foreach ($this->joins as $j)
            {
                list($_joins, $_fields) = explode("|", $j);
                $sql_join[] = $_joins;
                
                $farr = explode(",", $_fields);
                
                foreach ($farr as $_el)
                {
                    if (strpos($_el, ".") !== false)
                    {
                        $d = explode(".", $_el);
                        if (isset($d[2]))
                        {
                            $a_arr[] = $d[2];
                            $alias = " AS `".$d[2]."`";
                            unset($d[2]);
                        } else {
                            $a_arr[] = $d[1];
                            $alias = null;
                        }
                        $s_arr[] = "`" . join("`.`", $d) . "`" . $alias;
                    } else {
                        $s_arr[] = "`$_el`";
                        $a_arr[] = $_el;
                    }
                }
            }

            if (count($s_arr) > 0)
            {
                $sql_join_fields = ", " . join(", ", $s_arr);
            }
            
            if (count($sql_join) > 0)
            {
                $sql_join = join(" ", $sql_join);
            }
        }
        return array('fields' => $sql_join_fields, 'join' => $sql_join);
    }
/**
 * Execute 'SELECT' query
 *
 * @param array $options Optional.
 * @access public
 * @return array
 */
    function getAll($options=array())
    {
        $a_arr = array();
        $opts = $this->buildOpts($options);
        $sql_conditions = $opts['conditions'];
        // Oh, yes
        if ($ownerId = $this->getOwnerId()) {
            $sql_conditions .= " AND `t1`.`owner_id` = $ownerId";
        }

        // Oh, yes
        if ($ownerId = $this->getOwnerId()) {
            $sql_conditions .= " AND `t1`.`owner_id` = $ownerId";
        }
        
        $sql_limit = NULL;
        if (array_key_exists('offset', $options) && array_key_exists('row_count', $options))
        {
            $sql_limit = "LIMIT " . intval($options['offset']) . ", " . intval($options['row_count']);
        }
        
        if (!empty($options['col_name']) && !empty($options['direction']) && in_array(strtoupper($options['direction']), array('ASC', 'DESC')))
        {
            $sql_order = " ORDER BY ".$options['col_name']." " . strtoupper($options['direction']);
        } else {
            $sql_order = " ORDER BY `t1`.`id` DESC";
        }
        
        $sql_group = NULL;
        if (!empty($options['group_by']))
        {
            $sql_group = " GROUP BY " . $options['group_by'];
        }
        
        $j = $this->buildJoins($a_arr);
        $sql_join = $j['join'];
        $sql_join_fields = $j['fields'];
        
        $sql_subquery = null;
        if (count($this->subqueries) > 0)
        {
            foreach ($this->subqueries as $v)
            {
                $sql_subquery .= ", (" . $v['query'] . ") AS `" . $v['alias'] . "`";
                $a_arr[] = $v['alias'];
            }
        }
        
        $arr = array();
        $query = "SELECT `t1`.*
                    $sql_join_fields
                    $sql_subquery
                    FROM `".$this->getTable()."` AS `t1`
                    $sql_join
                    WHERE 1=1 $sql_conditions
                    $sql_group
                    $sql_order
                    $sql_limit
                    ";
        if ($this->debug) echo '<pre>'.$query.'</pre>';
        
        $r = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($r) > 0)
        {
            $i = 0;
            $f = $this->showColumns($this->getTable());
            for($j = 0; $j < count($f); $j++)
            {
                $a_arr[] = $f[$j]['field'];
            }
            while ($row = mysql_fetch_assoc($r))
            {
                if (count($a_arr) > 0)
                {
                    foreach ($a_arr as $v)
                    {
                        $arr[$i][$v] = $row[$v];
                    }
                }
                $i++;
            }
        }
        return $arr;
    }
    
/**
 * Execute 'SELECT' query
 *
 * @param array $options Optional.
 * @access public
 * @return array
 */
    function getcustomer($options=array())
    {
        $a_arr = array();
        $opts = $this->buildOpts($options);
        $sql_conditions = $opts['conditions'];
        // Oh, yes
        if ($ownerId = $this->getOwnerId()) {
            $sql_conditions .= " AND `t1`.`owner_id` = $ownerId";
        }
    
        $sql_limit = NULL;
        if (array_key_exists('offset', $options) && array_key_exists('row_count', $options))
        {
            $sql_limit = "LIMIT " . intval($options['offset']) . ", " . intval($options['row_count']);
        }
    
        if (!empty($options['col_name']) && !empty($options['direction']) && in_array(strtoupper($options['direction']), array('ASC', 'DESC')))
        {
            $sql_order = " ORDER BY ".$options['col_name']." " . strtoupper($options['direction']);
        } else {
            $sql_order = " ORDER BY `t1`.`id` DESC";
        }
    
        $sql_group = NULL;
        if (!empty($options['group_by']))
        {
            $sql_group = " GROUP BY " . $options['group_by'];
        }
    
        $j = $this->buildJoins($a_arr);
        $sql_join = $j['join'];
        $sql_join_fields = $j['fields'];
         
        $sql_subquery = null;
        if (count($this->subqueries) > 0)
        {
            foreach ($this->subqueries as $v)
            {
                $sql_subquery .= ", (" . $v['query'] . ") AS `" . $v['alias'] . "`";
                $a_arr[] = $v['alias'];
            }
        }
    
        $arr = array();
        $query = "SELECT COUNT(`t1`.`c_email`) as count, `t1`.*
        $sql_join_fields
        $sql_subquery
        FROM `".$this->getTable()."` AS `t1`
        $sql_join
        WHERE 1=1 $sql_conditions
        $sql_group
        $sql_order
        $sql_limit
        ";
        if ($this->debug) echo '<pre>'.$query.'</pre>';
    
        $r = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($r) > 0)
        {
            $i = 0;
            $f = $this->showColumns($this->getTable());
            for($j = 0; $j < count($f); $j++)
            {
                $a_arr[] = $f[$j]['field'];
            }
            while ($row = mysql_fetch_assoc($r))
            {
                $arr[$i] = $row;
                $i++;
            }
        }
        return $arr;
    }
/**
 * Execute 'SELECT COUNT(*)' query
 *
 * @param array $options
 * @access public
 * @return int
 */
    function getCountCustomer($options=array())
    {
        $opts = $this->buildOpts($options);
        $sql_conditions = $opts['conditions'];
        // Oh, yes
        if ($ownerId = $this->getOwnerId()) {
            $sql_conditions .= " AND `t1`.`owner_id` = $ownerId";
        }
    
        $a_arr = array();
        $j = $this->buildJoins($a_arr);
        $sql_join = $j['join'];
         
        $query = "SELECT COUNT(`t1`.`c_email`) AS `cnt` FROM `".$this->getTable()."` AS `t1` $sql_join WHERE 1=1 $sql_conditions LIMIT 1";
        if ($this->debug) echo '<pre>'.$query.'</pre>';
        $r = mysql_query($query) or die(mysql_error());
        $row = mysql_fetch_assoc($r);
        return $row['cnt'];
    }
    
/**
 * Execute 'SELECT COUNT(*)' query
 *
 * @param array $options
 * @access public
 * @return int
 */
    function getCount($options=array())
    {
        $opts = $this->buildOpts($options);
        $sql_conditions = $opts['conditions'];
        // Oh, yes
        if ($ownerId = $this->getOwnerId()) {
            $sql_conditions .= " AND `t1`.`owner_id` = $ownerId";
        }

        $a_arr = array();
        $j = $this->buildJoins($a_arr);
        $sql_join = $j['join'];
        
        $query = "SELECT COUNT(*) AS `cnt` FROM `".$this->getTable()."` AS `t1` $sql_join WHERE 1=1 $sql_conditions LIMIT 1";
        if ($this->debug) echo '<pre>'.$query.'</pre>';
        $r = mysql_query($query) or die(mysql_error());
        $row = mysql_fetch_assoc($r);
        return $row['cnt'];
    }
/**
 * Build conditions used in WHERE clause
 *
 * @param array $options
 * @access private
 * @return array
 */
    function buildOpts($options)
    {
        $keywords = array('col_name', 'direction', 'row_count', 'offset');
        
        $defaults = array();
        $_arr = array();
        $seccheck = false;
        
        if (get_class($this)=='pjUserModel' && is_array($options) && count($options) == 2)
        {
            
           foreach ($options as $key => $value)
            {
              if (empty($value) && !in_array($key, $keywords)) {
               $seccheck=true;
          }
            }
        }
        foreach ($this->schema as $field)
        {
            $defaults[$field['name']] = NULL;
            foreach ($options as $ok => $ov)
            {
                if (strpos($ok, $field['name']) !== false && !in_array($ok, $keywords))
                {
                    $_arr[$ok] = $ov;
                }
            }
        }
        $opts = array_merge($defaults, $_arr);
        if ($seccheck) $opts=$options;
        $sql_conditions = NULL;
        foreach ($opts as $key => $value)
        {
            if (is_array($value) && count($value) == 3)
            {
                # indexes: [0] - value, [1] - operator, [2] - type
                if (!in_array($value[1], $this->comparisonTypes)) return false;
                
                $sql_conditions .= " AND $key " . $value[1] ." ". $this->escape($value[0], null, $value[2]);
            } else {
                if (!empty($value)  or $seccheck)
                {
                    $sql_conditions .= " AND $key = '" . $this->escape($value, $key) . "'";
                }
            }
        }
        return array('conditions' => $sql_conditions);
    }
/**
 * Execute 'INSERT' query
 *
 * @param array $data
 * @access public
 * @return int|false
 */
    function save($data)
    {
        $save = array();
        
        // Add owner_id to schema
        $this->schema[] = [
            'name' => 'owner_id',
            'type' => 'int'
        ];

        if (!isset($data['owner_id']) && ($ownerId = $this->getOwnerId())) {
            $data['owner_id'] = $ownerId;
        }
        
        foreach ($this->schema as $field)
        {
            if (isset($data[$field['name']]))
            {
                $save[] = "`".$field['name']."` = '" . $this->escape($data[$field['name']], null, $field['type']) . "'";
            } else {
                $save[] = "`".$field['name']."` = " . (strpos($field['default'], ":") === 0 ? substr($field['default'], 1) : "'".$this->escape($field['default'], null, $field['type'])."'");
            }
        }

        if (count($save) > 0)
        {
            mysql_query("INSERT IGNORE INTO `".$this->getTable()."` SET " . join(",", $save)) or die(mysql_error());
            if (mysql_affected_rows() == 1)
            {
                return mysql_insert_id();
            }
        }
        return false;
    }
/**
 * Execute 'UPDATE' query
 *
 * @param array $data
 * @param array $conditions Optional.
 * @access public
 * @return bool
 * @todo Support of JOIN
 */
    function update($data, $conditions=array())
    {
        $update = array();
        
        if (!is_array($data)) return false;
        //if (array_key_exists($data[$this->primaryKey], $data)) return false;
        
        foreach ($this->schema as $field)
        {
            if (isset($data[$field['name']]))
            {
                if (!is_array($data[$field['name']]))
                {
                    $update[] = "`".$field['name']."` = '" . $this->escape($data[$field['name']], null, $field['type']) . "'";
                } else {
                    # Indexes: 0 - value,... @TODO
                    $update[] = "`".$field['name']."` = " . $data[$field['name']][0];
                }
            }
        }
        if (count($update) > 0)
        {
            $sql_limit = NULL;
            if (is_array($conditions) && count($conditions) > 0)
            {
                $opts = $this->buildOpts($conditions);
                $sql_conditions = $opts['conditions'];
                if (array_key_exists('limit', $conditions) && !$this->hasColumn('limit') && (int) $conditions['limit'] > 0)
                {
                    $sql_limit = "LIMIT " . (int) $conditions['limit'];
                }
            } else {
                $sql_conditions = " AND `".$this->primaryKey."` = '".$data[$this->primaryKey]."'";
                $sql_limit = "LIMIT 1";
            }

            // Oh, yes
            if ($ownerId = $this->getOwnerId()) {
                $sql_conditions .= " AND `owner_id` = $ownerId";
            }

            mysql_query("UPDATE `".$this->getTable()."`
                SET " . join(",", $update) . "
                WHERE 1=1 $sql_conditions
                $sql_limit
            ") or die(mysql_error());
            if (mysql_affected_rows() > 0)
            {
                return true;
            }
        }
        return false;
    }
/**
 * Execute 'DELETE' query
 *
 * @param array|scalar $id Required - primary key or array with conditions
 * @access public
 * @return int
 * @todo Support of JOIN
 */
    function delete($id)
    {
        $sql_conditions = NULL;
        $row_count = NULL;
        if (is_array($id) && count($id) > 0)
        {
            $opts = $this->buildOpts($id);
            $sql_conditions = $opts['conditions'];
        } else {
            $id = $this->escape($id, $this->primaryKey);
            $sql_conditions = "AND `".$this->primaryKey."` = '$id'";
            $row_count = "LIMIT 1";
        }
        // Oh, yes
        if ($ownerId = $this->getOwnerId()) {
            $sql_conditions .= " AND `owner_id` = $ownerId";
        }
        
        $sql = "DELETE FROM `".$this->getTable()."` WHERE 1=1 $sql_conditions $row_count";
        mysql_query($sql) or die(mysql_error());
        return mysql_affected_rows();
    }
/**
 * Get type of column
 *
 * @param string $column Column name
 * @access public
 * @return false|string
 */
    function getColumnType($column)
    {
        foreach ($this->schema as $col)
        {
            if ($col['name'] == $column)
            {
                return $col['type'];
            }
        }
        return false;
    }
/**
 * Escape value
 *
 * @param mixed $value
 * @param string $column
 * @param string $type
 * @access public
 * @return int|float|string
 */
    function escape($value, $column=null, $type=null)
    {
        if (is_null($type) && !is_null($column))
        {
            $type = $this->getColumnType($column);
        }
        
        switch ($type)
        {
            case 'null':
                return $value;
                break;
            case 'int':
            case 'smallint':
            case 'tinyint':
            case 'mediumint':
            case 'bigint':
                return intval($value);
                break;
            case 'float':
            case 'decimal':
            case 'double':
            case 'real':
                return floatval($value);
                break;
            case 'string':
            case 'varchar':
            case 'enum':
            case 'set':
            case 'char':
            case 'text':
            case 'tinytext':
            case 'mediumtext':
            case 'longtext':
            case 'date':
            case 'datetime':
            case 'year':
            case 'time':
            case 'timestamp':
            default:
                return $this->escapeString($value);
                break;
        }
    }
/**
 * Add join(s) to SQL query. Use in conjunction with Model::get() or Model::getAll()
 *
 * @param array $member
 * @param string $table Table name
 * @param string $alias Table alias
 * @param array $clauses
 * @param array $fields List of field names to select. Support aliases. Example: 'TR.role' or 'TR.role.role_title'
 * @param string $type Optional. Default value is 'left'. Possible values are: 'left', 'inner', 'cross', 'right', 'natural', 'straight'
 * @access public
 * @return void
 * @todo Support of <, >, <=, >=, etc.; Support of OR, XOR, etc.
 * @example
 * $UserModel->addJoin($UserModel->joins, $RoleModel->getTable(), 'TR', array('TR.id' => 't1.role_id'), array('TR.role'));
 * $UserModel->get(5);
 *
 */
    function addJoin(&$member, $table, $alias, $clauses=array(), $fields = array(), $type='left')
    {
        if (in_array($type, $this->joinTypes))
        {
            $type_join = ($type !== 'straight') ? strtoupper($type) : strtoupper($type) . "_JOIN";
            if (in_array($type, array('left', 'right')))
            {
                $type_join .= " OUTER";
            }
            $join_str  = ($type !== 'straight') ? 'JOIN' : null;
             
        } else {
            return false;
        }
        if (count($clauses) > 0)
        {
            $clauses_arr = array();
            foreach ($clauses as $k => $v)
            {
                $key = (strpos($k, ".") !== false) ? explode(".", $k) : $k;
                $val = (strpos($v, ".") !== false) ? explode(".", $v) : $v;
                
                $l_clause = (is_array($key)) ? "`" . join("`.`", $key) . "`" : $key;
                $r_clause = (is_array($val)) ? "`" . join("`.`", $val) . "`" : $val;
                
                # TODO support of <, >, <=, >=, etc.
                $clauses_arr[] = "$l_clause = $r_clause";
                //$clauses_arr[] = "$k = $v";
            }
            # TODO support of OR, XOR, etc.
            $clauses_str = join(" AND ", $clauses_arr);
        } else {
            return false;
        }
        
        $member[] = $type_join . " " . $join_str . " `" .$table . "` AS `" . $alias . "` ON " . $clauses_str . "|" . join(",", $fields);
        $member = array_unique($member);
    }
/**
 * Add SubQuery to SQL query. Use in conjunction with Model::get() or Model::getAll()
 *
 * @param array $member
 * @param string $query SQL query
 * @param string $alias Alias
 * @access public
 * @static
 * @return void
 * @example
 * $RoleModel->addSubQuery($RoleModel->subqueries, "SELECT COUNT(*) FROM `".$UserModel->getTable()."` WHERE `role_id` = `t1`.`id` LIMIT 1", "cnt");
 * $RoleModel->getAll();
 */
    function addSubQuery(&$member, $query, $alias)
    {
        $member[] = array('query' => $query, 'alias' => $alias);
        //FIXME Remove duplicates. Below didn't work as I expect!
        //$member = array_unique($member);
    }
/**
 * Check if the given $columnName exists into current schema
 *
 * @param string $columnName
 * @access public
 * @return bool
 */
    function hasColumn($columnName)
    {
        foreach ($this->schema as $field)
        {
            if ($field['name'] == $columnName)
            {
                return true;
            }
        }
        return false;
    }
    
    function execute($sql)
    {
        $arr = array();
        if ($this->debug)
        {
            printf("<pre>%s</pre>", $sql);
        }
        $r = mysql_query($sql) or die(mysql_error());
        $nr = @mysql_num_rows($r);
        if ($nr > 1)
        {
            $i = 0;
            while ($row = mysql_fetch_assoc($r))
            {
                $arr[$i] = $row;
                $i++;
            }
        } elseif ($nr == 1) {
            $arr[0] = mysql_fetch_assoc($r);
        }
        return $arr;
    }

    function getAllPair($key, $value, $opts=array())
    {
        $arr = array();
        $_arr = $this->getAll($opts);
        foreach ($_arr as $item)
        {
            $arr[$item[$key]] = $item[$value];
        }
        return $arr;
    }
    
    function truncate()
    {
        $query = sprintf("TRUNCATE TABLE `%s`", $this->getTable());
        if ($this->debug)
        {
            printf("<pre>%s</pre>", $query);
        }
        mysql_query($query) or die(mysql_error());
        //return mysql_affected_rows();
    }
}
?>
