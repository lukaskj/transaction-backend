<?php

namespace App\Util;

class StringUtil {
   /**
    * @param string $personId
    * @return bool
    */
   public static function isValidPersonId(string $personId) {

      if (empty($personId)) {
         return false;
      }

      $personId = @preg_replace('[^0-9]', '', $personId);
      $personId = str_pad($personId, 11, '0', STR_PAD_LEFT);

      if (strlen($personId) != 11) {
         return false;
      } elseif ($personId == '00000000000' ||
         $personId == '11111111111' ||
         $personId == '22222222222' ||
         $personId == '33333333333' ||
         $personId == '44444444444' ||
         $personId == '55555555555' ||
         $personId == '66666666666' ||
         $personId == '77777777777' ||
         $personId == '88888888888' ||
         $personId == '99999999999') {
         return false;
      }

      for ($t = 9; $t < 11; $t++) {
         for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $personId[$c] * (($t + 1) - $c);
         }
         $d = ((10 * $d) % 11) % 10;

         if ($personId[$c] != $d) {
            return false;
         }
      }

      return true;
   }

   /**
    * @param string $companyId
    * @return bool
    */
   public static function isValidCompanyId($companyId) {
      $companyId = preg_replace('/[^0-9]/', '', $companyId);

      if (strlen($companyId) != 14) {
         return false;
      }

      $sum = 0;

      $sum += ($companyId[0] * 5);
      $sum += ($companyId[1] * 4);
      $sum += ($companyId[2] * 3);
      $sum += ($companyId[3] * 2);
      $sum += ($companyId[4] * 9);
      $sum += ($companyId[5] * 8);
      $sum += ($companyId[6] * 7);
      $sum += ($companyId[7] * 6);
      $sum += ($companyId[8] * 5);
      $sum += ($companyId[9] * 4);
      $sum += ($companyId[10] * 3);
      $sum += ($companyId[11] * 2);

      $d1 = $sum % 11;
      $d1 = $d1 < 2 ? 0 : 11 - $d1;

      $sum = 0;
      $sum += ($companyId[0] * 6);
      $sum += ($companyId[1] * 5);
      $sum += ($companyId[2] * 4);
      $sum += ($companyId[3] * 3);
      $sum += ($companyId[4] * 2);
      $sum += ($companyId[5] * 9);
      $sum += ($companyId[6] * 8);
      $sum += ($companyId[7] * 7);
      $sum += ($companyId[8] * 6);
      $sum += ($companyId[9] * 5);
      $sum += ($companyId[10] * 4);
      $sum += ($companyId[11] * 3);
      $sum += ($companyId[12] * 2);

      $d2 = $sum % 11;
      $d2 = $d2 < 2 ? 0 : 11 - $d2;

      if ($companyId[12] == $d1 && $companyId[13] == $d2) {
         return true;
      }

      return false;
   }

   /**
    * @param string $personCompanyId
    * @return bool
    */
   public static function isValidPersonCompanyId(string $personCompanyId) {
      $personCompanyId = preg_replace('/[^0-9]/', '', $personCompanyId);

      if (strlen($personCompanyId) == 14) {
         return self::isValidCompanyId($personCompanyId);
      }

      return self::isValidPersonId($personCompanyId);
   }

   public static function onlyNumbers(?string $str) {
      return $str !== null ? @preg_replace('/[^0-9]/', '', $str) : null;
   }
}