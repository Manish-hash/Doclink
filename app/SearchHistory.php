<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    //
    protected $fillable = ['user_id', 'search_query'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_id', 'id');
    }

    public $hidden = ['created_at', 'updated_at'];
}
