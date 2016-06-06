<?php namespace App;

use App\BizUser;
use App\RoleUser;
use App\Biz;
use App\Roles;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasRoleAndPermissionContract {

	use Authenticatable, CanResetPassword, HasRoleAndPermission;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    public function biz()
    {
        return $this->hasOne('App\BizUser');
    }

    public function attachbizId($biz_id)
    {
        $bizUser = new BizUser();

        $bizUser->biz_id = $biz_id;
        $bizUser->user_id   = $this->id;
        $bizUser->save();
    }

    public function role()
    {
        $roleUser = RoleUser::where('user_id', $this->id)->first();

        if ( !$roleUser )
            return false;

        $role_id = $roleUser->role_id;

        $roles = Roles::find($role_id);

        if ( !$roles )
            return false;

        return $roles->name;
    }

    public function loja()
    {
        $bizUser = BizUser::where('user_id', $this->id)->first();

        if ( !$bizUser )
            return false;

        $biz_id = $bizUser->biz_id;

        return biz::name($biz_id);
    }


	/**
	 * Lista todas as imobiliárias que podem ser listadas ao usuário
	**/
    public function lojas($label=false)
    {
		if ( $this->is('admin') ) {
        	$bizs = biz::whereRaw('status=1')->get();

			$res = [];
			foreach ( $bizs as $eu ) {
				$res[$eu->id] = $label ? biz::select_name($eu->id) : $eu;
			}

			return $res;
		}

        $bizUser = BizUser::where('user_id', $this->id)->get();

        if ( !$bizUser )
            return false;

		$res = [];
		foreach ( $bizUser as $eu ) {
			$res[$eu->biz_id] = $label ? biz::select_name($eu->biz_id) : biz::find($eu->biz_id);
		}

        return $res;
    }

    public function biz_id()
    {
		$id = $this->id;
		if ( !$id )
			$id = \Auth::user()->id;

        $bizUser = BizUser::where('user_id', $id)->first();

        if ( !$bizUser )
            return false;

        return $bizUser->biz_id;
    }

    public function role_id()
    {
        $roleUser = RoleUser::where('user_id', $this->id)->first();

        if ( !$roleUser )
            return false;

        return $roleUser->role_id;
    }

    /**
     * Detach all roles from a user
     *
     * @return object
     */
    public function detachAllRoles()
    {
        $roleUser = RoleUser::where('user_id', $this->id)->delete();

        return $this;
    }
}
