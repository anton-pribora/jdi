<?php

namespace Jdi\Task;

use ApCode\Misc\Pagination;
use Jdi\Task;

class OwnerRepository
{
    /**
     * @param array $params
     * @param Pagination $pagination
     * @return \Jdi\Task\Run[]
     */
    public static function findMany($params, Pagination $pagination = NULL)
    {
        $params = array_filter($params);
        
        $where   = self::buildWhere($params);
        $orderBy = self::buildOrderBy($params);

        if (!empty($params)) {
            throw new \Exception('Invalid param(s) '. join(', ', array_keys($params)));
        }
        
        $sql = 'SELECT "owner" FROM "jdi_task_owner"';

        if ($where) {
            $sql .= ' WHERE '. join(" AND ", $where);
        }

        if ($orderBy) {
            $sql .= ' ORDER BY '. join(', ', $orderBy);
        }

        if ($pagination) {
            $sql2 = 'SELECT COUNT("id") FROM "'. Run::tableName() .'"';
            
            if ($where) {
                $sql2 .= ' WHERE '. join(" AND ", $where);
            }
            
            $pagination->setTotalItems(Db()->query($sql2)->fetchValue());
        }
        
        if ($pagination && $pagination->limit()) {
            $sql .= ' LIMIT '. intval($pagination->limit()) .' OFFSET '. intval($pagination->startFrom());
        }

        $result = Db()->query($sql)->fetchColumn();

        if ($pagination) {
            $pagination->setTotalItems(Db()->query('SELECT FOUND_ROWS()')->fetchValue());
        }

        return $result;
    }

    /**
     * @param array $params
     * @return \Jdi\Task\Run
     */
    public static function findOne($params)
    {
        $pagination = new Pagination(['limit' => 1]);
        $list = self::findMany($params, $pagination);

        return current($list);
    }
    
    public static function insert(Task $task, $owner)
    {
        if (empty($task->id())) {
            throw new \Exception("You should save task before add owners");
        }
        
        $sql = 'INSERT INTO "jdi_task_owner" ("task_id", "owner") VALUES (?, ?)';
        Db()->query($sql, [$task->id(), $owner]);
    }
    
    public static function delete(Task $task, $owners = NULL)
    {
        if (empty($task->id())) {
            return ;
        }
        
        $where = ['"task_id" = '. Db()->quote($task->id())];
        
        if ($owners) {
            $values = [];
            
            foreach ((array) $owners as $value) {
                $values[] = Db()->quote($value);
            }
            
            $where[] = '"owner" IN (' . $values . ')';
        }
        
        $sql = 'DELETE FROM "jdi_task_owner" WHERE ' . join(' AND ', $where);
        Db()->query($sql);
    }

    private static function buildWhere(&$params)
    {
        $where = [];
        
        if (isset($params['taskId'])) {
            $where[] = '"task_id" = '. Db()->quote($params['taskId']);
            unset($params['taskId']);
        }
        
        return $where;
    }

    private static function buildOrderBy(&$params)
    {
        $orderBy = [];
        
        $ascdesc = function($var, $default) {
            switch (mb_strtolower($var)) {
                case 'asc': return 'ASC';
                case 'desc': return 'DESC';
            }
            return $default;
        };

        if (empty($params['order_by'])) {
            unset($params['order_by']);
        }

        return $orderBy;
    }
}