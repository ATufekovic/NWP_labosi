<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\FileData;

class File extends Model
{
    use HasFactory;

    /**
     * The accessor for the file user
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * The accessor for the file data of the file
     */
    public function file_data(){
        return $this->hasOne(FileData::class, "file_id");
    }
}
