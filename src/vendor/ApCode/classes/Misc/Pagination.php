<?php

namespace ApCode\Misc;

class Pagination
{
    private $page  = 0;
    private $limit = 25;
    private $total = 0;
    
    public function __construct($params = []) {
        foreach ($params as $name => $value) {
            $function = [$this, 'set' . ucfirst($name)];
            
            if (is_callable($function)) {
                $function($value);
            }
        }
    }
    
    public function totalItems()
    {
        return $this->total;
    }
    
    public function totalPages()
    {
        return ceil($this->total / $this->limit);
    }
    
    public function setTotalItems($totalItems)
    {
        $this->total = $totalItems;
        return $this;
    }
    
    public function page()
    {
        return $this->page;
    }
    
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }
    
    public function limit()
    {
        return $this->limit;
    }
    
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function startFrom()
    {
        return $this->page * $this->limit;
    }
}