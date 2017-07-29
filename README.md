# phpS3Simple

Simple aws-sdk-php wrapper for Amazon S3

Copyright Christos Pontikis http://www.pontikis.net

Project page https://github.com/pontikis/phpS3Simple

License [MIT](https://github.com/pontikis/phpS3Simple/blob/master/LICENSE)


## Features

1. getObject
2. putObject
3. deleteObject
4. createPresignedURL
5. store presigned URLs in memcached

## Dependencies

* AWS SDK for PHP https://aws.amazon.com/sdk-for-php/
* Dacapo (Simple PHP database and memcached wrapper) https://github.com/pontikis/dacapo

(PHP 5 >= 5.2.0, PHP 7)

## Files
 
1. ``phpS3Simple.class.php`` php class


## Documentation

See ``docs/doxygen/html`` for html documentation of ``phpS3Simple`` class. 

## How to use

Download zip version of AWS SDK for PHP

Define ``C_CLASS_AWS_SDK_PHP_PATH``

    define('C_CLASS_AWS_SDK_PHP_PATH', '/path/to/aws/aws-autoloader.php');
   
   
## Examples

coming soon