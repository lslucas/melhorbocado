<?php namespace lslucas\Helpers;

class Arrays {

    public static function make($start, $end)
    {
        $array = array();
        return range($start, $end);
    }

    /**
    * Function exst() - Checks if the variable has been set
    * (copy/paste it in any place of your code)
    *
    * If the variable is set and not empty returns the variable (no transformation)
    * If the variable is not set or empty, returns the $default value
    *
    * @param  mixed $var
    * @param  mixed $default
    *
    * @return mixed
    */
    public static function exst( & $var, $default = "") {
        $t = "";
        if ( !isset($var)  || !$var ) {
            if (isset($default) && $default != "") $t = $default;
        }
        else  {
            $t = $var;
        }
        if (is_string($t)) $t = trim($t);
        return $t;
    }

    /**
     * faz uma busca no array e retorna a key encontrada
     */
    public static function findKey($array, $key=null, $value=null, $aux=false)
    {
        $results = array();
        if ( !is_array($value) )
            $value = [$value];

        foreach ( $value as $val ) {
            if (is_array($array)) {
                if (isset($array[$key]) && $array[$key] == $val)
                    $results[] = $aux;

                foreach ($array as $chave=>$subarray) {
                    $results = array_merge($results, static::findKey($subarray, $key, $val, $chave));
                }
            }
        }

        return $results;
    }

    /**
     * faz uma busca no array
     */
    public static function find($array, $key=null, $value=null)
    {
        $results = array();
        if ( !is_array($value) )
            $value = [$value];

        foreach ( $value as $val ) {
            if (is_array($array)) {
                if (isset($array[$key]) && $array[$key] == $val)
                    $results[] = $array;

                foreach ($array as $subarray)
                    $results = array_merge($results, static::find($subarray, $key, $val));
            }
        }

        return $results;
    }

    public static function convert2option($array, $selected=null) {

        $option = "<option value=''>Selecione</option>";
        foreach ($array as $key=>$val) {
            if (!$val) $val = $key;
            $option .= "<option value='{$key}'";

            if (!empty($selected) && $selected==$key)
                $option .= " selected";

            $option .= ">{$val}</option>";
        }

        return $option;
    }

    public static function in_multiarray($elem, $array)
    {
        $top = sizeof($array) - 1;
        $bottom = 0;
        while($bottom <= $top)
        {
            if(isset($array[$bottom]) && $array[$bottom] == $elem)
                return true;
            else
                if( isset($array[$bottom]) && is_array($array[$bottom]))
                    if(self::in_multiarray($elem, ($array[$bottom])))
                        return true;

            $bottom++;
        }
        return false;
    }

    public static function mongo2array($obj)
    {
        $res = array();
        foreach ( $obj as $id => $raw ) {
            $res[] = $raw->attributes;
        }

        return $res;
    }

    public static function object_to_array($obj) {
        if(is_object($obj)) $obj = (array) $obj;
        if(is_array($obj)) {
            $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = self::object_to_array($val);
            }
        }
        else $new = $obj;
        return $new;
    }

    public static function array_merge_recursive_ex(array & $array1, array & $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => & $value)
        {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
            {
                $merged[$key] = self::array_merge_recursive_ex($merged[$key], $value);
            } else if (is_numeric($key))
            {
                 if (!in_array($value, $merged))
                    $merged[] = $value;
            } else
                $merged[$key] = $value;
        }

        return $merged;
    }

    public static function sortBy($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

}
