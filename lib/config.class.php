<?php

class Config{
    protected static $setings = array();

    public static function get($key){
        return isset(self::$setings[$key]) ? self::$setings[$key] : null;
    }

    public static function set($key, $value) {
        self::$setings[$key] = $value;
    }
}