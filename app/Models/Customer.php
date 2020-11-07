<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    //Fields to mass assignment
    protected $fillable = ['name', 'document', 'status', 'user_id'];

    protected $attributes = [
        'status' => 0
    ];

    public function statusOptions()
    {
        return [
            0 => 'New',
            1 => 'Active',
            2 => 'Suspended',
            3 => 'Cancelled'
        ];
    }

    public function user(){
        return $this->BelongsTo(User::class);
    }

    public function number(){
        return $this->hasMany(Number::class);
    }
}
