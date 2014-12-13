<?php
/**
 * Language keys for module Consumers
 *
 * @author An Cao <an@varaa.com>
 */
return [
    'consumers'         => 'Consumers',
    'empty'             => 'You do not have any consumers yet. Consumers are created in your active services.',
    'id'                => 'ID',
    'first_name'        => 'First Name',
    'last_name'         => 'Last Name',
    'email'             => 'Email',
    'phone'             => 'Phone',
    'city'              => 'City',
    'country'           => 'Country',
    'address'           => 'Address',
    'postcode'          => 'Post Code',
    'joined'            => 'Joined',
    'active_services'   => 'Active Services',
    'with_selected'     => 'With selected',
    'delete'            => 'Delete',
    'edit_heading'      => 'Edit consumer #:id',
    'updated'           => 'Consumer information has been updated',
    'query_exception'   => 'Failed to save data. Is the email unique?',
    'exception'         => 'Unexpected error. Please try again later.',
    'invalid_action'    => 'Invalid action',
    'all'               => 'Consumers',
    'add'               => 'Add Consumer',
    'edit'              => 'Edit',
    'services'          => 'Services',
    'date'              => 'Date',
    'start_at'          => 'Start',
    'end_at'            => 'End',
    'notes'             => 'Notes',
    'action'            => 'Action',
    'give_points'       => 'Give :points points',
    'import' => [
        'import'        => 'Import',
        'upload_csv'    => 'Upload CSV',
        'upload_is_missing' => 'File upload is required.',
        'upload_is_invalid' => 'Uploaded file is invalid.',
        'save_error_row_x_y'  => 'Row :row has an error: ":error".',
        'imported_x'    => 'Successfully imported one row.|Successfully imported :count rows.',
        'csv_header_is_missing' => 'Header row could not be found.',
        'csv_required_field_x_is_missing' => 'Required field `:field` could not be found.',
    ],
    'x_consumers'       => ':count consumer|:count consumers',
    'group'             => 'Add To Group',
    'groups' => [
        'all'           => 'Groups',
        'add'           => 'Add Group',
        'edit'          => 'Edit Group',
        'name'          => 'Group Name',
        'consumers'     => 'Group Members',
        'existing_group'=> 'Existing Group',
        'new_group'     => 'New Group',
        'send_campaign' => 'Send Group Campaign',
        'groups'        => 'Groups',
        'send_sms'      => 'Send Group SMS',
    ],
    'send_campaign'     => 'Send Campaign',
    'email_templates' => [
        'all'           => 'Email templates',
        'subject'       => 'Subject',
        'content'       => 'Content',
        'from_email'    => 'From email',
        'from_name'     => 'From name',
        'add'           => 'Add email template',
        'edit'          => 'Edit email template',

        'sent_at'       => 'Sent at',
        'sent_to_x_of_y'=> 'Sent email to :sent out of :total consumers',
    ],
    'send_sms'     => 'Send SMS',
    'sms_templates' => [
        'all'           => 'SMS templates',
        'title'         => 'Title',
        'content'       => 'Content',
        'add'           => 'Add SMS template',
        'edit'          => 'Edit SMS template',
        'sent_at'       => 'Sent at',
        'sent_to_x_of_y'=> 'Sent SMS to :sent out of :total consumers',
    ],
    'history' => [
        'index'         => 'History',
        'email'         => 'Email history',
        'sms'           => 'SMS history',
    ]
];
