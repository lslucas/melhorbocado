<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Codes extends Model {

	protected $table = 'codes';

    public function exists($code)
    {
        $code = trim($code);

        if ( !$code )
            throw new InvalidArgumentException('Code invalid');

        if ( strlen($code)<=3 )
            throw new InvalidArgumentException('Code must have more than 3 characters');

        // check if code exists or not
        return (bool) self::whereRaw('code=?', [$code])->count();
    }

}
