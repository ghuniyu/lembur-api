<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{

    protected $fillable = ['user_id','tanggal_lembur','jam','alasan','fee'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
