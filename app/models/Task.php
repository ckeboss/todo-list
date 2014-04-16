<?php

class Task extends Eloquent {

	public static $rules = array(
		'user_id'=>'required|integer',
		'title'=>'required|max:200',
		'description'=>'required|max:500',
    );
	
	public function user() {
		return $this->belongsTo('User');
	}
}
