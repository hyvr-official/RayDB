<?php

namespace Hyvr\RayDB;

use Exception;
use Illuminate\Support\Collection;

class Document
{
    public string $_id;
    private array $attributes = [];
    private Bucket $bucket;

    public function __construct(Bucket $bucket, array $data = [], string $id = ''){
        $this->_id = ($id=='')?Helper::getUniqueId():$id;
        $this->bucket = $bucket;
        $this->attributes = $data;

        if(!isset($this->attributes['_id'])) $this->attributes['_id'] = $this->_id;
    }

    public function __get($key){
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value){
        $this->attributes[$key] = $value;
    }

    public function update(array $data){
        unset($data['_id']);

        $this->attributes = array_replace($this->attributes, $data);
        $this->save();

        return $this;
    }

    public function save(){
        $this->bucket->updateOrInsert($this->attributes);
        $this->attributes = get_object_vars(json_decode(json_encode($this->attributes)));

        return $this;
    }

    public function toArray(): array {
        return $this->attributes;
    }
}