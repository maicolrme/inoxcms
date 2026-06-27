<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Profile extends Model
{
    use HasFactory;

    protected $table = 'profile';

    public $timestamps = true;

    protected $fillable = ['id'];

    protected $casts = [];

}