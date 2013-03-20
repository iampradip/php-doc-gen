*Do not use this script on production server. It allows user to include files.*

php-doc-gen
===========

This script will generate PHP code for defined classes, functions, constants, etc. from extensions or included files. Interface is somewhat similar to javadocs.

An example:

<pre>class PDOException extends RuntimeException {

   /* Properties */ 
   protected $message ;
   protected $code ;
   protected $file ;
   protected $line ;
   public $errorInfo ;

   /* Methods */ 
   private function __clone() {}
   public function __construct($message, $code, $previous) {
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

}</pre>
