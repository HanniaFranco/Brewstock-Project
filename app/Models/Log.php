<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'Logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'record_id',
        'ip_address',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}