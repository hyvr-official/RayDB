<?php

namespace Hyvr\RayDB;

use Exception;

class Bucket
{
    public Ray $ray;
    public string $bucket_name;
    public string $bucket_path;

    public function __construct(string $bucket_name, bool $is_create_if_not_found, Ray $ray){
        $this->ray = $ray;
        $this->bucket_name = $bucket_name;
        $this->bucket_path = $ray->db_folder.DIRECTORY_SEPARATOR.$bucket_name;

        if(!is_dir($this->bucket_path)){
            if($is_create_if_not_found){
                mkdir($this->bucket_path, $ray->db_config['folder_permission'], true);
            }
            else{
                throw new Exception('RayDB database bucket not found ('.$this->bucket_path.')');
            }
        }
    }

    public function new(string $id){
        $path = $this->bucket_path.DIRECTORY_SEPARATOR.$id.'.json';

        if(file_exists($path)){
            throw new Exception('RayDB database document that you tried to insert already exists ('.$path.')');
        }

        return new Document($this, [], $id);
    }

    public function newOrUpdate(string $id){
        return new Document($this, [], $id);
    }

    public function find(string $id){
        $path = $this->bucket_path.DIRECTORY_SEPARATOR.$id.'.json';

        if(!file_exists($path)){
            return false;
        }

        return new Document($this, Helper::parseJsonFile($path, $this->ray), $id);
    }

    public function query(){
        return new Query($this);
    }

    public function insert(array $data){
        if(count($data)!==count(array_filter($data, 'is_array'))){
            $data = [$data];
        }

        foreach($data as $row){
            if(!isset($row['_id'])) $row['_id'] = Helper::getUniqueId();

            $seed = $row['_id'];
            $document_path = $this->bucket_path.DIRECTORY_SEPARATOR.$seed.'.json';

            if(file_exists($document_path)){
                throw new Exception('RayDB database document that you tried to insert already exists ('.$document_path.')');
            }

            Helper::writeJsonFile($document_path, $row, $this->ray);
        }
    }

    public function update(array $data){
        if(count($data)!==count(array_filter($data, 'is_array'))){
            $data = [$data];
        }

        foreach($data as $row){
            $seed = $row['_id'];
            $path = $this->bucket_path.DIRECTORY_SEPARATOR.$seed.'.json';

            if(file_exists($path)){
                $document = Helper::parseJsonFile($path, $this->ray);
                $document = array_replace($document, $row);

                Helper::writeJsonFile($path, $document, $this->ray);
            }
        }
    }

    public function updateOrInsert(array $data){
        if(count($data)!==count(array_filter($data, 'is_array'))){
            $data = [$data];
        }

        foreach($data as $row){
            $seed = $row['_id'];
            $document_path = $this->bucket_path.DIRECTORY_SEPARATOR.$seed.'.json';

            Helper::writeJsonFile($document_path, $row, $this->ray);
        }
    }
}