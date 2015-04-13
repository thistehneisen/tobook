<?php

$cancelMessage = <<< HTML
You have cancelled the booking {BookingID}

{Services}
HTML;

return [
    'index' => [
        'heading'       => 'Welcome',
        'description'   => 'You can view the calendar of all created employees. Green time is bookable for consumers and grey is not bookable.',
        'today'         => 'Today',
        'tomorrow'      => 'Tomorrow',
        'print'         => 'Print',
        'calendar'      => 'Calendar',
    ],
    'services' => [
        'heading'            => 'Services',
        'edit'               => 'Edit service',
        'custom_time'        => 'Custom time',
        'master_categories'  => 'Master categories',
        'treatment_types'    => 'Treatment types',
        'categories' => [
            'all'           => 'All categories',
            'add'           => 'Add new category',
            'edit'          => 'Edit category',
            'name'          => 'Name',
            'description'   => 'Description',
            'is_show_front' => 'Is shown in frontpage?',
            'no_services'   => 'There are no services for this category',
            'availability'  => 'Availability',
            'category_name' => 'Category name',
            'error'         => [
                'category_current_in_use' => 'Category is currently in use. Please delete all related services before deleting this category.'
            ]
        ],
        'resources' => [
            'all'         => 'All resources',
            'add'         => 'Add new resource',
            'edit'        => 'Edit resource',
            'name'        => 'Name',
            'description' => 'Description',
            'quantity'    => 'Quantity',
        ],
        'rooms' => [
            'all'         => 'All rooms',
            'add'         => 'Add room',
            'edit'        => 'Edit room',
            'name'        => 'Name',
            'description' => 'Description',
        ],
        'extras' => [
            'all'         => 'All extra services',
            'add'         => 'Add new extra service',
            'edit'        => 'Edit extra service',
            'name'        => 'Name',
            'description' => 'Description',
            'price'       => 'Price',
            'length'      => 'Length',
            'msg_extra'   => 'Do you also want to reserve?',
        ],
        'all'          => 'All services',
        'index'        => 'Services',
        'desc'         => 'Here you can edit or add new services',
        'add'          => 'Add new service',
        'add_desc'     => 'Add a new employee by adding service name and description, you can also connect services to employees',
        'name'         => 'Name',
        'description'  => 'Description',
        'price'        => 'Price',
        'duration'     => 'Duration',
        'length'       => 'Total',
        'during'       => 'Duration',
        'before'       => 'Before',
        'after'        => 'After',
        'total'        => 'Total',
        'category'     => 'Category',
        'is_active'    => 'Active',
        'resource'     => 'Resource',
        'room'         => 'Room',
        'extra'        => 'Extra Service',
        'employees'    => 'Employees',
        'no_employees' => 'There is no employee to be selected',
        'no_name'      => 'Untitled',
        'error'        => [
            'service_current_in_use' => 'Services is currently in use. Please delete all related bookings before deleting this service.'
        ]
    ],
    'bookings' => [
        'confirmed'         => 'Confirmed',
        'pending'           => 'Pending',
        'cancelled'         => 'Cancelled',
        'arrived'           => 'Arrived',
        'paid'              => 'Paid',
        'not_show_up'       => 'Customer does not show up',
        'change_status'     => 'Change booking status',
        'all'               => 'Bookings',
        'add'               => 'Add new booking',
        'invoices'          => 'Invoices',
        'consumer'          => 'Customer',
        'date'              => 'Date',
        'total'             => 'Total',
        'start_at'          => 'Start at',
        'end_at'            => 'End at',
        'status'            => 'Status',
        'total_price'       => 'Price',
        'uuid'              => 'UUID',
        'ip'                => 'IP',
        'add_service'       => 'Add service',
        'booking_info'      => 'Booking Info',
        'booking_id'        => 'Booking ID',
        'categories'        => 'Categories',
        'services'          => 'Services',
        'service_time'      => 'Service time',
        'modify_time'       => 'Modify time',
        'plustime'          => 'Plustime',
        'total_length'      => 'Total length',
        'modify_duration'   => 'Modify duration',
        'employee'          => 'Employee',
        'notes'             => 'Notes',
        'first_name'        => 'First name',
        'last_name'         => 'Last name',
        'email'             => 'Email',
        'phone'             => 'Phone',
        'address'           => 'Address',
        'city'              => 'City',
        'postcode'          => 'Postcode',
        'country'           => 'Country',
        'confirm_booking'   => 'Confirm booking',
        'service_employee'  => 'Employee',
        'date_time'         => 'Date',
        'price'             => 'Price',
        'extra_service'     => 'Extra service',
        'keyword'           => 'Keyword',
        'edit'              => 'Edit bookings',
        'terms'             => 'Terms',
        'terms_agree'       => 'I\'ve read and agreed to the booking terms',
        'cancel_message'    => $cancelMessage,
        'cancel_confirm'    => 'Are you sure to cancel this booking %s?',
        'modify_booking'    => 'Modify booking',
        'reschedule'        => 'Reschedule',
        'confirm_reschedule'=> 'Confirm reschedule',
        'cancel_reschedule' => 'Cancel reschedule',
        'own_customer'      => 'Own customer',
        'request_employee'  => 'Requesting for a specific employee',
        'error'             => [
            'add_overlapped_booking'      => 'Overlapped booking time!',
            'insufficient_slots'          => 'There is no enough time slots for this booking!',
            'invalid_consumer_info'       => 'Could not save consumer info',
            'terms'                       => 'You have to agree with our term.',
            'service_empty'               => 'Please select service and service time!',
            'unknown'                     => 'Something went wrong!',
            'exceed_current_day'          => 'Booking end time cannot exceed current day',
            'overllapped_with_freetime'   => 'Booking is overllapped with employee freetime',
            'empty_total_time'            => 'Booking total minutes must be greater or equal 1',
            'uuid_notfound'               => 'Booking ID not found',
            'not_enough_slots'            => 'Not enough booking slots or overllaped with other booking.',
            'employee_not_servable'       => 'This employee does not serve the booking service.',
            'id_not_found'                => 'Booking not found',
            'start_time'                  => 'Booking start time is invalid',
            'service_time_invalid'        => 'Service time for booking not found',
            'overlapped_with_freetime'    => 'Booking is overlapped with employee freetime',
            'reschedule_single_only'      => 'Booking with multiple services cannot be rescheduled',
            'reschedule_unbooked_extra'   => 'Booking cannot be rescheduled',
            'not_enough_resources'        => 'Required resources are not available!',
            'not_enough_rooms'            => 'There are not enough room!',
            'empty_start_time'            => 'Booking start time cannot be empty',
            'booking_not_found'           => 'Booking not found!',
            'past_booking'                => 'Cannot make booking in the past!',
            'delete_last_booking_service' => 'You cannot delete the last booking service',
            'before_min_distance'         => 'You cannot make a booking before the min distance day',
            'after_max_distance'          => 'You cannot make a booking after the max distance day',
        ],
        'warning'      => [
            'existing_user'   => 'There is an user associate with this email in our system. Do you want to use these information instead?',
        ],
    ],
    'employees' => [
        'all'                              => 'Employees',
        'add'                              => 'Add new employee',
        'edit'                             => 'Edit employee',
        'name'                             => 'Name',
        'phone'                            => 'Phone number',
        'email'                            => 'Email',
        'description'                      => 'Description',
        'is_subscribed_email'              => 'Is subscribed email?',
        'is_subscribed_sms'                => 'Is subscribed SMS?',
        'is_received_calendar_invitation'  => 'Is received calendar invitation?',
        'services'                         => 'Services',
        'status'                           => 'Status',
        'is_active'                        => 'Status',
        'avatar'                           => 'Avatar',
        'default_time'                     => 'Default time',
        'custom_time'                      => 'Custom time',
        'days_of_week'                     => 'Days of week',
        'start_time'                       => 'Start time',
        'end_time'                         => 'End time',
        'day_off'                          => 'Is day off?',
        'confirm'                          => [
            'delete_freetime' => 'Are you sure to delete selected free time from the calendar?'
        ],
        'free_time'                        => 'Free time',
        'free_times'                       => 'Free times',
        'working_times'                    => 'Working times',
        'add_free_time'                    => 'Add free time',
        'start_at'                         => 'Start at',
        'end_at'                           => 'End at',
        'date'                             => 'Date',
        'is_day_off'                       => 'Day off',
        'workshifts'                       => 'Workshifts',
        'workshift_planning'               => 'Workshift planning',
        'workshift_summary'                => 'Workshift summary',
        'from_date'                        => 'From date',
        'to_date'                          => 'To date',
        'weekday'                          => 'Weekday',
        'employee'                         => 'Employee',
        'saturday_hours'                   => 'Saturdays hours',
        'sunday_hours'                     => 'Sunday hours',
        'monthly_hours'                    => 'Monthly hours',
        'error'                            => [
        'freetime_overlapped_with_booking' => 'Freetime is overlapped with with booking(s)',
        ],
    ],
    'embed' => [
        'heading'          => 'Title',
        'description'      => 'Body',
        'embed'            => 'Embed',
        'preview'          => 'Preview',
        'back_to_services' => 'Back to services',
        'select_date'      => 'Select date',
        'select_service'   => 'Select services',
        'guide_text'       => 'Click on available time',
        'make_appointment' => 'Make appointment',
        'cancel'           => 'Cancel',
        'back'             => 'Back',
        'book'             => 'Book',
        'empty_cart'       => 'Your cart is empty',
        'start_time'       => 'Start time',
        'end_time'         => 'End time',
        'booking_form'     => 'Booking form',
        'name'             => 'Name',
        'email'            => 'Email',
        'phone'            => 'Phone number',
        'checkout'         => 'Checkout',
        'fi_version'       => 'Finnish',
        'en_version'       => 'English',
        'sv_version'       => 'Swedish',
        'book'             => 'Book',
        'loading'          => 'Now loading&hellip;',
        'success'          => 'Booking was placed. Thank you.',
        'confirm'          => 'Confirm booking',
        'layout_2'         => [
            'select_service'      => 'Choose service and date',
            'select_service_type' => 'Choose service category',
            'services'            => 'Services',
            'selected'            => 'Selected services',
            'extra_services'      => 'Extra services',
            'employees'           => 'Employee',
            'choose'              => 'Choose date',
            'unavailable'         => 'No times available',
            'form'                => 'Contact information',
            'date'                => 'Date',
            'price'               => 'Price',
            'name'                => 'Name',
            'phone'               => 'Phone',
            'email'               => 'Email',
            'thanks'              => 'Thank you for your booking!',
        ],
        'layout_3'         => [
            'select_service'  => 'Select service',
            'select_employee' => 'Select employee',
            'select_datetime' => 'Select date &amp; time',
            'contact'         => 'Contact information',
            'service'         => 'Service',
            'employee'        => 'Employee',
            'name'            => 'Your name',
            'notes'           => 'Notes',
            'postcode'        => 'Postcode',
            'empty'           => 'There is no available time on selected day.',
            'payment_note'    => 'After a booking is placed, you will be redirected to payment.',
            'confirm_service' => 'Confirm booking service',
        ]
    ],
     'options' => [
        'heading' => 'Options',
        'updated' => 'Options updated',
        'general' => [
            'index'           => 'General',
            'heading'         => 'General options',
            'info'            => 'Apply your settings',
            'currency'        => 'Currency',
            'custom_status'   => 'Custom Status',
            'datetime_format' => 'Datetime format',
            'date_format'     => 'Date format',
            'time_format'     => 'Time format',
            'layout'          => 'Layout',
            'seo_url'         => 'SEO URLs',
            'timezone'        => 'Timezone',
            'week_numbers'    => 'Näytä viikkonumerot',
            'week_start'      => 'Show week numbers?',
            'phone_number'    => 'SMS phone number',
            'business_name'   => 'Business name',
        ],
        'booking'   => [
            'heading'                                        => '',
            'info'                                           => '',
            'disable_booking'                                => 'Disable booking widget', // @todo
            'index'                                          => 'Bookings',
            'booking_form'                                   => 'Booking Form',
            'reminders'                                      => 'Reminder',
            'confirmations'                                  => 'Confirmation',
            'terms'                                          => 'Terms',
            'confirmed'                                      => 'Confirmed',
            'pending'                                        => 'Pending',
            'accept_bookings'                                => 'Accept bookings',
            'hide_prices'                                    => 'Hide prices',
            'step'                                           => 'Step',
            'bookable_date'                                  => 'Bookable date',
            'status_if_paid'                                 => 'Default mode for paid bookings',
            'status_if_not_paid'                             => 'Default mode for unpaid bookings',
            'notes'                                          => 'Notes',
            'address'                                        => 'Address',
            'city'                                           => 'City',
            'postcode'                                       => 'Postcode',
            'country'                                        => 'Country',
            'email'                                          => 'Email',
            'reminder_enable'                                => 'Enable notifications',
            'reminder_email_before'                          => 'Send email reminder',
            'reminder_subject'                               => 'Email Reminder subject',
            'reminder_subject_default'                       => 'Muistutus varauksestasi',
            'reminder_body'                                  => 'Email Reminder body',
            'reminder_sms_hours'                             => 'Send SMS reminder',
            'reminder_sms_country_code'                      => 'SMS country code',
            'reminder_sms_message'                           => 'SMS message',
            'terms_url'                                      => 'Booking terms URL',
            'terms_body'                                     => 'Booking terms content',
            'confirm_subject_client'                         => 'Client confirmation title',
            'confirm_tokens_client'                          => 'Email body',
            'confirm_email_enable'                           => 'Enable email',
            'confirm_sms_enable'                             => 'Enable sms',
            'confirm_sms_country_code'                       => 'Code',
            'confirm_consumer_sms_message'                   => 'Consumer sms',
            'confirm_employee_sms_message'                   => 'Employee sms',
            'confirm_subject_employee'                       => 'Employee confirmation title',
            'confirm_tokens_employee'                        => 'Email body',
            'terms_enabled'                                  => 'Enable terms',
            'default_nat_service'                            => 'Default next available service',
            'min_distance'                                   => 'Min distance',
            'max_distance'                                   => 'Max distance',
            'auto_select_employee'                           => 'Auto select an employee',
            'auto_expand_all_categories'                     => 'Auto expand all categories',
            'show_employee_request'                          => 'Show option requesting for an employee',
            'factor'                                         => 'Factor'
        ],
        'style' => [
            'heading'                           => '',
            'info'                              => '',
            'index'                             => 'Front-end style',
            'style_logo'                        => 'Logo URL',
            'style_banner'                      => 'Banner',
            'style_heading_color'               => 'Heading color',
            'style_text_color'                  => 'Text color',
            'style_background'                  => 'Background',
            'style_custom_css'                  => 'Custom CSS',
            'style_main_color'                  => 'Main color',
            'style_heading_background'          => 'Heading background',
        ],
        'working_time' => [
            'index' => 'Calendar view',
        ]
    ],
    'reports' => [
        'index'     => 'Reports',
        'employees' => 'Employees',
        'services'  => 'Services',
        'generate'  => 'Generate',
        'start'     => 'Start date',
        'end'       => 'End date',
        'booking'   => [
            'total'       => 'Total bookings',
            'confirmed'   => 'Confirmed bookings',
            'unconfirmed' => 'Unconfirmed bookings',
            'cancelled'   => 'Cancelled bookings',
        ],
        'statistics'=> 'Statistics',
        'monthly'   => 'Monthly report',
        'stat' => [
            'monthly'      => 'Monthly review',
            'bookings'     => 'Bookings',
            'revenue'      => 'Revenue',
            'working_time' => 'Working time',
            'booked_time'  => 'Booked time',
            'occupation'   => 'Occupation %'
        ]
    ],
    'crud' => [
        'bulk_confirm'   => 'Are you sure to carry out this action?',
        'success_add'    => 'Item was created successfully.',
        'success_edit'   => 'Data was updated successfully.',
        'success_delete' => 'Item was deleted successfully.',
        'success_bulk'   => 'Item was deleted successfully.',
        'sortable'       => 'Drag to reorder',
    ],
];
