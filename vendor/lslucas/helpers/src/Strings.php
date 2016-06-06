<?php namespace lslucas\Helpers;

class Strings {

    public static function phoneFormat($str)
    {
        $str = preg_replace('/[^0-9]/', '', $str);

        $pattern = '/(\d{2})(\d{4})(\d*)/';
        $phone = preg_replace($pattern, '($1) $2-$3', $str);

        return $phone;
    }

    public static function isJson($string)
    {
        if ( !is_string($string) )
            return false;

       json_decode($string);
       return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function mb_ucfirst($str)
    {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }

    public static function ucword($str)
    {
        $words = explode(' ', $str);
        $string = null;

        foreach ($words as $w) {
            if ( strlen($w)>2 )
                $string .= ucfirst($w).' ';
            else
                $string .= $w.' ';

        }

        return $string;
    }

    public static function hideCC($ccNumber)
    {
        if ( !$ccNumber || empty($ccNumber) )
            return false;

        return '****-****-****-'.substr($ccNumber, 15);
    }

    protected static function randomColorPart($pastel=true) {
      $color = $pastel ? mt_rand( 0, 128 )+127 : mt_rand(0, 256);
      return str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT);
    }

    public static function randomColor($pastel=true) {

      $red = self::randomColorPart($pastel);
      $green = self::randomColorPart($pastel);
      $blue = self::randomColorPart($pastel);

      $color = $red.$green.$blue;

      return $color;
    }

}
