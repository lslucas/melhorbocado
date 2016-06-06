<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BizUser extends Model {

	protected $table = 'biz_user';

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }

}
