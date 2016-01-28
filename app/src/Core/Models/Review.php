<?php namespace App\Core\Models;

use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App, View, Mail, Log, Settings;
use Queue;

class Review extends Base
{
    protected $table = 'reviews';

    const STATUS_INIT   = 'init';
    const STATUS_APPROVED   = 'approved';
    const STATUS_REJECTED = 'rejected';

    public $fillable = [
        'name',
        'comment',
        'environment',
        'service',
        'price_ratio',
        'avg_rating',
        'status',
    ];

    public $rulesets = [
        'saving' => [
            'environment' => 'required',
            'service'     => 'required',
            'price_ratio' => 'required',
        ]
    ];
    
    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public static function sendReviewRequest()
    {   
        $now = Carbon::now();

        $minutes = intval($now->copy()->minute);
        $remove = $minutes % 15;
        $compensate = 15 - $remove;

        $bookings = Booking::where('date', '=', $now->toDateString())
            ->where('end_at', '=', $now->copy()
                    ->subHours(2)
                    ->addMinutes($compensate)
                    ->second(0)
                    ->toTimeString()
            )->get();

        // Loop thourgh bookings and add to queue
        foreach ($bookings as $booking) {
            Log::info("Send review request to customer", [$booking->consumer->email, $booking->end_at]);
            $emailSubject = trans('as.review.email.subject', [
                    'env' => App::environment()
            ]);
            
            $body = route('businesses.review', [$booking->user->id, $booking->user->business->slug]);
            $receiver     = $booking->consumer->email;
            $receiverName = $booking->consumer->name;
            // Enque to send email
            Queue::push(function ($job) use ($emailSubject, $body, $receiver, $receiverName) {
                Mail::send('modules.as.emails.review', [
                    'title' => $emailSubject,
                    'body' => nl2br($body)
                ], function ($message) use ($emailSubject, $receiver, $receiverName) {
                    $message->subject($emailSubject);
                    $message->to($receiver, $receiverName);
                });
                $job->delete();
            });
        }
    }

    public function setAvgRating()
    {
        $this->avg_rating = ($this->environment + $this->price_ratio + $this->service) / 3;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
