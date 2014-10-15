<?php
Event::listen('payment.success', 'App\Appointment\Listeners\PaymentSuccessListener');
