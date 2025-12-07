<?php

namespace Hyvr\RayDB;

use Exception;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;

class Documents implements IteratorAggregate
{
    private Bucket $bucket;
    public array $items = [];

    public function __construct(Bucket $bucket, array $items = []){
        $this->bucket = $bucket;

        foreach($items as $item){
            $this->items[] = new Document($this->bucket, $item);
        }
    }

    public function update(array $data){
        foreach($this->items as $key => $item){
            $this->items[$key] = $item->update($data);
        }

        return $this;
    }

    public function getIterator(): Traversable {
        return new \ArrayIterator($this->items);
    }

    public function toArray(){
        return array_map(fn($document) => $document->toArray(), $this->items);
    }
}