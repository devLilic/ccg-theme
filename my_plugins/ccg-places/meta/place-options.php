<?php
function ccg_get_place_duration_options() {
    return [
        '30min'     => '30 minute',
        '1h'        => '1 oră',
        '2h'        => '2 ore',
        '3h'        => '3 ore',
        'half_day'  => 'Jumătate de zi',
        'full_day'  => 'O zi întreagă',
    ];
}

function ccg_get_place_access_options() {
    return [
        'car'   => 'Automobil',
        'bus'   => 'Autobuz',
        'train' => 'Tren',
        'boat'  => 'Barcă',
        'bike'  => 'Bicicletă',
        'walk'  => 'Pe jos',
    ];
}

function ccg_get_place_price_options() {
    return [
        'free'   => 'Gratuit',
        'paid'    => 'Cost redus',
        'moderate' => 'Cost mediu',
        'premium'   => 'Cost ridicat',
    ];
}
