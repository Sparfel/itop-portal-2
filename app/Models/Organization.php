<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Organization extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['id','name','type','deliverymodel_id','deliverymodel_id_friendlyname','parent_id'];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public $additional_attributes = ['code_name'];

    public function getCodeNameAttribute()
    {
        return $this->id."-".$this->name;
    }

    //Liste des organisations clientes pour les user Fives multi-usine
    public function getListCustOrg(){
        return self::whereIn('type', array('customer','both'))
            ->orderBy('name','ASC')
            ->get();
    }

    // Liste des organisations 'soeurs' (d'un même groupe) pour les utilisateurs multi-usine
    public function getListSisterOrg($org_id)
    {
        return self::join('organizations as o2', function ($join) {
            $join->on('o2.parent_id', '=', 'organizations.parent_id');
                    })
            ->where('organizations.id',$org_id)
            ->select('o2.*')
            ->get();
    }
}
