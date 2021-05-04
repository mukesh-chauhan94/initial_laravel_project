<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
	use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $table = 'restaurant';
    protected $guarded = ['id']; 
    protected $fillable = ['name','code','description','phone_number','email'];


    public function restaurantImage() {
        return $this->hasOne('App\RestaurantImage','restaurant_id', 'id');
    }
}
