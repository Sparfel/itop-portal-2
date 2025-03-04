<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ItopUser extends Model
{

    protected $fillable = ['itop_id','portal_id','last_name','first_name','email','org_id','org_name','location_id','location_name','phone','mobile_phone','comment',
                            'is_local','is_in_itop','has_itop_account','role_id'];


}
