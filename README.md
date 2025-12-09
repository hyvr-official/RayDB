![Fishline logo](https://github.com/hyvr-official/Fishline/blob/main/art/readme-header.png?raw=true)
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
