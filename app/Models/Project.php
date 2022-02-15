<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;

class Project extends Model{

    protected $table = 'projects';

    public function user(){
        return $this->hasOne(User::class,'user_id','id');
    }
}
