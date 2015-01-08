<?php
return [
    'consumers'         => 'Asiakkaat',
    'empty'             => 'Sinulla ei ole vielä aktiivisia asiakkaita.',
    'id'                => 'ID',
    'first_name'        => 'Etunimi',
    'last_name'         => 'Sukunimi',
    'email'             => 'Sähköposti',
    'phone'             => 'Puhelin',
    'city'              => 'Postitoimipaikka',
    'country'           => 'Maa',
    'address'           => 'Osoite',
    'postcode'          => 'Postinumero',
    'receive_email'     => 'Email Kielto',
    'receive_sms'       => 'SMS Kielto',
    'joined'            => 'Lisätty',
    'active_services'   => 'Aktiiviset palvelut',
    'with_selected'     => 'Valittu',
    'delete'            => 'Poista',
    'edit_heading'      => 'Muokkaa asiakkaan #:id',
    'edit_success'      => 'Asiakkaan tiedot on päivitetty',
    'hide_success'      => 'Valittut asiakkaat ovat poistettu',
    'query_exception'   => 'Tallentaminen epäonnistui, onko sähköposti jo käytössä?',
    'exception'         => 'Yritä uudelleen.',
    'invalid_action'    => 'Toiminta epäonnistui',
    'all'               => 'Kaikki',
    'add'               => 'Lisää',
    'edit'              => 'Muokkaa',
    'services'          => 'Palvelut',
    'date'              => 'Päivämäärä',
    'start_at'          => 'Alkaa',
    'end_at'            => 'Loppuu',
    'notes'             => 'Muistiot',
    'action'            => 'Toiminto',
    'give_points'       => 'Anna :points pistetta',

    'import' => [
        'import'        => 'Lataa lista',
        'upload_csv'    => 'Lataa CSV -lista',
        'upload_is_missing' => 'Ladattava tiedosto puuttuu.',
        'upload_is_invalid' => 'Ladattu tiedosto on virheellinen.',
        'save_error_row_x_y'  => 'Row :row has an error: ":error".',
        'imported_x'    => 'Successfully imported one row.|Successfully imported :count rows.',
        'csv_header_is_missing' => 'Header row could not be found.',
        'csv_required_field_x_is_missing' => 'Required field `:field` could not be found.',
    ],
    'x_consumers'       => ':count consumer|:count consumers',
    'group'             => 'Lisää ryhmään',
    'send_email'        => 'Lähetä sähköposti',
    'send_sms'          => 'Lähetä SMS',
    'send_all_email'    => 'Lähetä sähköposti kaikille',
    'send_all_sms'      => 'Lähetä SMS kaikille',
    'groups' => [
        'all'           => 'Ryhmät',
        'add'           => 'Lisää ryhmä',
        'edit'          => 'Muokkaa ryhmää',
        'name'          => 'Ryhmän nimi',
        'consumers'     => 'Ryhmän jäsenet',
        'existing_group'=> 'Olemassaoleva ryhmä',
        'new_group'     => 'Uusi ryhmä',
        'send_email'    => 'Lähetä sähköposti ryhmälle',
        'groups'        => 'Ryhmät',
        'send_sms'      => 'Lähetä SMS ryhmälle',
    ],
    'email_templates' => [
        'all'           => 'Sähköpostikampanjat',
        'single'        => 'Sähköpostikampanja',
        'subject'       => 'Aihe',
        'content'       => 'Sisältö',
        'from_email'    => 'Lähettäjän sähköpostiosoite',
        'from_name'     => 'Lähettäjän nimi',
        'add'           => 'Lisää email kampanja',
        'edit'          => 'Muokkaa sähköpostikampanjaa',
        'sent_at'       => 'Lähetetty',
        'sent_to_x_of_y'=> 'Sähköposteja lähetetty onnistuneesti :sent / :total consumers ',
    ],
    'sms_templates' => [
        'all'           => 'SMS kampanjat',
        'single'        => 'SMS kampanja',
        'title'         => 'Kampanjan nimi',
        'content'       => 'Sisältö',
        'from_name'     => 'Lähettäjän nimi',
        'add'           => 'Uusi SMS kampanja',
        'edit'          => 'Muokkaa SMS kampanjaa',
        'sent_at'       => 'Lähetetty',
        'sent_to_x_of_y'=> 'Tekstiviestejä lähetetty onnistuneesti :sent / :total consumers ',
    ],
    'history' => [
        'index'         => 'Historia',
        'email'         => 'Lähetetyt sähköpostit',
        'sms'           => 'Lähetetyt tekstiviestit',
    ]
];
