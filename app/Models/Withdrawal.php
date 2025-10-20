<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_details',
        'status',
        'admin_notes',
    ];

    /**
     * Get the user that owns the withdrawal request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
