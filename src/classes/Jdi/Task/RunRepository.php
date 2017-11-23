<?php

namespace Jdi\Task;

use ApCode\Misc\Pagination;

class RunRepository
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
        
        $sql = 'SELECT "id" FROM "'. Run::tableName() .'"';

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
            $sql .= ' LIMIT '. intval($pagination->startFrom()) .', '. intval($pagination->limit());
        }

        $idList = Db()->query($sql)->fetchColumn();

        if ($pagination) {
            $pagination->setTotalItems(Db()->query('SELECT FOUND_ROWS()')->fetchValue());
        }

        $result = [];

        foreach ($idList as $id) {
            $result[] = Run::getInstance($id);
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
    
    public static function delete(Run $run)
    {
        $run->stderr()->remove();
        $run->stdout()->remove();
        
        $sql = 'DELETE FROM "'. Run::tableName() .'" WHERE "id" = ? LIMIT 1';
        $res = Db()->query($sql, [$run->id()]);
    }

    private static function buildWhere(&$params)
    {
        $where = [];
        
        if (isset($params['id'])) {
            $where[] = '"id" = '. Db()->quote($params['id']);
            unset($params['id']);
        }
        
        if (isset($params['taskId'])) {
            $where[] = '"task_id" = '. Db()->quote($params['taskId']);
            unset($params['taskId']);
        }
        
        if (isset($params['start'])) {
            $where[] = '"start" = '. Db()->quote($params['start']);
            unset($params['start']);
        }
        
        if (isset($params['end'])) {
            $where[] = '"end" = '. Db()->quote($params['end']);
            unset($params['end']);
        }
        
        if (isset($params['stdout'])) {
            $where[] = '"stdout" = '. Db()->quote($params['stdout']);
            unset($params['stdout']);
        }
        
        if (isset($params['stderr'])) {
            $where[] = '"stderr" = '. Db()->quote($params['stderr']);
            unset($params['stderr']);
        }
        
        if (isset($params['exitCode'])) {
            $where[] = '"exit_code" = '. Db()->quote($params['exitCode']);
            unset($params['exitCode']);
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

        $orderBy[] = '"id" DESC';
        
        if (empty($params['order_by'])) {
            unset($params['order_by']);
        }

        return $orderBy;
    }
}