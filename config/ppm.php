<?php

return [
    // fallback when est_resource_time is empty/0
    'default_pnl_minutes' => 30,

    'shifts' => [
        'shift1' => ['label' => 'Shift 1', 'icon' => '🌅', 'start' => '06:00', 'end' => '14:00'],
        'shift2' => ['label' => 'Shift 2', 'icon' => '☀️', 'start' => '14:00', 'end' => '22:00'],
        'shift3' => ['label' => 'Shift 3', 'icon' => '🌙', 'start' => '22:00', 'end' => '06:00'],
    ],
];