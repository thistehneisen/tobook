<?php namespace App\Core\Models;

class Module extends Base
{
	public $visible = ['id', 'name', 'uri'];
	/**
	 * Define the relationship with User model
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany('User')->withPivot(['start', 'end']);
	}
}
