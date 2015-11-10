<?php namespace App\Consumers\Models;

use Confide, DB, Util, Hashids;
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
        'notes',
        'receive_email',
        'receive_sms'
    ];

    protected $rulesets = [
        'saving' => [
            'email'         => 'email',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required|numeric'
        ]
    ];

    const STATUS_NEW = 'new';
    const STATUS_EXIST = 'exist';

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

    public function getIsNewAttribute()
    {
        return (isset($this->attributes['is_new']))
            ? (bool) $this->attributes['is_new']
            : false;
    }

    public function getServiceAttribute()
    {
        $services = [];

        if (\App\Appointment\Models\Booking::ofCurrentUser()
                ->where('consumer_id', $this->id)
                ->first() !== null) {
            $services['as'] = trans('dashboard.appointment');
        }

        if (\App\LoyaltyCard\Models\Consumer::ofCurrentUser()
                ->where('consumer_id', $this->id)
                ->first() !== null) {
            $services['lc'] = trans('dashboard.loyalty');
        }

        return $services;
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    /**
     * Create a new consumer or get the existing one
     *
     * @param array                    $data   Consumer's information will be saved
     * @param int|App\Core\Models\User $userId The ID of user this consumer
     *                                         belongs to
     *
     * @throws Watson\Validating\ValidationException If validation is not passed
     *
     * @return App\Consumers\Model\Consumer
     */
    public static function make($data, $userId = null)
    {
        // if there is no information passed, get the current user
        $user = Confide::user();
        if ($userId instanceof \App\Core\Models\User) {
            $user = $userId;
        } elseif ((int) $userId > 0) {
            $user = User::findOrFail($userId);
        }

        // validate input data by creating draft consumer, banana?
        $draft = new self();
        $draft->fill($data);
        $draft->saveOrFail();

        // check duplicated / existing consumer here
        // since users don't want to share consumers with other businesses
        // consumer should be unique within 1 user based only
        // duplicated consumers DB-wise is fine
        // use scope ofUser for preventing error in incognito mode
        $similarConsumers = self::ofUser($user->id)
            ->where('first_name', trim($data['first_name']))
            ->where('last_name', trim($data['last_name']))
            ->get();

        $consumer = null;
        if ($similarConsumers !== null) {
            foreach ($similarConsumers as $c) {
                if (Util::isSimilarPhoneNumber($c->phone, trim($data['phone']))) {
                    $consumer = $c;
                    break;
                }
            }
        }

        // if can't find any match then return the draft consumer
        if ($consumer === null) {
            $consumer = $draft;
        } else {
            // otherwise, remove the draft for good
            $draft->forceDelete();
            //consumer is_new default is true
            $consumer->is_new  = false;
            $consumer->email   = trim($data['email']);
            if (!empty($data['address'])) {
                $consumer->address = trim($data['address']);
            }
            $consumer->saveOrFail();
        }

        try {
            $user->consumers()->attach($consumer->id);
        } catch (\Exception $ex) {}

        return $consumer;
    }

    /**
     * Handle creating consumer
     *
     * @param array                $data
     * @param App\Core\Models\User $user
     *
     * @return App\Consumers\Model\Consumer
     */
    public static function handleConsumer($data, $user = null)
    {
        if (empty($user)) {
            $decoded = Hashids::decrypt($data['hash']);
            if (empty($decoded)) {
                return;
            }
            $user = User::find($decoded[0]);
        }

        unset($data['hash']);

        return self::make($data, $user);
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
                $newObj->$field = str_replace(["\xA0", "\xC2"], "", trim($cells[$index]));
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

    public function lc()
    {
        return $this->hasOne('App\LoyaltyCard\Models\Consumer', 'consumer_id')
            ->ofCurrentUser();
    }

    //--------------------------------------------------------------------------
    // SEARCH
    //--------------------------------------------------------------------------
    /**
     * @{@inheritdoc}
     */
    public function getCustomSearchQuery($query)
    {
        return $query->orderBy('first_name', 'ASC')
            ->orderBy('last_name', 'ASC');
    }
}
