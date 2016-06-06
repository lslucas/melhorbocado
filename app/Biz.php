<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Biz extends Model {

	protected $table = 'biz';

	public static function select_name($id=false)
	{
		if ( !$id )
			return false;

		$estate = self::find($id);

		return '#'.$estate->id.' '.$estate->nome_fantasia.', '.$estate->endereco.' - '.$estate->bairro;
	}

    public static function name($id, $console=false)
    {
		if ( !$id || isset($this) && !$this->id )
			return false;

		$id = $id ? $id : (isset($this) ? $this->id : false);

        $estate = self::find($id);

        if ( !$estate ) {
            return $console ? false : '[loja apagada]';
		}

        return $estate->nome_fantasia ? $estate->nome_fantasia : $estate->razao_social;
    }

    public static function field($name, $id=false)
    {
		$id = $id ? $id : $this->id;

		if ( !$name && !$id )
			return false;

        $estate = self::find($id);

        if ( !$estate )
            return false;

        return $estate[$name] ? $estate[$name] : false;
    }

	public function logo($id=false, $size=['width'=>180])
	{
		if ( !$id && !$this->id )
			return false;

		$id = $id ? $id : $this->id;

		$estate = self::find($id);

		if ( !empty($estate->logo) ) {

			$w = $size['width'];
			$h = isset($size['height']) ? $size['height'] : false;
			$wh= ' width="'.$w.'" ';
			if ( $h )
				$wh .= ' height="'.$h."' ";

			$file = '/storage/'.$estate->logo;

			return '<img src="'.$file.'" '.$wh.' border=0/>';

		} else
			return self::name($id);
	}
}
