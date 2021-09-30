<?php 

namespace app\core;

use app\core\exception\BaseException;
use PDOException;
use PDO;

/*
|--------------------------------------------------------------------------
| Core Model
|--------------------------------------------------------------------------
| The model class is used to deal with SQL 
| queries and a query builder is created here
| 
*/

class Model extends Database
{
    /**
     * The active PDO connection
     * 
     * @var pdo $conn
     */
    private $conn;

    /**
     * The name of the field you want to display
     * 
     * @var array $field_select 
     */
    private $field_select;

    /**
     * Table name for from method
     * 
     * @var string $table_name
     */
    private $table_name;

    /**
     * Query result. "array" version
     * 
     * @var string $result_array 
     */
    private $result_array;

    /**
     * Run Select
     * 
     * Execute a select query
     * 
     * @var object $run_select
     */
    private $run_select;

    /**
     * Num Rows
     * 
     * Counting records rows 
     * 
     * @var int $num_rows
     */
    private $num_rows = 0;


    public function __construct()
    {
        $this->conn = parent::__construct();

        return $this;
    }

    /**
	 * Get
	 *
	 * Compiles the select statement based on the other functions called
	 * and runs the query
     * 
     * @param string $table     table name
     * @param string $limit     string the limit clause 
     * @param string $offset    string the offset clause 
     */
    public function get($table = "",$limit = null,$offset = null)
    {
        if($table !== "")
        {
            $this->from($table);
        }

        $this->compile_select();
        $this->run_select();
    }

    /**
     * Compile Select
     * 
     * Create a select query
     * 
     * SELECT | FIELD | FROM 
     * 
     * @return string $sql
     */
    public function compile_select()
    {
        $sql = "SELECT ";

        /* 
         * Start SELECT {field}
        */
        if(count($this->field_select) === 0)
        {
            $sql .= "*";
        }
        else 
        {
            foreach($this->field_select as $key => $value)
            {
                $this->field_select[$key] = filter_var($value,FILTER_DEFAULT);
            }

            $sql .= implode(',',$this->field_select);
        }
        /* 
         * End SELECT {field}
        */

        // --------------------------------------------------------------------------------

        /**
         * Start FROM {table_name}
         */
        try 
        {
            if($this->table_name != "")
            {
                $sql .= ' FROM ' . $this->table_name;
            }
            else 
            {
                throw new BaseException('Table name not found',404);
            }
        }
        catch(BaseException $e)
        {
            BaseException::getException($e);
        }
        /**
         * End FROM {table_name}
         */

        return $sql;
    }

    /**
     * Execute a select query
     * 
     */
    public function run_select()
    {
        try 
        {
            $stmt = $this->conn->prepare($this->compile_select());
            $stmt->execute();
            $this->num_rows     = $stmt->rowCount();
            $this->run_select   = $stmt;
        }
        catch(PDOException $e)
        {
            BaseException::getException($e);
        }
    }

    /**
     * Num Rows 
     * 
     * Counting records rows
     * 
     * @return int 
     */
    public function num_rows()
    {
        return $this->num_rows;
    }

    /**
     * Result All Array 
     * 
     * Query result. "array" version
     * 
     * @return array 
     */
    public function result_array()
    {
        $this->result_array = $this->run_select->fetchAll(PDO::FETCH_ASSOC);

        return $this->result_array;
    }

    /**
	 * Select
	 *
	 * Generates the SELECT portion of the query
	 *
	 * @param string $field the name of the field you want to display
     */
    public function select($field = '*')
    {
        if(is_string($field))
        {
            $select =  explode(',',$field);
        }

        foreach($select as $val)
        {
            $val = trim($val);

            if($val !== "")
            {
                $this->field_select[] = $val;
            }
        }

        return $this;

    }

    /**
	 * From
	 *
	 * Generates the FROM portion of the query
     * 
     * @param string $table table name 
     */
    public function from($table)
    {
        $this->table_name = $table;
    }

    /**
     * To view the currently used query
     * 
     */
    public function last_query()
    {
        return $this->run_select->queryString;
    }
    
}
