<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPocket extends Model
{
    protected $table = 'user_pockets';

    protected $fillable = [
        'user_id',
        'name',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function incomes()
    {
        return $this->hasMany(Income::class, 'pocket_id');
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'pocket_id');
    }
}
