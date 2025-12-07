<?php

namespace Hyvr\RayDB;

use Exception;
use Illuminate\Support\Collection;

class Query
{
    public Bucket $bucket;
    private $collection;

    public function __construct(Bucket $bucket){
        $this->bucket = $bucket;
        $this->collection = new Collection($this->getCollection());
    }

    public function where($key, $operato = null, $value = null){
        $this->collection = $this->collection->where($key, $operato, $value);

        return $this;
    }

    public function get(){
        return (new Documents($this->bucket, $this->collection->toArray()));
    }

    public function all(){
        return $this->get();
    }

    public function first(){
        if($this->collection->count()==0) return null;

        return (new Document($this->bucket, $this->collection->first()));
    }

    public function sort($callback = null){
        $this->collection = $this->collection->sort($callback);

        return $this;
    }

    public function sortBy($callback, $options = SORT_REGULAR, $descending = false){
        $this->collection = $this->collection->sortBy($callback, $options, $descending);

        return $this;
    }

    private function getCollection(){
        $documents = [];
        $path = $this->bucket->bucket_path;
        $files = scandir($this->bucket->bucket_path);
        
        foreach($files as $file){
            if($file[0]==='_' || !str_ends_with($file, '.json') || is_dir($path.DIRECTORY_SEPARATOR.$file)) continue;

            $document = Helper::parseJsonFile($path, $this->bucket->ray);
            $documents[] = $document;
        }
        
        return $documents;
    }
}