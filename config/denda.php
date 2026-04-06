<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tarif Denda Keterlambatan
    |--------------------------------------------------------------------------
    |
    | Tarif denda per hari untuk keterlambatan pengembalian alat.
    | Nilai dalam Rupiah per hari.
    |
    */
    'tarif_keterlambatan_per_hari' => env('DENDA_KETERLAMBATAN_PER_HARI', 10000),

    /*
    |--------------------------------------------------------------------------
    | Tarif Denda Kerusakan
    |--------------------------------------------------------------------------
    |
    | Tarif denda untuk kerusakan alat (bisa berbeda tergantung jenis kerusakan).
    | Nilai default dalam Rupiah.
    |
    */
    'tarif_kerusakan_default' => env('DENDA_KERUSAKAN_DEFAULT', 50000),

    /*
    |--------------------------------------------------------------------------
    | Tarif Denda Kehilangan
    |--------------------------------------------------------------------------
    |
    | Tarif denda untuk alat yang hilang/rusak berat (tidak bisa diperbaiki).
    | Nilai dalam Rupiah.
    |
    */
    'tarif_kehilangan' => env('DENDA_KEHILANGAN', 500000),
];
