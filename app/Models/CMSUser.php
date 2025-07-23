<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class CMSUser extends Authenticatable
{
    use HasRoles;

    protected $table = "cms_users";

    protected $guard_name = 'web';

    protected $guarded = [''];


}
