<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Location extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['id','name','org_id','address','postal_code','city','country','is_active'];


    public function organization()
    {
        return $this->belongsTo(Organization::class,'org_id','id');
    }

    public $additional_attributes = ['active'];
    public function getActiveAttribute()
    {
        return "Je suis actif";
    }


    //Localisation d'un site
    public function localize(){
        $API_KEY = env('GOOGLE_API_KEY');
        $adress = urlencode($this->address.','.$this->postal_code.' '.$this->city.','.$this->country);
        // \LOG::debug("https://maps.googleapis.com/maps/api/geocode/xml?address=$adress&key=$API_KEY");
        try {
            $xml = simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?address=".$adress."&key=".$API_KEY);
            if (!(is_null($xml))) {
                $location = $xml->result->geometry->location;
                //error_log(htmlspecialchars_decode (Zend_Debug::dump($location,'localisation')),3,"/var/tmp/mes-erreurs.log");
                if (!(is_null($location))) {
                    $this->latitude = $location->lat;
                    $this->longitude = $location->lng;
                    $this->is_localized = 1;
                    \Log::info("Le site " . $this->name . " est localisé.");
                    $this->save();

                }
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

}
