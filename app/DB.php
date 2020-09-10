<?php

namespace app;

require_once '../config.php';

use mysqli;

/*
 * Модель DB служит для установление связи с базой данных которое устанавливается автоматически при загрузки обьекта
 * даного класса. Данные для подключение к БД находятся в файле config.php в виде глобальных переменных.
 * Так же модель находится метод для отправки запросов в БД query.
 * метод для получения всех записей из баззы данных fetchAll()
 * метод для получения всех записей из баззы данных в виде массива fetchArray()
 * метод для разрыва соединения с БД close()
 * метод который возвращает число строк из базы данных numRows()
 * метод который возвращает число добавленных строк в БД, которые были затронуты последним запросом affectedRows()
 * метод который возвращает последнее значение поля id которые были затронуты последним запросом lastInsertID()
 * метод который отлавливает полученые ошиьки error()
 * метод для проверки имеет ли строку передаваемый аргумент _gettype()
 * */

class DB
{
    //  connection является объктом класса mysqli которое осузествляет подключение в БД
    protected $connection;
    //  query осуществляет манипуляции с запросами и закрытия соединении с БД после выполнения запросов
    protected $query;
    //  show_errors служит для отлавливания ошибок. Является объктом класса Exception
    protected $show_errors = TRUE;
    //  query_closed служит для проверки закрыто соединение с БД после последней связи или нет
    protected $query_closed = TRUE;
    //  счетчик запросов
    public $query_count = 0;

    public function __construct($dbhost = DB_HOST, $dbuser = DB_USER, $dbpass = DB_PASSWORD, $dbname = DB_NAME, $charset = 'utf8')
    {
        // создания подключения к таблици базы данных
        $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        // выполнить error если произошла ошибка соединения
        if ($this->connection->connect_error) {
            $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        // если подключение прошло удачно добавить в подключение кодировку utf8
        $this->connection->set_charset($charset);
    }

    public function query($query)
    {
        // если подключение к БД ранее не было закрыто то закрыть
        if (!$this->query_closed) {
            $this->query->close();
        }
        // если есть подключение к БД записать в переменную query подготовленный запрос
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
            // запуск подготовленного запроса на выполнения
            $this->query->execute();
            // если есть ошибка вызвать метод error для вывода данной ошибки
            if ($this->query->errno) {
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
        // получение метаданных таблицы после запроса
        $meta = $this->query->result_metadata();
        // получение имен столбцов из метаданных таблицы и запись в массив
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        // методом обратного вызова вызываем функцию query которой передаем параметры столбцов полученых ранее
        call_user_func_array(array($this->query, 'bind_result'), $params);
        // массив в который будут записаны строки с данными из таблицы
        $result = array();
        // получаем следующую строку индексированную как именнами таблицы так и их номерами
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

