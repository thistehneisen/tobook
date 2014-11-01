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

    Route::get('/employee/{id}/{date?}', [
        'as' => 'as.employee',
        'uses' => 'App\Appointment\Controllers\Index@employee'
    ]);

    Route::get('/employees/get-freetime-form', [
        'as' => 'as.employees.freetime.form',
        'uses' => 'App\Appointment\Controllers\Employees@getFreetimeForm'
    ]);

    Route::post('/employees/add-employee-freetime', [
        'as' => 'as.employees.freetime.add',
        'uses' => 'App\Appointment\Controllers\Employees@addEmployeeFreeTime'
    ]);

    Route::post('/employees/delete-employee-freetime', [
        'as' => 'as.employees.freetime.delete',
        'uses' => 'App\Appointment\Controllers\Employees@deleteEmployeeFreeTime'
    ]);

    Route::get('services', [
        'as' => 'as.services.index',
        'uses' => 'App\Appointment\Controllers\Services@index'
    ]);

    Route::get('services/create', [
        'as' => 'as.services.create',
        'uses' => 'App\Appointment\Controllers\Services@create'
    ]);

    Route::post('services/create', [
        'as' => 'as.services.create',
        'uses' => 'App\Appointment\Controllers\Services@doCreate'
    ]);

    Route::get('services/edit', [
        'as' => 'as.services.edit',
        'uses' => 'App\Appointment\Controllers\Services@edit'
    ]);

    Route::post('services/edit', [
        'as' => 'as.services.edit',
        'uses' => 'App\Appointment\Controllers\Services@doEdit'
    ]);

    Route::get('services/custom-time/{id}', [
        'as' => 'as.services.customTime',
        'uses' => 'App\Appointment\Controllers\Services@customTime'
    ]);

    Route::get('services/custom-time/{id}/upsert/{customTimeId?}', [
        'as'   => 'as.services.customTime.upsert',
        'uses' => 'App\Appointment\Controllers\Services@upsertCustomTime'
    ]);

    Route::post('services/custom-time/{id}/upsert/{customTimeId?}', [
        'uses' => 'App\Appointment\Controllers\Services@doUpsertCustomTime'
    ]);

    Route::get('services/custom-time/{id}/delete/{customTimeId?}', [
        'as'   => 'as.services.customTime.delete',
        'uses' => 'App\Appointment\Controllers\Services@deleteCustomTime'
    ]);

    Route::get('services/delete', [
        'as' => 'as.services.delete',
        'uses' => 'App\Appointment\Controllers\Services@delete'
    ]);

    Route::post('services/destroy', [
        'as' => 'as.services.destroy',
        'uses' => 'App\Appointment\Controllers\Services@destroy'
    ]);

    // Bookings
    \App\Appointment\Controllers\Bookings::crudRoutes(
        'bookings',
        'as.bookings'
    );

    Route::get('bookings/invoices', [
        'as' => 'as.bookings.invoices',
        'uses' => 'App\Appointment\Controllers\Bookings@invoices'
    ]);

    Route::get('bookings/customers', [
        'as' => 'as.bookings.customers',
        'uses' => 'App\Appointment\Controllers\Bookings@customers'
    ]);

    Route::get('bookings/statistics', [
        'as' => 'as.bookings.statistics',
        'uses' => 'App\Appointment\Controllers\Stat@index'
    ]);

    Route::get('bookings/statistics/calendar', [
        'as' => 'as.bookings.statistics.calendar',
        'uses' => 'App\Appointment\Controllers\Stat@calendar'
    ]);

    Route::get('bookings/statistics/monthly', [
        'as' => 'as.bookings.statistics.monthly',
        'uses' => 'App\Appointment\Controllers\Stat@monthly'
    ]);

    Route::post('bookings/cut', [
        'as' => 'as.bookings.cut',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@cut'
    ]);

    Route::post('bookings/discard-cut', [
        'as' => 'as.bookings.discard-cut',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@discardCut'
    ]);

    Route::post('bookings/paste', [
        'as' => 'as.bookings.paste',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@paste'
    ]);

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

    // Employee
    \App\Appointment\Controllers\Employees::crudRoutes(
        'employees',
        'as.employees'
    );

    // Service
    \App\Appointment\Controllers\Services::crudRoutes(
        'services',
        'as.services'
    );

    \App\Appointment\Controllers\ExtraServices::crudRoutes(
        'services/extras',
        'as.services.extras'
    );

    Route::get('services/employees/free-time', [
        'as' => 'as.employees.freetime',
        'uses' => 'App\Appointment\Controllers\Employees@freetime'
    ]);

    Route::get('employees/work-shift', [
        'as' => 'as.employees.customTime',
        'uses' => 'App\Appointment\Controllers\Employees@customTime'
    ]);

    Route::get('employees/work-shift/upsert/{customTimeId?}', [
        'as'   => 'as.employees.customTime.upsert',
        'uses' => 'App\Appointment\Controllers\Employees@upsertCustomTime'
    ]);

    Route::post('employees/work-shift/upsert/{customTimeId?}', [
        'uses' => 'App\Appointment\Controllers\Employees@doUpsertCustomTime'
    ]);

    Route::get('employees/work-shift/delete/{customTimeId?}', [
        'as'   => 'as.employees.customTime.delete',
        'uses' => 'App\Appointment\Controllers\Employees@deleteCustomTime'
    ]);

    Route::get('employees/work-shift-planning/{employeedId?}/{date?}', [
        'as' => 'as.employees.employeeCustomTime',
        'uses' => 'App\Appointment\Controllers\Employees@employeeCustomTime'
    ]);

    Route::post('employees/work-shift-planning/{employeedId?}/{date?}', [
        'as' => 'as.employees.employeeCustomTime.upsert',
        'uses' => 'App\Appointment\Controllers\Employees@upsertEmployeeCustomTime'
    ]);

    Route::get('employees/work-shift-summary/{date?}', [
        'as' => 'as.employees.employeeCustomTime.summary',
        'uses' => 'App\Appointment\Controllers\Employees@employeeCustomTimeSummary'
    ]);

    Route::post('employees/update-work-shift-plan/{employeedId}', [
        'as' => 'as.employees.employeeCustomTime.massiveUpdate',
        'uses' => 'App\Appointment\Controllers\Employees@massiveUpdateEmployeeCustomTime'
    ]);

    Route::post('employees/employee-delete-work-shift/{employeedId}', [
        'as' => 'as.employees.employeeCustomTime.delete',
        'uses' => 'App\Appointment\Controllers\Employees@deleteEmployeeCustomTime'
    ]);

    Route::get('employees/default-time/{id}', [
        'as' => 'as.employees.defaultTime.get',
        'uses' => 'App\Appointment\Controllers\Employees@defaultTime'
    ]);

    Route::post('employees/default-time', [
        'as' => 'as.employees.defaultTime',
        'uses' => 'App\Appointment\Controllers\Employees@updateDefaultTime'
    ]);

    Route::get('bookings/get-booking-form', [
        'as' => 'as.bookings.form',
        'uses' => 'App\Appointment\Controllers\Bookings@getBookingForm'
    ]);

    Route::get('bookings/get-add-extra-service-form', [
        'as' => 'as.bookings.extra-service-form',
        'uses' => 'App\Appointment\Controllers\Bookings@getAddExtraServiceForm'
    ]);

    Route::post('bookings/add-extra-services', [
        'as' => 'as.bookings.add-extra-services',
        'uses' => 'App\Appointment\Controllers\Bookings@addExtraServices'
    ]);

    Route::post('bookings/remove-extra-service', [
        'as' => 'as.bookings.remove-extra-service',
        'uses' => 'App\Appointment\Controllers\Bookings@removeExtraService'
    ]);

    Route::get('bookings/get-change-status-form', [
        'as' => 'as.bookings.change-status-form',
        'uses' => 'App\Appointment\Controllers\Bookings@getChangeStatusForm'
    ]);

    Route::post('bookings/change-status', [
        'as' => 'as.bookings.change-status',
        'uses' => 'App\Appointment\Controllers\Bookings@changeStatus'
    ]);

    Route::get('bookings/modify-booking', [
        'as' => 'as.bookings.modify-form',
        'uses' => 'App\Appointment\Controllers\Bookings@getModifyBookingForm'
    ]);

    Route::post('bookings/modify-booking', [
        'as' => 'as.bookings.modify-form',
        'uses' => 'App\Appointment\Controllers\Bookings@doModifyBooking'
    ]);

    Route::get('bookings/search-consumer', [
        'as' => 'as.bookings.search-consumer',
        'uses' => 'App\Appointment\Controllers\Bookings@searchConsumer'
    ]);

    Route::get('bookings/ajax/get-employee-services', [
        'as' => 'as.bookings.employee.services',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@getEmployeeServicesByCategory'
    ]);

    Route::get('bookings/ajax/get-service-times', [
        'as' => 'as.bookings.service.times',
        'uses' => 'App\Appointment\Controllers\Ajax\Bookings@getServiceTimes'
    ]);

    Route::post('bookings/add-booking-service', [
        'as' => 'as.bookings.service.add',
        'uses' => 'App\Appointment\Controllers\Bookings@addBookingService'
    ]);

    Route::post('bookings/add-booking', [
        'as' => 'as.bookings.add',
        'uses' => 'App\Appointment\Controllers\Bookings@upsertBooking'
    ]);

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
    Route::get('options/working-time', [
        'uses' => 'App\Appointment\Controllers\Options@workingTime'
    ]);

    Route::post('options/working-time', [
        'uses' => 'App\Appointment\Controllers\Options@updateWorkingTime'
    ]);

    Route::get('options/{page?}', [
        'as'   => 'as.options',
        'uses' => 'App\Appointment\Controllers\Options@index'
    ]);

    Route::post('options/{page?}', [
        'uses' => 'App\Appointment\Controllers\Options@update'
    ]);

    // Report
    Route::get('reports', [
        'as' => 'as.reports.employees',
        'uses' => 'App\Appointment\Controllers\Reports@employees'
    ]);

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
