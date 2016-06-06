<?php namespace lslucas\Helpers;

use App\Codes;

class Numbers {

    public static function extenso( $valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false )
    {
        //$valor = self::removerFormatacaoNumero( $valor );

        $singular = null;
        $plural = null;

        if ( $bolExibirMoeda ) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");


        if ( $bolPalavraFeminina )
        {

            if ($valor == 1)
            {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            else
            {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }


            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");


        }


        $z = 0;

        $valor = number_format( $valor, 2, ".", "." );
        $inteiro = explode( ".", $valor );

        for ( $i = 0; $i < count( $inteiro ); $i++ )
        {
            for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ )
            {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $inteiro ); $i++ )
        {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $inteiro ) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ( $valor == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;

            if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];

            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr( $rt, 1 );

        return($rt ? trim( $rt ) : "zero");

    }

    public static function fnumber_format($number, $decimals='', $sep1='', $sep2='') {

        if (($number * pow(10 , $decimals + 1) % 10 ) == 5)  //if next not significant digit is 5
            $number -= pow(10 , -($decimals+1));

        return number_format($number, $decimals, $sep1, $sep2);

    }

    public static function decimalize($num)
    {
        $num = self::onlyPrice($num);
        $res = number_format(self::toFloat($num), 2, '.', '');

        return $res;
    }

    public static function toFloat($num)
    {
        if ( is_array($num) )
            return false;

        if ($num == '')
            return 0;

        if ( strpos($num, ',') )
            $num = str_replace(',', '.', str_replace('.', '', $num));

        $val = floatval($num);
        $val = (float) number_format($val, 2, '.', '');

        return $val;
    }

    public static function money($num)
    {
        if ( is_string($num) )
            $num = (float) self::toFloat($num);
        if ( is_array($num) )
            $num = array_sum($num);

        //if ( !self::isDecimal($num) )
            //return $num;

        return number_format($num, 2, ',', '.');
    }

    public static function isDecimal( $val )
    {
        if ( is_float($val) )
            return $val;
        elseif ( preg_match('/^\d+\.\d+$/', $val) )
            return $val;
        else
            return false;
    }

    public static function onlyNumbers($num)
    {
        return preg_replace('/[^0-9]/', '', $num);
    }

    public static function onlyPrice($num)
    {
        return preg_replace('/[^0-9,.]/', '', $num);
    }

    public static function showPrice($value, $defaultIfZero='Consultar Valor')
    {
        if ( !$value )
            return $defaultIfZero;
        else
            return self::money($value);
    }

    public static function random($origin=null, $max=8)
    {
        $codeTable = new Codes();
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {

            //$code = mt_rand(1, 999999999999);
            $code = '';
            for ($i=0; $i<$max; $i++) {
                 $code .= $characters[rand(0, strlen($characters) - 1)];
            }

            if ( $max ) {
                $code = substr($code, 0, $max);
                $code = sprintf("%0". $max ."s", $code);
            }

            $exists = $codeTable->exists($code);

        } while ( $exists );

        $codeTable->code = $code;
        $codeTable->origin = $origin;
        $codeTable->save();

        return $code;
    }

    /**
     * Generate reliable random int numbers
     * PHP7 only
     *
     * Updated version at https://github.com/paragonie/random_compat
     * */
    public static function random_int($min, $max)
    {
        if (!is_int($min) || !is_int($max)) {
             throw new InvalidArgumentException('random_int() expects two positive integers');
        }
        if ($min > $max) {
             throw new InvalidArgumentException('$min must be less than $max');
        }
        $range = $max - $min;
        // Test for integer overflow:
        if (!is_int($range)) {
             throw new InvalidArgumentException('Integer overflow');
        }
        // Do we have a meaningful range?
        if ($range < 1) {
            return $min;
        }

        // Initialize variables to 0
        $bits = $bytes = $mask = 0;

        $tmp = $range;
        /**
         * We want to store:
         * $bytes => the number of random bytes we need
         * $mask => an integer bitmask (for use with the &) operator
         *          so we can minimize the number of discards
         */
        while ($tmp > 0) {
            if ($bits % 8 === 0) {
               ++$bytes;
            }
            ++$bits;
            $tmp >>= 1;
            $mask = $mask << 1 | 1;
        }

        /**
         * Now that we have our parameters set up, let's begin generating
         * random integers until one falls within $range
         */
        do {
            $rval = random_bytes($bytes);
            if ($rval === false) {
                throw new Exception('Random number generator failure');
            }

            /**
             * Let's turn $rval (random bytes) into an integer
             *
             * This uses bitwise operators (<< and |) to build an integer
             * out of the values extracted from ord()
             *
             * Example: [9F] | [6D] | [32] | [0C] =>
             *   159 + 27904 + 3276800 + 201326592 =>
             *   204631455
             */
            $val = 0;
            for ($i = 0; $i < $bytes; ++$i) {
                $val |= (ord($rval[$i]) << ($i * 8));
            }

            // Apply mask
            $val = $val & $mask;

            // If $val is larger than the maximum acceptable number for
            // $min and $max, we discard and try again.
        } while ($val > $range);
        return (int) ($min + $val) & PHP_INT_MAX;
    }

}
