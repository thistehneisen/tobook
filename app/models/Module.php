<?php

class Module extends Eloquent
{
	public $visible = ['id', 'name', 'url'];
	/**
	 * Define the relationship with User model
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany('User');
	}
}