<?php namespace App\Appointment\Models;
use DB;
use App\Appointment\Models\RoomService;

class Room extends \App\Core\Models\Base
{
    protected $table = 'as_rooms';

    public $fillable = ['name', 'description'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    /**
     * Link rooms to a certain service
     */
    public static function associateWithService($roomIds, $service)
    {
        //Delete old room services;
        DB::table('as_room_service')
            ->where('service_id', $service->id)
            ->delete();

        foreach ($roomIds as $roomId) {
            $room = self::find($roomId);
            $roomService = new RoomService;
            $roomService->service()->associate($service);
            $roomService->room()->associate($room);
            $roomService->save();
        }
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function services()
    {
        return $this->belongsToMany(
            'App\Appointment\Models\Service',
            'as_room_service'
        );
    }
}
