<?php namespace App\Core\Models;

use Carbon\Carbon;
use DB;
use App\Core\Models\User;
use Illuminate\Support\Collection;

class Module extends Base
{
	public $visible = ['id', 'name', 'uri'];

    public $fillable = ['name', 'uri'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required',
            'uri'  => 'required',
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    /**
     * Tell if the active time of this module has passed
     *
     * @return boolean
     */
    public function getIsPassedAttribute()
    {
        return Carbon::now()->gt((new Carbon($this->pivot->end))->endOfDay());
    }

    /**
     * Tell if the current time is in the active time of this module
     *
     * @return boolean
     */
    public function getIsNowAttribute()
    {
        $now = Carbon::now();
        return (new Carbon($this->pivot->start))->lte($now) &&
            $now->lte((new Carbon($this->pivot->end))->endOfDay());
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
	/**
	 * Define the relationship with User model
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany('User')
            ->withPivot(['id', 'start', 'end', 'is_active'])
            ->withTimestamps();
	}

    /**
     * Check if the given start and end date is overlapped with existing data
     *
     * @param App\Core\Models\User $user
     * @param Carbon $start
     * @param Carbon $end
     *
     * @return boolean
     */
    public function isOverlapped(User $user, Carbon $start, Carbon $end)
    {
        $sql = <<< SQL
SELECT COUNT(*) AS overlapped
FROM varaa_module_user t
WHERE user_id = ? AND module_id = ? AND (
    (? > t.start AND ? < t.end) OR
    (? > t.start AND ? < t.end) OR
    (? = t.start OR ? = t.end)
)
SQL;
        $params = [
            $user->id,
            $this->id,
            $start,
            $start,
            $end,
            $end,
            $start,
            $end
        ];
        $result = DB::selectOne($sql, $params);

        if (!empty($result)) {
            return (bool) $result->overlapped;
        }

        throw new \RuntimeException('Something went wrong');
    }

    /**
     * Toggle activation of a period
     *
     * @param int $id ID in pivot table
     *
     * @return int
     */
    public static function toggleActivation($id)
    {
        // This might look stupid where you could negativate in just one query
        $result =DB::table('module_user')
            ->where('id', $id)
            ->first();
        $value = (bool) $result->is_active;
        // Update
        return DB::table('module_user')
            ->where('id', $id)
            ->update(['is_active' => (int) !$value]);
    }

    /**
     * Get all active period of this user
     *
     * @param User   $user
     * @param string $moduleName
     *
     * @return boolean
     */
    public static function getActivePeriods(User $user, $moduleName)
    {
        $module = static::where('name', $moduleName)->firstOrFail();
        $table = DB::getQueryGrammar()->wrapTable('module_user');
        $sql = <<< SQL
SELECT *
FROM $table t
WHERE user_id = ? AND module_id = ? AND is_active = ? AND (
    (? <= t.start AND ? <= t.end) OR
    (? >= t.start AND ? <= t.end)
)
SQL;
        $now = Carbon::now();
        $params = [
            $user->id,
            $module->id,
            true,
            $now,
            $now,
            $now,
            $now,
        ];
        $result = DB::select($sql, $params);
        return new Collection($result);
    }
}
