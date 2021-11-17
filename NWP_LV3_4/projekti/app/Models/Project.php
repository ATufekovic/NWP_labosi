<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        "leader_user_id",
        "naziv_projekta",
        "datum_pocetka"
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function leader(){
        return $this->belongsTo(User::class, "leader_user_id");
    }

    public function setNazivProjektaAttribute($value){
        $this->attributes["naziv_projekta"] = $value;
    }
}
