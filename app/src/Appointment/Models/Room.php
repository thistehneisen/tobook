<?php namespace App\Appointment\Models;
use DB;

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

        foreach ($roomIds as $key => $id) {
            $room = self::find($id);
            $roomService = new RoomService();
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
}
