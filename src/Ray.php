<?php

namespace Hyvr\RayDB;

use Exception;
use Hyvr\RayDB\Bucket;

class Ray
{
    public array $db_config = [
        'folder_permission' => 0777,
        'pretty' => true
    ];

    public string $db_folder;

    public function __construct(string $db_folder, array $db_config = [], bool $is_create_if_not_found = true){
        $this->db_folder = $db_folder;
        $this->db_config = array_replace($this->db_config, $db_config);

        if(!is_dir($db_folder)){
            if($is_create_if_not_found){
                mkdir($db_folder, $this->db_config['folder_permission'], true);
            }
            else{
                throw new Exception('RayDB database folder not found ('.$db_folder.')');
            }
        }
    }

    public function bucket(string $bucket_name, bool $is_create_if_not_found = true){
        return new Bucket($bucket_name, $is_create_if_not_found, $this);
    }
}