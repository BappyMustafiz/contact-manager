<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'email',
        'phone',
        'user_id',
        'list_id',
        'profile_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }


    protected $hidden = [
        'updated_at', 'deleted_at'
    ];
}
