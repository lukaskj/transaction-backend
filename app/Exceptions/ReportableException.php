<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Class ReportableException.
 */
class ReportableException extends \RuntimeException implements HttpExceptionInterface {
   /**
    * @var
    */
   public $message;

   private $data = null;

   /**
    * GeneralException constructor.
    *
    * @param string         $message
    * @param int            $code
    * @param Throwable|null $previous
    */
   public function __construct(string $message = '', $data = null, int $code = 500, Throwable $previous = null) {
      // parent::__construct($code, $message, null, [], $code);
      parent::__construct($message, $code, $previous);
      $this->data = $data;
   }

   public function getData() {
      return $this->data;
   }

   public function getStatusCode() {
      return $this->getCode();
   }

   public function getHeaders() {
      return [];
   }

   public static function from(Throwable $e) {
      return new ReportableException($e->getMessage(), null, (int) $e->getCode() ?: 500, null);
   }
}
