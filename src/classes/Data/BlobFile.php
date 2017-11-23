<?php

namespace Data;

class BlobFile extends File
{
    private $guid;
    
    public function __construct($path = NULL)
    {
        if (empty($path)) {
            do {
                $guid = md5(uniqid('', '2io492eweb3u72iwn'));
                $path = join('/', [
                    substr($guid, 0, 2),
                    substr($guid, 2, 2),
                    substr($guid, 4, 6),
                ]);
            } while (file_exists(ExpandPath("@blob/$path")));
        }
        
        $this->guid = $path;
        
        parent::__construct(ExpandPath("@blob/$path"));
    }
    
    public function guid()
    {
        return $this->guid;
    }
    
    public function remove()
    {
        $result = parent::remove();
        
        @rmdir($this->folder());
        @rmdir(dirname($this->folder()));
        
        return $result;
    }
}