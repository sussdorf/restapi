<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Ipadress extends Model
{
    use HasFactory;
    protected $table = 'ip_addresses';

    protected $fillable = [
        'id',
        'subnet_id',
        'ip',
        'customer',
        'status',
    ];


}
