<?php namespace App\Consumers\Models;

use Confide, DB;
use App\Core\Models\User;
use Watson\Validating\ValidationException;

class Consumer extends \App\Core\Models\Base
{
    public $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postcode',
        'country',
    ];

    protected $rulesets = [
        'saving' => [
            'email'         => 'required|email',
            // 'first_name'    => 'required',
            // 'last_name'     => 'required',
            // 'phone'         => 'required|numeric'
        ]
    ];

    /**
     * Temporarily disable search using ES on model Consumer
     *
     * @var boolean
     */
    public $isSearchable = false;

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function setEmailAttribute($value)
    {
        $value = trim($value);
        $this->attributes['email'] = $value;
    }

    public function setPhoneAttribute($value)
    {
        //Remove + and spaces since phone is numberic value
        $value =  str_replace([' ', '+'], '', $value);
        $this->attributes['phone'] = $value;
    }

    private function checkService($table)
    {
        $service = DB::table($table)
                    ->where('user_id', Confide::user()->id)
                    ->where('consumer_id', $this->id)
                    ->first();

        if ($service) {
            return true;
        } else {
            return false;
        }
    }

    public function getServiceAttribute()
    {
        $service = [];

        $services = [
            'as_consumers' => trans('dashboard.appointment'),
            'lc_consumers' => trans('dashboard.loyalty'),
        ];

        foreach ($services as $key => $value) {
            if ($this->checkService($key)) {
                $service[substr($key, 0, 2)] = $value;
            }
        }

        return $service;
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    /**
     * Create a new consumer
     *
     * @param array                    $data   Consumer's information will be saved
     * @param int|App\Core\Models\User $userId The ID of user this consumer
     *                                         belongs to
     *
     * @throws Watson\Validating\ValidationException If validation is not passed
     * @throws App\Consumers\DuplicatedException     If there is existing consumer with the same email
     * @throws Illuminate\Database\QueryException    If there are database errors
     *
     * @return App\Consumers\Model
     */
    public static function make($data, $userId = null)
    {
        // If there is no information passed, get the current user
        $user = Confide::user();
        if ($userId instanceof \App\Core\Models\User) {
            $user = $userId;
        } elseif ((int) $userId > 0) {
            $user = User::findOrFail($userId);
        }

        if (empty($data['email'])) {
            $data['email'] = '';
        }

        try {
            $consumer = new self();
            $consumer->fill($data);
            $consumer->saveOrFail();
        } catch (\Illuminate\Database\QueryException $ex) {
            // Check if there's existing consumer with the same email
            $consumer = self::where('email', $data['email'])->first();
            if ($consumer !== null) {
                $ex = new \App\Consumers\DuplicatedException('There is already a consumer with email '.$data['email']);
                $ex->setDuplicated($consumer);
                throw $ex;
            }
            // Maybe other database erorrs, so we pass it to other handlers
            throw $ex;
        }

        $user->consumers()->attach($consumer->id);

        return $consumer;
    }

    public static function importCsv($csvLines, User $businessUser = null)
    {
        $headersRow = array_shift($csvLines);
        if (empty($headersRow)) {
            throw new ValidationException(trans('co.import.csv_header_is_missing'));
        }

        // configurable?
        $delimiter = ',';

        $headers = explode($delimiter, $headersRow);
        $obj = new self();
        $fieldIndices = [];
        foreach ($headers as $index => $header) {
            if (in_array($header, $obj->fillable)) {
                $fieldIndices[$header] = $index;
            }
        }
        foreach ($obj->rulesets['saving'] as $field => $savingRule) {
            if (strpos($savingRule, 'required') !== false and !isset($fieldIndices[$field])) {
                throw new ValidationException(trans('co.import.csv_required_field_x_is_missing', ['field' => $field]));
            }
        }

        $rowIndex = 1;
        $results = [];
        while (count($csvLines) > 0) {
            $rowIndex++;
            $row = array_shift($csvLines);
            $cells = explode($delimiter, $row);
            if (count($cells) != count($headers)) {
                // ignore all rows with mismatched number of cells
                continue;
            }

            $newObj = new self();
            foreach ($fieldIndices as $field => $index) {
                $newObj->$field = $cells[$index];
            }

            try {
                $newObj->saveOrFail();

                if (!empty($businessUser)) {
                    $newObj->users()->attach($businessUser->id);
                }

                $results[] = [
                    'row' => $rowIndex,
                    'success' => true,
                    'consumer_id' => $newObj->id,
                ];
            } catch (ValidationException $e) {
                $results[] = [
                    'row' => $rowIndex,
                    'success' => false,
                    'error' => $newObj->getErrors()->first(),
                ];
            }
        }

        return $results;
    }

    /**
     * Hide this consumer
     *
     * @param int $userId ID of user whom this consumer will be hidden from
     *
     * @return Consumer
     */
    public function hide($userId)
    {
        return $this->users()
            ->updateExistingPivot($userId);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    /**
     * Define a many-to-many relationship to User
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Core\Models\User');
    }
}
