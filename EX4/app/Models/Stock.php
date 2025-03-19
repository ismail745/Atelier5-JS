<?php

// app/Models/Stock.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['product_name', 'quantity'];
}