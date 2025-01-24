<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isNull;

class UserPreference extends Model
{
    use HasFactory;
    protected $table = 'user_preferences';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','preference_name','preference_value'];

    public function getPref($user_id,$pref)
    {

        $query = $this->where('user_id', $user_id)->where('preference_name', $pref)->first();
        if (isset($query)) {
            return $query->preference_value;
        } else {
            return null;
        }
    }

    public function savePref($user_id,$pref,$value) {
        $this::updateOrCreate(['user_id'=> $user_id, 'preference_name' => $pref],
            ['preference_value' => $value]);

    }
}
