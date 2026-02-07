<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'phone',
        'email',
        'township',
        'state_division',
        'user_id',
    ];

    protected $with = ['user'];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
