<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
                    'tahun_akademik',
                    'semester',
                    'status'
                ];

    public function collaboration()
    {
        return $this->hasOne('App\Collaboration','id_ta');
    }

    public function ewmp()
    {
        return $this->hasMany('App\Ewmp','id_ta');
    }
}
