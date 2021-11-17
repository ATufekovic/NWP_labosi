<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\File;

class FileData extends Model
{
    use HasFactory;
    protected $table = 'file_data';

    protected $fillable = [
        'data',
        'file_id'
    ];

    /**
     * The accessor for the file the data belongs to
     */
    public function file(){
        $this->belongsTo(File::class);
    }
}
