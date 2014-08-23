<?php namespace App\Core\Models;

use Carbon\Carbon;
use DB;
use App\Core\Models\User;

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

	/**
	 * Define the relationship with User model
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany('User')->withPivot(['id', 'start', 'end']);
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
     * Delete a record in pivot table
     *
     * @param int $id ID in pivot table
     *
     * @return int
     */
    public static function deletePeriod($id)
    {
        return DB::table('module_user')
            ->where('id', $id)
            ->delete();
    }
}
