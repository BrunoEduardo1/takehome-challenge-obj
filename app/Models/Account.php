<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'balance'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
