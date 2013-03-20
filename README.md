*Do not use this script on production server. It allows user to include files.*

php-doc-gen
===========

This script will generate PHP code for defined classes, functions, constants, etc. from extensions or included files. Interface is somewhat similar to javadocs.

<<<<<<< HEAD
An example

```php
class PDOException extends RuntimeException {
=======
An example:

<pre>class PDOException extends RuntimeException {
>>>>>>> 615cb1db60a3de032ce02fd34fb8a68bd235707b

   /* Properties */ 
   protected $message ;
   protected $code ;
   protected $file ;
   protected $line ;
   public $errorInfo ;

   /* Methods */ 
   private function __clone() {}
<<<<<<< HEAD
   public function __construct($message = '<internal-value>', $code = '<internal-value>', $previous = '<internal-value>') {
=======
   public function __construct($message, $code, $previous) {
>>>>>>> 615cb1db60a3de032ce02fd34fb8a68bd235707b
      /* Constructor Implementation */ 
   }
   public function getMessage() {}
   public function getCode() {}
   public function getFile() {}
   public function getLine() {}
   public function getTrace() {}
   public function getPrevious() {}
   public function getTraceAsString() {}
   public function __toString() {}

<<<<<<< HEAD
} 
```
=======
}</pre>
>>>>>>> 615cb1db60a3de032ce02fd34fb8a68bd235707b
