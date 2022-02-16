<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;

class Project extends Model{

    public function user(){
        return $this->belongsTo(User::class);
    }
}