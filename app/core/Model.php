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
     * Num Rows
     * 
     * Counting records rows 
     * 
     * @var int $num_rows
     */
    private $num_rows = 0;

    /**
     * Where 
     * 
     * Generate the WHERE
     * 
     * @var array $where
     */
    private $where;

    /**
     * This function enables you to set values for inserts or updates.
     * 
     * @var array $set
     */
    private $set;

    /** 
     * WHERE IN
	 *
	 * Generates a WHERE field IN('item', 'item') SQL query,
	 * joined with 'AND' if appropriate.
     * 
     * @var array $where_in
     */
    private $where_in;

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
     * SELECT | FIELD | FROM  | WHERE | WHERE IN 
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
        
        // --------------------------------------------------------------------------------
        
        /**
         * Start Where 
         */
        if(!empty($this->where))
        {
            if(count($this->where) === 1)
            {
                $sql .= " WHERE ";
    
                foreach($this->where as $field => $value)
                {
                    $where[] = $field . " = '$value'";
                }
    
                $sql .= implode(" ",$where);
            }
            
            if(count($this->where) > 1)
            {
                $sql .= " WHERE ";
    
                foreach($this->where as $key => $value)
                {
                    $where[]  = " $key = '$value'";
                }
    
                $sql .= implode(" AND ",$where);
            }
        }

        /**
         * End Where 
         */

        // --------------------------------------------------------------------------------

        /**
         * Start Where IN
         */
        if(empty($this->where))
        {
            if(count($this->where_in) === 1)
            {
                $sql .= " WHERE ";

                foreach($this->where_in as $key => $value)
                {
                    $where_in[] = $value;
                }

                $sql .= implode("",$where_in);
            }
            else if(count($this->where_in) > 1)
            {
                $sql .= " WHERE ";
                foreach($this->where_in as $key => $value)
                {
                    $where_in[] = $value;
                }

                $sql .= implode(" AND ",$where_in);
            }
        }
        else 
        {
            if(count($this->where_in) === 1)
            {
                foreach($this->where_in as $key => $value)
                {
                    $where_in[] = $value;
                }

                $sql .= " AND " . implode("",$where_in);
            }
            else if(count($this->where_in) > 1)
            {
                foreach($this->where_in as $key => $value)
                {
                    $where_in[] = $value;
                }

                $sql .= " AND " .  implode(" AND ",$where_in);
            }
        }
        
        /**
         * End Where IN
         */

        return $sql;
    }

    /**
     * Execute a select query
     * 
     * @return object
     */
    public function run_select()
    {
        try 
        {
            $stmt = $this->conn->prepare($this->compile_select());
            $stmt->execute();
            $this->num_rows     = $stmt->rowCount();

            return $stmt;
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
        $this->result_array = $this->run_select()->fetchAll(PDO::FETCH_ASSOC);

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

        return $this->field_select;

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
     * @return string 
     */
    public function last_query()
    {
        return $this->run_select()->queryString;
    }

    /**
     * Result All Json
     * 
     * Query result. "json" version
     * 
     * @return json
     */
    public function result_json()
    {
        header('Content-Type: application/json');
        return json_encode($this->result_array());        
    }
    
    /**
     * Result All Object
     * 
     * Query result. "object" version
     * 
     * @return object
     */
    public function result_object()
    {
        return (object)$this->run_select()->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * WHERE
     * 
     * Generate the WHERE portion of the query 
     * Separates multiple call with AND 
     * 
     * @param string $field
     * @param string $value
     */
    public function where($field,$value)
    {
        if(is_string($field))
        {
            $field = [$field => $value];
        }

        foreach($field as $key => $val)
        {
            $this->where[$key] = $val;
        }

        return $this->where;
        
    }   

    /**
	 * WHERE IN
	 *
	 * Generates a WHERE field IN('item', 'item') SQL query,
     * 
     * @param string $field
     * @param array $value
     */
    public function where_in($field = "",$value = [])
    {
        if(!is_array($value))
        {
            $value = array($value);
        }
        
        $where_in = array();

        $i = 0;
        foreach($value as $val)
        {
            $where_in[$i] =  "'$val'";
            $i++;
        }
        

        $value = $field . " IN (" . implode(',',$where_in) . ")"; 

        $this->where_in[] = $value;
        

        return $this->where_in;
    }

    /**
     * INSERT 
     * 
     * Generates an insert string based on the data you supply, and runs the query. 
     * You can either pass an array or an object to the function. Here is an example using an array:
     * 
     * @param string $table_name
     * @param array $data
     */
    public function insert($table_name = "",$data = [])
    {
        if(empty($data))
        {
            foreach($this->set as $key => $value)
            {
                $data[$key] = $value;
            }
        }

        $array_keys     = array_keys($data);
        $array_values   = array_values($data);


        $field  = implode(',',$array_keys);
        $values = str_repeat('?,',count($array_values)-1) . "?";

        $sql = "INSERT INTO $table_name ($field) VALUES ($values)";
        
        $this->num_rows = $this->run_query($sql,$array_values)->rowCount();
    }

    /**
     * This function is used to run the query
     * 
     * @param string $query
     * @param array  $bindValue
     */
    public function run_query($query,$bindValue = [])
    {
        try 
        {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($bindValue);
            $this->num_rows = $stmt->rowCount();
            
            return $stmt;
        }
        catch(PDOException $e)
        {
            BaseException::getException($e);
        }
    }

    /**
     * This function enables you to set values for inserts or updates.
     * 
     * @param string $field
     * @param string $value
     */
    public function set($field,$value)
    {
        if(is_string($field))
        {
            $this->set[$field] = $value;
        }
    }
    
    /**
     * Generates an update string and runs the query based on the data you supply. 
     * 
     * @param string $table_name
     * @param array $data
     */
    public function update($table_name = "",$data = [])
    {
        if(empty($data))
        {
            foreach($this->set as $key => $value)
            {
                $set[$key] = $key . " = ?";
            }
            
            $array_values = array_values(array_merge($this->set,$this->where));
        }
        else 
        {
            foreach($data as $key => $value)
            {
                $set[$key] = $key . " = ?"; 
            }

            $array_values = array_values(array_merge($data,$this->where));
        }

        foreach($this->where as $key => $value)
        {
            $where[] = $key . " = ?";
        }

        $sql  = "UPDATE $table_name SET " . implode(",",$set);
        $sql .= " WHERE " . implode('',$where);

        $this->num_rows = $this->run_query($sql,$array_values)->rowCount();
    }

    /**
     * Generates a delete SQL string and runs the query.
     * 
     * @param string $table_name
     * @param array $data
     */
    public function delete($table_name,$data = [])
    {
        if(empty($data))
        {
            foreach($this->where as $key => $value)
            {
                $set[$key] = $key . " = ?";
            }

            $array_values = array_values($this->where);
        }
        else 
        {
            foreach($data as $key => $value)
            {
                $set[$key] = $key . " = ?";
            }

            $array_values = array_values($data);
        }

        $sql = "DELETE FROM $table_name WHERE " . implode('',$set);

        $this->num_rows = $this->run_query($sql,$array_values)->rowCount();
    }
    
}
