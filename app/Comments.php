<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model {

	//comments table in database
	protected $guarded = [];
	
	// user who commented
	public function author()
	{
		return $this->belongsTo('App\User','user_id');
	}
	
	public function post()
	{
		return $this->belongsTo('App\Blogs','blog_id');
	}

}
