<?php

namespace Data;

class BlobFile extends File
{
    private $guid;
    
    public function __construct($path = NULL, $prefix = '')
    {
        if (empty($path)) {
            do {
                $guid = md5(uniqid('', '2io492eweb3u72iwn'));
                $path = join('/', [
                    substr($guid, 0, 2),
                    substr($guid, 2, 6),
                ]);
            } while (file_exists(ExpandPath("@blob/{$prefix}$path")));
        }
        
        $this->guid = $path;
        
        parent::__construct(ExpandPath("@blob/{$prefix}$path"));
    }
    
    public function guid()
    {
        return $this->guid;
    }
    
    public function remove()
    {
        $result = parent::remove();
        
        @rmdir($this->folder());
        
        return $result;
    }
}