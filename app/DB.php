<?php

namespace app;

require_once '../config.php';

use mysqli;

class DB
{
    // класс для подключение к БД.  Класс использует обьектно ориентированый стиль расширения mysqli
    protected $connection;
    protected $query;
    protected $show_errors = TRUE;
    protected $query_closed = TRUE;
    public $query_count = 0;

    public function __construct($dbhost = DB_HOST, $dbuser = DB_USER, $dbpass = DB_PASSWORD, $dbname = DB_NAME, $charset = 'utf8')
    {
        $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        // создания подключения к таблици базы данных
        if ($this->connection->connect_error) {
            // выполнить error если произошла ошибка соединения
            $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        $this->connection->set_charset($charset);
        // если подключение прошло удачно добавить в подключение кодлировку utf8
    }

    public function query($query)
    {
        // метод для отправки запроса в БД
        if (!$this->query_closed) {
            $this->query->close();
        }
        if ($this->query = $this->connection->prepare($query)) {
            // если есть connect с БД записать в переменную query подготовленный запрос
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
            // запусе подготовленного запроса на выполнения
            if ($this->query->errno) {
                // если есть ошибка вызвать метод error для вывода данной ошибки
                $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_closed = FALSE;
            $this->query_count++;
        } else {
            // если есть ошибка вызвать метод error для вывода данной ошибки
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }


    public function fetchAll($callback = null)
    {
        // метод получает все строки с базы данных в виже хеш-таблицы
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
        // метод который возвращает массив данных каждого столбца таблицы
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        // получение метаданных таблицы после запроса
        while ($field = $meta->fetch_field()) {
            // получение имен столбцов из метаданных таблицы и запись в массив
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        // методом обратного вызова вызываем функцию query которой передаем параметры столбцов полученых ранее
        $result = array();
        // массив в который будут записаны строки с данными из таблицы
        while ($this->query->fetch()) {
            // получаем следующую строку индексированную как именнами таблицы так и их номерами
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
        // метод для зарытия соединения с базой
        return $this->connection->close();
    }

    public function numRows()
    {
        // метод возвращает число строк из запроса в БД
        $this->query->store_result();
        return $this->query->num_rows;
    }

    public function affectedRows()
    {
        // метод возвращает число добавленных строк в БД, которые были затронуты последним запросом
        return $this->query->affected_rows;
    }

    public function lastInsertID()
    {
        // PDO метод возвращает посдледней значение поля id добавленной строки запроса
        return $this->connection->insert_id;
    }

    /**
     * @param $error
     * @throws \Exception
     */
    public function error($error)
    {
        // метод отлавливает ошибки
        if ($this->show_errors) {
            throw new \Exception($error);
        }
    }

    private function _gettype($var)
    {
        // метод для провекри на строки
        if (is_string($var)) return 's';
        if (is_float($var)) return 'd';
        if (is_int($var)) return 'i';
        return 'b';
    }

}

