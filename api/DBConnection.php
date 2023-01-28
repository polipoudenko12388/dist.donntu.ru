<?php

// Используем для подключения к базе данных MySQL
class DateBaseConnection
{
    // Учётные данные базы данных
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $connection;

    // Получаем соединение с базой данных
    public function __construct($host, $db_name, $username, $password)
    {
        $this->connection = null;

        $this->host = $host; $this->db_name = $db_name; 
        $this->username = $username; $this->password = $password; 
        
         //  массив опций - как выдавать ошибки (в виде исключений)
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try 
        {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, $opt);
        } catch (PDOException $exception) { echo "Ошибка соединения с БД: " . $exception->getMessage(); }
    }
}