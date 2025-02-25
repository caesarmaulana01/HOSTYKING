<?php
function tgl_indo($tanggal) {
    // Pastikan tanggal tidak kosong atau NULL
    if (empty($tanggal)) {
        return 'Tanggal tidak valid';
    }

    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );

    $pecahkan = explode('-', $tanggal);

    // Pastikan array hasil explode memiliki 3 elemen (format YYYY-MM-DD)
    if (count($pecahkan) < 3) {
        return 'Format tanggal salah';
    }

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

?>