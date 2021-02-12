<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Class ReportableException.
 */
class ReportableException extends HttpException {
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
      parent::__construct($code, $message, null, [], $code);
      $this->data = $data;
   }

   public function getData() {
      return $this->data;
   }

   public static function from(Throwable $e) {
      return new ReportableException($e->getMessage(), null, (int)$e->getCode() ?: 500, null);
   }
}
