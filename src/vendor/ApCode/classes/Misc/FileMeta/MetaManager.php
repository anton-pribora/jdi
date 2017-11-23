<?php

namespace ApCode\Misc\FileMeta;

class MetaManager
{
    private static $files = [];
    private static $classes = [];
    
    /**
     * @param string $path
     * @return FileMeta
     */
    static function fileMeta($path)
    {
        $path = realpath($path);
        
        if (empty(self::$files[$path])) {
            self::$files[$path] = new FileMeta($path);
        }
        
        return self::$files[$path];
    }
    
    /**
     * @param string $class
     * @return FileMeta
     */
    static function classMeta($class)
    {
        if (empty(self::$classes[$class])) {
            $reflection = new \ReflectionClass($class);
            self::$classes[$class] = self::fileMeta($reflection->getFileName());
        }
        
        return self::$classes[$class];
    }
}