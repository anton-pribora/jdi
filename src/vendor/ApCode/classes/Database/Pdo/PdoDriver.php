<?php

namespace ApCode\Database\Pdo;

use ApCode\Database\DatabaseInterface;
use PDO;

class PdoDriver implements DatabaseInterface
{
    private $dsn;
    private $login;
    private $password;
    private $options;
    
    /**
     * @var PDO
     */
    private $pdo;
    
    private $totalQueries = 0;
    private $totalTime = 0;
    
    public function __construct($dsn, $login, $password, array $options = [])
    {
        $this->dsn      = $dsn;
        $this->login    = $login;
        $this->password = $password;
        
        if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
            $options += [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="ANSI"',
            ];
        }
        
        $this->options = $options + [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
    }
    
    private function connect()
    {
        $this->pdo = new PDO($this->dsn, $this->login, $this->password, $this->options);
    }
    
    public function disconnect()
    {
        $this->pdo = null;
    }
    
    public function pdo()
    {
        if (empty($this->pdo)) {
            $this->connect();
        }
        
        return $this->pdo;
    }
    
    public function driverName()
    {
        return $this->pdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }
    
    public function checkConnection()
    {
        try {
            $this->query('SELECT 1')->fetchAllRows();
        } catch (\PDOException $e) {
            $this->connect();
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \ApCode\Database\DatabaseInterface::query()
     */
    public function query($sql, $params = array())
    {
        if (empty($this->pdo)) {
            $this->connect();
        }
        
        $startTime = microtime(true);
        
        if ( $params ) {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
        }
        else { 
            $statement = $this->pdo->query($sql);
        }
        
        $elapsed = microtime(true) - $startTime;
        
        $this->totalQueries += 1;
        $this->totalTime += $elapsed;
        
        return new QueryResult($statement, $elapsed);
    }

    /**
     * {@inheritDoc}
     * @see \ApCode\Database\DatabaseInterface::quote()
     */
    public function quote($data)
    {
        if (empty($this->pdo)) {
            $this->connect();
        }
        
        return $this->pdo->quote($data);
    }

    /**
     * {@inheritDoc}
     * @see \ApCode\Database\DatabaseInterface::quoteName()
     */
    public function quoteName($data)
    {
        return '"'. strtr($data, ['"' =>'""', '.' => '"."']) .'"';
    }

    /**
     * {@inheritDoc}
     * @see \ApCode\Database\DatabaseInterface::lastInsertId()
     */
    public function lastInsertId()
    {
        if (empty($this->pdo)) {
            $this->connect();
        }
        
        return $this->pdo->lastInsertId();
    }
    
    public function totalQueries()
    {
        return $this->totalQueries;
    }
    
    public function totalTime()
    {
        return $this->totalTime;
    }
}