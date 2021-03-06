<?php

namespace ApCode\Database;

interface DatabaseInterface
{
    /**
     * Выполнить запрос с заданными параметрами
     * 
     * @param string $sql
     * @param array $params
     * @return \ApCode\Database\QueryResultInterface
     */
    function query($sql, $params = []);
    
    /**
     * Заключить в кавычки значение
     * @param mixed $data
     */
    function quote($data);
    
    /**
     * Заключить в кавычки название таблицы, столбца или базы данных
     * @param string $data
     */
    function quoteName($data);
    
    /**
     * Получить последний вставленный Id
     */
    function lastInsertId();
}