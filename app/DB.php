<?php

namespace app;

require_once '../config.php';

use mysqli;

/*
 * The DB model is used to establish a connection with the database, which is installed automatically when the object is loaded
 * of this class. The data for connecting to the database are located in the config.php file as global variables.
 * The model also contains a method for sending queries to the query database.
 * method for getting all records from the database fetchAll ()
 * method to get all records from the database as an array fetchArray ()
 * method to close the connection to the database close ()
 * method, returns the number of rows from the database numRows ()
 * method, returns the number of rows added to the database that were affected by the last affectedRows () query
 * method, returns the last value of the id field that was touched by the last request lastInsertID ()
 * method that catches received errors error ()
 * method for checking if a string has a passed argument _gettype ()
 * */

class DB
{
    //  connection property for initializing an object of the mysqli class that connects to the database
    protected $connection;
    //  query manipulates queries and closes the database connection after executing queries
    protected $query;
    //  show_errors is used to catch errors. Is an Exception object
    protected $show_errors = TRUE;
    //  query_closed is used to check whether the connection to the database is closed after the last connection or not
    protected $query_closed = TRUE;
    //  Request counter
    public $query_count = 0;

    public function __construct($dbhost = DB_HOST, $dbuser = DB_USER, $dbpass = DB_PASSWORD, $dbname = DB_NAME, $charset = 'utf8')
    {
        // Making a connection to a database table
        $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        // Execute error () if there was a connection error
        if ($this->connection->connect_error) {
            $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        // If the connection was successful, add utf8 encoding to the connection
        $this->connection->set_charset($charset);
    }

    public function query($query)
    {
        // If the connection to the database was not closed earlier, then close
        if (!$this->query_closed) {
            $this->query->close();
        }
        // If there is a connection to the database, write the prepared query to the query variable
        if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types .= $this->_gettype($args[$k][$j]);
                            $args_ref[] = &$a;
                        }
                    } else {
                        $types .= $this->_gettype($args[$k]);
                        $args_ref[] = &$arg;
                    }
                }
                array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            // Running a prepared execution query
            $this->query->execute();
            // If there is an error, call the error method to display this error
            if ($this->query->errno) {
                $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_closed = FALSE;
            $this->query_count++;
        } else {
            // If there is an error, call the error method to display this error
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }


    public function fetchAll($callback = null)
    {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    public function fetchArray()
    {
        $params = array();
        $row = array();
        // Retrieving table metadata after query
        $meta = $this->query->result_metadata();
        // Get column names from table metadata and write to array
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        // Using the callback method, we call the query function of which we pass the parameters of the columns
        // obtained earlier
        call_user_func_array(array($this->query, 'bind_result'), $params);
        // An array into which rows with data from the table will be written
        $result = array();
        // We get the next row indexed both by the table names and their numbers
        while ($this->query->fetch()) {
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    public function close()
    {
        return $this->connection->close();
    }

    public function numRows()
    {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    public function affectedRows()
    {
        return $this->query->affected_rows;
    }

    public function lastInsertID()
    {
        return $this->connection->insert_id;
    }

    /**
     * @param $error
     * @throws \Exception
     */
    public function error($error)
    {
        if ($this->show_errors) {
            throw new \Exception($error);
        }
    }

    private function _gettype($var)
    {
        if (is_string($var)) return 's';
        if (is_float($var)) return 'd';
        if (is_int($var)) return 'i';
        return 'b';
    }
}

