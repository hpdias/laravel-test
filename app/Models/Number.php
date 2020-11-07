<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Number extends Model
{
    use HasFactory;
    use SoftDeletes;

    //Fields to mass assignment
    protected $fillable = ['customer_id', 'number', 'status'];

    protected $attributes = [
        'status' => 1
    ];

    public function statusOptions()
    {
        return [
            1 => 'Active',
            0 => 'Inactive',
            2 => 'Cancelled'
        ];
    }


    public function customer(){
       return  $this->belongsTo(Customer::class);
    }

    public function numberPreference(){
        return  $this->hasMany(NumberPreference::class);
     }
}
