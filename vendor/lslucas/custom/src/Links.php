<?php namespace lslucas\Custom;

use App\RealEstate;

class Links {

    public static function l($module, $id=null, $action=null, $args=[])
    {
        switch ( $action ) {
            case 'ver':
            case 'show': $target = '/imovel/';
            break;
            default: '/'. $module;
        }

        if ( $module=='realestate' ) {

            $imovel = RealEstate::find($id);
            if ( !$imovel )
                return false;

            $id = $imovel->codigo;

        }

        return $target . $id;
    }

}
