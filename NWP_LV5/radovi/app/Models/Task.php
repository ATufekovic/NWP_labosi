<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'naziv_rada',
        'naziv_rada_engleski',
        'zadatak_rada',
        'tip_studija'
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function applicants(){
        return $this->belongsToMany(User::class)->withPivot("order");
    }

    public function chosenStudent(){
        return $this->belongsTo(User::class, 'student_id');
    }
}
