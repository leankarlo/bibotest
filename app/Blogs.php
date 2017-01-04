<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Blogs extends Model {

	//posts table in database
	protected $guarded = [];
	public function comments()
	{
		return $this->hasMany('App\Comments','blog_id');
	}
	
	public function author()
	{
		return $this->belongsTo('App\User','user_id');
	}

}
