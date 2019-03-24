<?php

return [
    new DocumentNamespace(
        'cf_1155',
        '1. Nationality',
        '.',
        'Contacts'
    ),
    new DocumentNamespace(
        'lastname',
        '2. Surname',
        '.',
        'Contacts'
    ),
    new DocumentNamespace(
        'firstname',
        '3. First name',
        '.',
        'Contacts'
    ),
    new DocumentNamespace(
        'birthday',
        '4. Date of birth',
        '.',
        'Contacts',
        'date'
    ),
    new DocumentNamespace(
        'cf_1135',
        '5. Sex',
        '.',
        'Contacts'
    ),
    new DocumentNamespace(
        'cf_1137',
        'Passport No',
        ':',
        'Visa'
    ),
    new DocumentNamespace(
        'cf_1139',
        'Date of issue',
        ':',
        'Visa',
        'date'
    ),
    new DocumentNamespace(
        'cf_1141',
        'Valid until',
        ':',
        'Visa',
        'date'
    ),
    new DocumentNamespace(
        'cf_1143',
        '10. Date of arrival',
        '.',
        'Visa',
        'date',
        array('cf_1145'),
        '-'
    ),
    new DocumentNamespace(
        'mailingstreet',
        '14. Your permanent address',
        '.',
        'Contacts',
        'address',
        array('mailingzip', 'mailingcity'),
        ','
    ),
];