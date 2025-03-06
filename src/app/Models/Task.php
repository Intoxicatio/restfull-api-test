<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'create_date',
        'status',
        'priority',
        'category'
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
