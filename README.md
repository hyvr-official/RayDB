![Fishline logo](https://github.com/hyvr-official/RayDB/blob/main/art/readme-header.png?raw=true)
RayDB is a lightweight NoSQL database built in PHP that relies solely on plain JSON files, with no third-party dependencies. Its simple file-based design makes it ideal for static websites or projects that need an easy, manageable way to store content. It’s intended for handling a few gigabytes of data under low to medium workloads, making it suitable for low-traffic environments where a full traditional database would be unnecessary.

### :zap: Get started
RayDB can be installed from the composer package manager. Make sure you installed PHP and Composer from the requirments section before continuing. Run the below command to install the RayDB.
`````
composer require niyko/transpicious
`````
Example usage is given below. More usage examples are details given in the below section.
`````php
<?php
  require 'vendor/autoload.php';

  use Hyvr\RayDB\Ray;

  $ray = new Ray(__DIR__.'/buckets');

  $bucket = $ray->bucket('fruits');

  $bucket->insert([
    'name' => 'apple',
    'color' => 'red',
    'size' => ['small', 'medium'],
    'orgin' => [
      'name' => 'India',
      'code' => 'IN'
    ]
  ]);

  $fruits = $bucket->query()->sortBy('name')->get();
?>
`````

### :tophat: Requirments
The RayDB has a few system requirements. You should ensure that your local system has the following minimum PHP version and extensions:

* PHP >= `8.0.0`
* Composer >= `1.0.0`

### :rocket: Usage
Examples on how to use RayDB is given in grouped sections below.

<details name="usage">
<summary>Initializing</summary>
<br>
  
`````php
<?php
  require 'vendor/autoload.php';

  use Hyvr\RayDB\Ray;

  // Initializing database 
  $ray = new Ray(__DIR__.'/buckets');

  // Initializing database with config 
  $ray = new Ray(__DIR__.'/buckets', [
    'folder_permission' => 0777,
    'pretty' => true
  ]);

  // Initializing database also create the database folder
  $ray = new Ray(__DIR__.'/buckets', [], true);
?>
`````
</details>

<details name="usage">
<summary>Insert</summary>
<br>
  
`````php
<?php
  require 'vendor/autoload.php';

  use Hyvr\RayDB\Ray;

  $ray = new Ray(__DIR__.'/buckets');

  // Create a bucket called 'fruits' and insert a document
  $bucket = $ray->bucket('fruits');

  $bucket->insert([
    'name' => 'apple',
    'color' => 'red',
    'size' => ['small', 'medium'],
    'orgin' => [
      'name' => 'India',
      'code' => 'IN'
    ]
  ]);

  // Insert a document with id
  $bucket->insert([
    '_id' => 'apple',
    'name' => 'apple',
    'color' => 'red'
  ]);

  // Bulk insert
  $bucket->insert([
    [
      'name' => 'apple',
      'color' => 'red'
    ],[
      'name' => 'orange',
      'color' => 'orange'
    ]
  ]);

  // Another method to insert with object
  $apple = $ray->bucket('fruits')->new('apple');
  $apple->name = 'apple';
  $apple->color = 'red';
  $apple->save();
?>
`````
</details>

<details name="usage">
<summary>Query</summary>
<br>
  
`````php
<?php
  require 'vendor/autoload.php';

  use Hyvr\RayDB\Ray;

  $ray = new Ray(__DIR__.'/buckets');

  $bucket = $ray->bucket('fruits');

  // Get a document with id
  $apple = $bucket->find('apple');

  echo $apple->name;
  echo $apple->color;

  // Query all the documents in the bucket
  $fruits = $bucket->query()->get();

  foreach($fruits as $fruit){
    echo $fruit->name;
    echo $fruit->color;
  }

  // Query using condition
  $fruits = $bucket->query()->where('color', 'red')->get();

  $fruits = $bucket->query()->where('size', '>=', 100)->get();

  foreach($fruits as $fruit){
    echo $fruit->name;
    echo $fruit->color;
  }

  // Query using condition and get first
  $fruit = $bucket->query()->where('color', 'red')->first();

  echo $fruit->name;
  echo $fruit->color;
?>
`````
</details>

<details name="usage">
<summary>Update</summary>
<br>
  
`````php
<?php
  require 'vendor/autoload.php';

  use Hyvr\RayDB\Ray;

  $ray = new Ray(__DIR__.'/buckets');

  $bucket = $ray->bucket('fruits');

  // Find a document with id and update it
  $apple = $ray->bucket('fruits')->find('apple');
  $apple->color = 'red';
  $apple->save();

  // Bulk update with id
  $bucket->update([
    [
      '_id' => 'apple',
      'name' => 'apple',
      'color' => 'red'
    ],[
      '_id' => 'orange',
      'name' => 'orange',
      'color' => 'orange'
    ]
  ]);
?>
`````
</details>

<details name="usage">
<summary>Upsert</summary>
<br>
  
`````php
<?php
  require 'vendor/autoload.php';

  use Hyvr\RayDB\Ray;

  $ray = new Ray(__DIR__.'/buckets');

  $bucket = $ray->bucket('fruits');

  // Upsert using id
  $apple = $ray->bucket('fruits')->newOrUpdate('apple');
  $apple->color = 'red';
  $apple->save();

  // Bulk upsert with id
  $bucket->updateOrInsert([
    [
      '_id' => 'apple',
      'name' => 'apple',
      'color' => 'red'
    ],[
      '_id' => 'orange',
      'name' => 'orange',
      'color' => 'orange'
    ]
  ]);
?>
`````
</details>

### :page_with_curl: License
RayDB is licensed under the [MIT License](https://github.com/hyvr-official/RayDB/blob/main/LICENSE).
