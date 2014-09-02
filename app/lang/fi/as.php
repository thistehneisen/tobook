<?php
return [
    'services' => [
        'categories' => [
            'all'           => 'Kaikki kategoriat',
            'add'           => 'Lisää kategoria',
            'edit'          => 'Edit category',
            'name'          => 'Nimi',
            'description'   => 'Kuvaus',
            'is_show_front' => 'Varattavissa kuluttajille',
        ],
        'resources' => [
            'all'         => 'Kaikki resurssit',
            'add'         => 'Lisää resurssi',
            'edit'        => 'Edit resource',
            'name'        => 'Nimi',
            'description' => 'Kuvaus',
            'quantity'    => 'Quantity',
        ],
        'extras' => [
            'all'         => 'Kaikki lisäpalvelut',
            'add'         => 'Lisää lisäpalvelu',
            'edit'        => 'Edit extra service',
            'name'        => 'Nimi',
            'description' => 'Kuvaus',
            'price'       => 'Hinta',
            'length'      => 'Length',
        ],
        'all'          => 'Kaikki palvelut',
        'add'          => 'Lisää palvelu',
        'add_desc'     => 'Lisää uusi palvelu lisäämällä palvelun nimi, palvelun kesto ja työntekijät',
        'name'         => 'Nimi',
        'description'  => 'Kuvaus',
        'price'        => 'Hinta',
        'duration'     => 'Kesto',
        'before'       => 'Ennen',
        'after'        => 'Jälkeen',
        'total'        => 'Yhteensä',
        'category'     => 'Kategoria',
        'is_active'    => 'Tila',
        'resource'     => 'Resurssit',
        'extra'        => 'Lisäpalvelut',
        'employees'    => 'Työntekijät',
        'no_employees' => 'There is no employee to be selected',
    ],
    'bookings' => [
        'confirmed'  => 'Tila: vahvistettu',
        'pending'    => 'Tila: auki',
        'cancelled'  => 'Tila: peruutettu',
        'all'        => 'Varaukset',
        'add'        => 'Tee varaus',
        'invoices'   => 'Laskut',
        'customers'  => 'Asiakkaat',
        'statistics' => 'Statistiikka',
        'date'       => 'date', // @todo
        'total'      => 'total', // @todo
        'start_at'   => 'start_at', // @todo
        'status'     => 'status', // @todo
        'ip'         => 'ip', // @todo
    ],
    'employees' => [
        'all'                 => 'Työntekijät',
        'add'                 => 'Lisää työntekijä',
        'edit'                => 'Edit employee',
        'name'                => 'Nimi',
        'phone'               => 'Puhelinnumero',
        'email'               => 'Sähköpostiosoite',
        'description'         => 'Kuvaus',
        'is_subscribed_email' => 'Lähetä sähköposti',
        'is_subscribed_sms'   => 'Lähetä tekstiviesti',
        'services'            => 'Palvelut',
        'status'              => 'Tila',
        'avatar'              => 'Kuva',
        'default_time'        => 'default_time', // @todo
        'custom_time'         => 'custom_time', // @todo
        'days_of_week'        => 'Viikonpäivä',
        'start_time'          => 'Aloitusaika',
        'end_time'            => 'Lopetusaika',
        'day_off'             => 'Vapaapäivä'
    ],
    'items_per_page' => 'Yksiköitä yhteensä',
    'with_selected'  => 'Valitse toiminto',
    'crud' => [
        'bulk_confirm' => 'Are you sure to carry out this action?',
        'success_add' => 'Item was created successfully.',
        'success_edit' => 'Data was updated successfully.',
        'success_delete' => 'Item was deleted successfully.',
        'success_bulk' => 'Item was deleted successfully.',
    ]
];
