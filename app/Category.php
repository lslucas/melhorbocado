<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

	protected $table = 'category';

    public static function name($id, $console=false)
    {
		if ( !$id || isset($this) && !$this->id )
			return false;

		$id = $id ? $id : (isset($this) ? $this->id : false);

        $self = self::find($id);

        if ( !$self ) {
            return $console ? false : '[inválido]';
		}

        return $self->titulo;
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

}
