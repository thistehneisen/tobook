<?php
Event::listen('payment.process', 'App\Appointment\Listeners\PaymentProcessListener');
Event::listen('payment.success', 'App\Appointment\Listeners\PaymentSuccessListener');
Event::listen('payment.cancelled', 'App\Appointment\Listeners\PaymentCancelledListener');
