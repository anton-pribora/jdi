<?php

namespace Jdi;

use ApCode\Misc\Pagination;

class TaskRepository
{
    /**
     * @param array $params
     * @param Pagination $pagination
     * @return \Jdi\Task[]
     */
    public static function findMany($params, Pagination $pagination = NULL)
    {
        $params = array_filter($params);
        
        $where   = self::buildWhere($params);
        $orderBy = self::buildOrderBy($params);

        if (!empty($params)) {
            throw new \Exception('Invalid param(s) '. join(', ', array_keys($params)));
        }
        
        $sql = 'SELECT "id" FROM "'. Task::tableName() .'"';

        if ($where) {
            $sql .= ' WHERE '. join(" AND ", $where);
        }

        if ($orderBy) {
            $sql .= ' ORDER BY '. join(', ', $orderBy);
        }

        if ($pagination) {
            $sql2 = 'SELECT COUNT("id") FROM "'. Task::tableName() .'"';
            
            if ($where) {
                $sql2 .= ' WHERE '. join(" AND ", $where);
            }
            
            $pagination->setTotalItems(Db()->query($sql2)->fetchValue());
        }
        
        if ($pagination && $pagination->limit()) {
            $sql .= ' LIMIT '. intval($pagination->limit()) .' OFFSET '. intval($pagination->startFrom());
        }

        $idList = Db()->query($sql)->fetchColumn();
        
        $result = [];

        foreach ($idList as $id) {
            $result[] = Task::getInstance($id);
        }

        return $result;
    }

    /**
     * @param array $params
     * @return \Jdi\Task
     */
    public static function findOne($params)
    {
        $pagination = new Pagination(['limit' => 1]);
        $list = self::findMany($params, $pagination);

        return current($list);
    }
    
    public static function checkRunningLimit($limit)
    {
        $sql = 'SELECT COUNT("id") FROM "'. Task::tableName() .'" WHERE "status" = ?';
        $res = Db()->query($sql, [Task::STATUS_RUNNING]);
        
        return $res->fetchValue() < $limit;
    }
    
    public static function delete(Task $task)
    {
        $task->stdin()->remove();
        
        foreach ($task->runs() as $run) {
            $run->delete();
        }
        
        $sql = 'DELETE FROM "'. Task::tableName() .'" WHERE "id" = ? LIMIT 1';
        $res = Db()->query($sql, [$task->id()]);
        
        $sql = 'DELETE FROM "jdi_task_owner" WHERE "task_id" = ?';
        $res = Db()->query($sql, [$task->id()]);
    }

    private static function buildWhere(&$params)
    {
        $where = [];
        
        if (isset($params['id'])) {
            $where[] = '"id" = '. Db()->quote($params['id']);
            unset($params['id']);
        }
        
        if (isset($params['status'])) {
            if (is_array($params['status'])) {
                $array = array_map([Db(), 'quote'], $params['status']);
                $where[] = '"status" IN ('. join(', ', $array) .')';
            } else {
                $where[] = '"status" = '. Db()->quote($params['status']);
            }
            
            unset($params['status']);
        }
        
        if (isset($params['run_at<='])) {
            $where[] = '"run_at" <= '. Db()->quote($params['run_at<=']);
            unset($params['run_at<=']);
        }
        
        if (isset($params['owner'])) {
            $clause = [];
            
            foreach ((array) $params['owner'] as $value) {
                $array[] = '"owner" LIKE '. Db()->quote($value);
            }
            
            $sql = 'SELECT "task_id" FROM "jdi_task_owner" WHERE ' . join(' OR ', $array);
            $res = Db()->query($sql);
            
            $values = $res->fetchColumn();
            
            if ($values) {
                $where[] = '"id" IN ('. join(', ', $values) .')';
            } else {
                $where[] = '0';
            }
            
            unset($params['owner']);
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

        $orderBy[] = '"id" ASC';
        
        if (empty($params['order_by'])) {
            unset($params['order_by']);
        }

        return $orderBy;
    }
}