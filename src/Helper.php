<?php

namespace Hyvr\RayDB;

class Helper
{
    public static function writeJsonFile(string $path, array $data, Ray $ray){
        $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

        if($ray->db_config['pretty']===true) $flags |= JSON_PRETTY_PRINT;

        $json = json_encode($data, $flags);

        file_put_contents($path, $json);
    }

    public static function parseJsonFile(string $path, Ray $ray){
        $json = file_get_contents($path);

        return get_object_vars(json_decode($json));
    }

    public static function getUniqueId(){
        return hash('md5', microtime().random_bytes(4));
    }
}