<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $table = 'incomes';

    protected $fillable = [
        'user_id',
        'pocket_id',
        'amount',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pocket()
    {
        return $this->belongsTo(UserPocket::class, 'pocket_id');
    }
}
