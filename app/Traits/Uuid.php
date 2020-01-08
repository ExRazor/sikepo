<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id = Str::uuid()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
