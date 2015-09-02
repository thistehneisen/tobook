<?php
/*
|--------------------------------------------------------------------------
| Appointment Scheduler routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'appointment-scheduler',
    'before' => ['auth', 'only.business', 'premium.modules:appointment']
], function () {
    // Service Category
    \App\Appointment\Controllers\Categories::crudRoutes(
        'services/categories',
        'as.services.categories'
    );

    // Service Resource
    \App\Appointment\Controllers\Resources::crudRoutes(
        'services/resources',
        'as.services.resources'
    );

    // Service Room
    \App\Appointment\Controllers\Rooms::crudRoutes(
        'services/rooms',
        'as.services.rooms'
    );

    // Extra service
    \App\Appointment\Controllers\ExtraServices::crudRoutes(
        'services/extras',
        'as.services.extras'
    );

    // Service
    \App\Appointment\Controllers\Services::crudRoutes(
        'services',
        'as.services'
    );

    Route::group([
        'prefix' => 'services',
    ], function () {
        Route::get('custom-time/{id}', [
            'as' => 'as.services.customTime',
            'uses' => 'App\Appointment\Controllers\Services@customTime'
        ]);

        Route::get('custom-time/{id}/upsert/{customTimeId?}', [
            'as'   => 'as.services.customTime.upsert',
            'uses' => 'App\Appointment\Controllers\Services@upsertCustomTime'
        ]);

        Route::post('custom-time/{id}/upsert/{customTimeId?}', [
            'uses' => 'App\Appointment\Controllers\Services@doUpsertCustomTime'
        ]);

        Route::get('custom-time/{id}/delete/{customTimeId?}', [
            'as'   => 'as.services.customTime.delete',
            'uses' => 'App\Appointment\Controllers\Services@deleteCustomTime'
        ]);

        Route::get('employees/free-time', [
            'as' => 'as.employees.freetime',
            'uses' => 'App\Appointment\Controllers\Employees@freetime'
        ]);
    });

    // Employee
    \App\Appointment\Controllers\Employees::crudRoutes(
        'employees',
        'as.employees'
    );

    Route::group([
        'prefix' => 'employees',
    ], function () {
        Route::get('{id}/{date?}', [
            'as' => 'as.employee',
            'uses' => 'App\Appointment\Controllers\Index@employee'
        ]);

        Route::get('get-freetime-form', [
            'as' => 'as.employees.freetime.form',
            'uses' => 'App\Appointment\Controllers\Employees@getFreetimeForm'
        ]);

        Route::post('add-employee-freetime', [
            'as' => 'as.employees.freetime.add',
            'uses' => 'App\Appointment\Controllers\Employees@addEmployeeFreeTime'
        ]);

        Route::post('edit-employee-freetime', [
            'as' => 'as.employees.freetime.edit',
            'uses' => 'App\Appointment\Controllers\Employees@editEmployeeFreeTime'
        ]);

        Route::post('delete-employee-freetime', [
            'as' => 'as.employees.freetime.delete',
            'uses' => 'App\Appointment\Controllers\Employees@deleteEmployeeFreeTime'
        ]);

        Route::get('work-shift', [
            'as' => 'as.employees.customTime',
            'uses' => 'App\Appointment\Controllers\Employees@customTime'
        ]);

        Route::get('work-shift/upsert/{customTimeId?}', [
            'as'   => 'as.employees.customTime.upsert',
            'uses' => 'App\Appointment\Controllers\Employees@upsertCustomTime'
        ]);

        Route::post('work-shift/upsert/{customTimeId?}', [
            'uses' => 'App\Appointment\Controllers\Employees@doUpsertCustomTime'
        ]);

        Route::get('work-shift/delete/{customTimeId?}', [
            'as'   => 'as.employees.customTime.delete',
            'uses' => 'App\Appointment\Controllers\Employees@deleteCustomTime'
        ]);

        Route::get('work-shift-planning', [
            'as' => 'as.employees.employeeCustomTime',
            'uses' => 'App\Appointment\Controllers\Employees@workshiftPlanning'
        ]);

        Route::post('ajax/update-work-shift', [
            'as' => 'as.employees.employeeCustomTime.updateWorkshift',
            'uses' => 'App\Appointment\Controllers\Employees@updateEmployeeWorkshift'
        ]);

        Route::post('update-work-shift-plan/{employeedId}', [
            'as' => 'as.employees.employeeCustomTime.massUpdate',
            'uses' => 'App\Appointment\Controllers\Employees@massUpdateWorkShift'
        ]);

        Route::post('employee-delete-work-shift/{employeedId}', [
            'as' => 'as.employees.employeeCustomTime.delete',
            'uses' => 'App\Appointment\Controllers\Employees@deleteEmployeeCustomTime'
        ]);

        Route::get('default-time/{id}', [
            'as' => 'as.employees.defaultTime.get',
            'uses' => 'App\Appointment\Controllers\Employees@defaultTime'
        ]);

        Route::post('default-time', [
            'as' => 'as.employees.defaultTime',
            'uses' => 'App\Appointment\Controllers\Employees@updateDefaultTime'
        ]);
    });

    // Bookings
    \App\Appointment\Controllers\Bookings::crudRoutes(
        'bookings',
        'as.bookings'
    );

    Route::group([
        'prefix' => 'bookings',
    ], function () {
        Route::get('invoices', [
            'as' => 'as.bookings.invoices',
            'uses' => 'App\Appointment\Controllers\Bookings@invoices'
        ]);

        Route::get('customers', [
            'as' => 'as.bookings.customers',
            'uses' => 'App\Appointment\Controllers\Bookings@customers'
        ]);

        Route::post('cut', [
            'as' => 'as.bookings.cut',
            'uses' => 'App\Appointment\Controllers\Ajax\Bookings@cut'
        ]);

        Route::post('discard-cut', [
            'as' => 'as.bookings.discard-cut',
            'uses' => 'App\Appointment\Controllers\Ajax\Bookings@discardCut'
        ]);

        Route::post('paste', [
            'as' => 'as.bookings.paste',
            'uses' => 'App\Appointment\Controllers\Ajax\Bookings@paste'
        ]);

        Route::get('get-booking-form', [
            'as' => 'as.bookings.form',
            'uses' => 'App\Appointment\Controllers\Bookings@getBookingForm'
        ]);

        Route::get('get-add-extra-service-form', [
            'as' => 'as.bookings.extra-service-form',
            'uses' => 'App\Appointment\Controllers\Bookings@getAddExtraServiceForm'
        ]);

        Route::post('add-extra-services', [
            'as' => 'as.bookings.add-extra-services',
            'uses' => 'App\Appointment\Controllers\Bookings@addExtraServices'
        ]);

        Route::post('remove-extra-service', [
            'as' => 'as.bookings.remove-extra-service',
            'uses' => 'App\Appointment\Controllers\Bookings@removeExtraService'
        ]);

        Route::get('get-change-status-form', [
            'as' => 'as.bookings.change-status-form',
            'uses' => 'App\Appointment\Controllers\Bookings@getChangeStatusForm'
        ]);

        Route::post('change-status', [
            'as' => 'as.bookings.change-status',
            'uses' => 'App\Appointment\Controllers\Bookings@changeStatus'
        ]);

        Route::get('modify-booking', [
            'as' => 'as.bookings.modify-form',
            'uses' => 'App\Appointment\Controllers\Bookings@getModifyBookingForm'
        ]);

        Route::post('modify-booking', [
            'as' => 'as.bookings.modify-form',
            'uses' => 'App\Appointment\Controllers\Bookings@doModifyBooking'
        ]);

        Route::get('search-consumer', [
            'as' => 'as.bookings.search-consumer',
            'uses' => 'App\Appointment\Controllers\Bookings@searchConsumer'
        ]);

        Route::get('ajax/get-employee-services', [
            'as' => 'as.bookings.employee.services',
            'uses' => 'App\Appointment\Controllers\Ajax\Bookings@getEmployeeServicesByCategory'
        ]);

        Route::get('ajax/get-service-times', [
            'as' => 'as.bookings.service.times',
            'uses' => 'App\Appointment\Controllers\Ajax\Bookings@getServiceTimes'
        ]);

        Route::post('add-booking-service', [
            'as' => 'as.bookings.service.add',
            'uses' => 'App\Appointment\Controllers\Bookings@addBookingService'
        ]);

        Route::post('delete-booking-service', [
            'as' => 'as.bookings.service.delete',
            'uses' => 'App\Appointment\Controllers\Bookings@deleteBookingService'
        ]);

        Route::post('add-booking', [
            'as' => 'as.bookings.add',
            'uses' => 'App\Appointment\Controllers\Bookings@upsertBooking'
        ]);

        Route::get('ajax/booking-history', [
            'as' => 'bookings.history',
            'uses' => 'App\Appointment\Controllers\Ajax\Bookings@getHistory',
        ]);
    });

    Route::group([
        'prefix' => 'master-cats',
    ], function () {
        Route::get('ajax/get-treatment-types', [
            'as' => 'as.master-cats.treatment-types',
            'uses' => 'App\Appointment\Controllers\Ajax\MasterCategories@getTreatmentTypes'
        ]);
    });

    // Embed
    Route::get('embed', [
        'as' => 'as.embed.index',
        'uses' => 'App\Appointment\Controllers\Embed@index'
    ]);

    Route::get('preview', [
        'as' => 'as.embed.preview',
        'uses' => 'App\Appointment\Controllers\Embed@preview'
    ]);

    // Options
    Route::group([
        'prefix' => 'options',
    ], function () {
        Route::get('working-time', [
            'uses' => 'App\Appointment\Controllers\Options@workingTime'
        ]);

        Route::post('working-time', [
            'uses' => 'App\Appointment\Controllers\Options@updateWorkingTime'
        ]);

        Route::get('discount/{page}', [
            'as'   => 'as.options.discount',
            'uses' => 'App\Appointment\Controllers\Options@discount'
        ]);

        Route::get('{page?}', [
            'as'   => 'as.options',
            'uses' => 'App\Appointment\Controllers\Options@index'
        ]);

        Route::post('{page?}', [
            'uses' => 'App\Appointment\Controllers\Options@update'
        ]);
    });

    // Report
    Route::group([
        'prefix' => 'reports',
    ], function () {
        Route::get('statistics', [
            'as' => 'as.reports.statistics',
            'uses' => 'App\Appointment\Controllers\Reports@statistics'
        ]);

        Route::get('monthly', [
            'as' => 'as.reports.monthly',
            'uses' => 'App\Appointment\Controllers\Reports@monthlyReport'
        ]);

        Route::get('monthly/calendar', [
            'as' => 'as.reports.monthly.calendar',
            'uses' => 'App\Appointment\Controllers\Reports@calendar'
        ]);

        Route::get('monthly/monthly', [
            'as' => 'as.reports.monthly.monthly',
            'uses' => 'App\Appointment\Controllers\Reports@monthly'
        ]);
    });

    // Catch-all route should always be at the bottom
    Route::get('/{date?}', [
        'as' => 'as.index',
        'uses' => 'App\Appointment\Controllers\Index@index'
    ]);
});

/*
|--------------------------------------------------------------------------
| Cancel routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'cancel',
], function () {
    Route::get('as/{uuid}', [
        'as' => 'as.bookings.cancel',
        'uses' => 'App\Appointment\Controllers\Bookings@cancel'
    ]);

    Route::get('as/do/{uuid}', [
        'as' => 'as.bookings.doCancel',
        'uses' => 'App\Appointment\Controllers\Bookings@doCancel'
    ]);
});
