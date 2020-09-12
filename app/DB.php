<?php

namespace app;

require_once '../config.php';

use mysqli;

/*
 * The DB model is used to establish a connection with the database, which is installed automatically when the
 * object is loaded of this class. The data for connecting to the database are located in the config.php
 * file as global variables.
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

    /**
     * DB constructor.
     * @param string $dbhost
     * @param string $dbuser
     * @param string $dbpass
     * @param string $dbname
     * @param string $charset
     * @throws \Exception
     */
    public function __construct($dbhost = DB_HOST, $dbuser = DB_USER,
                                $dbpass = DB_PASSWORD, $dbname = DB_NAME,
                                $charset = 'utf8')
    {
        $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($this->connection->connect_error) {
            $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        $this->connection->set_charset($charset);
    }

    /**
     * @param $query
     * @return $this
     * @throws \Exception
     */
    public function query($query)
    {
        if (!$this->query_closed) {
            $this->query->close();
        }
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
            $this->query->execute();
            if ($this->query->errno) {
                $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_closed = FALSE;
            $this->query_count++;
        } else {
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }


    /**
     * @param null $callback
     * @return array
     */
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

    /**
     * @return array
     */
    public function fetchArray()
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
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    /**
     * @return bool
     */
    public function close()
    {
        return $this->connection->close();
    }

    /**
     * @return mixed
     */
    public function numRows()
    {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    /**
     * @return mixed
     */
    public function affectedRows()
    {
        return $this->query->affected_rows;
    }

    /**
     * @return mixed
     */
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

    /**
     * @param $var
     * @return string
     */
    private function _gettype($var)
    {
        if (is_string($var)) return 's';
        if (is_float($var)) return 'd';
        if (is_int($var)) return 'i';
        return 'b';
    }
}

