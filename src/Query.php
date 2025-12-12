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

    public function where(mixed $key, mixed $operator = null, mixed $value = null){
        $arguments = array_filter([$key, $operator, $value], fn($value) => $value !== null);
        $this->collection = $this->collection->where(...$arguments);

        return $this;
    }

    public function limit(int $count){
        $this->collection = $this->collection->take($count);

        return $this;
    }

    public function shuffle(){
        $this->collection = $this->collection->shuffle();

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

    public function sort(mixed $callback, string $option = 'asc'){
        if($option=='asc') $this->collection = $this->collection->sortBy($callback);
        else $this->collection = $this->collection->sortByDesc($callback);

        return $this;
    }

    private function getCollection(){
        $documents = [];
        $path = $this->bucket->bucket_path;
        $files = scandir($this->bucket->bucket_path);
        
        foreach($files as $file){
            if($file[0]==='_' || !str_ends_with($file, '.json') || is_dir($path.DIRECTORY_SEPARATOR.$file)) continue;

            $document = Helper::parseJsonFile($path.DIRECTORY_SEPARATOR.$file, $this->bucket->ray);
            $documents[] = $document;
        }
        
        return $documents;
    }
}