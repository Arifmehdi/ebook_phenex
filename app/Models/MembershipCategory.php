<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'subscription_fee', 'free_books', 'access_all_books'];
}
