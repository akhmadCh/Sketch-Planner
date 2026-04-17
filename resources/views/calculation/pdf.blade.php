<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Meyerhof</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .footer { font-size: 10px; text-align: justify; margin-top: 40px; color: #666; font-style: italic; }
        .status-layak { color: green; font-weight: bold; }
        .status-bahaya { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TECHNICAL REPORT - FOUNDATION BEARING CAPACITY</h2>
        <p>Dynamic Calculation Terminal for Lahan Gambut</p>
    </div>

    <h3>1. Parameter Pondasi</h3>
    <table>
        <tr><th>Titik / CPT Label</th><td>{{ $calc->point_label }}</td></tr>
        <tr><th>Total Ultimate Capacity (Qu)</th><td>{{ $calc->total_capacity_qu }} kN</td></tr>
        <tr><th>Allowable Capacity (Qa)</th><td>{{ $calc->allowable_load_qa }} kN</td></tr>
        <tr><th>Safety Factor Applied</th><td>{{ $calc->safety_factor }}</td></tr>
    </table>

    <h3>2. Parameter SAP2000 & Efisiensi Grup Tiang</h3>
    <table>
        <tr><th>Beban Vertikal Ultimate SAP2000</th><td>{{ $calc->sap2000_vertical_load ? $calc->sap2000_vertical_load . ' kN' : 'N/A' }}</td></tr>
        <tr><th>Konfigurasi Tiang Pancang</th><td>{{ $calc->pile_group_n }} x {{ $calc->pile_group_m }}</td></tr>
    </table>

    <h3>3. Parameter Seismik (GPS)</h3>
    <table>
        <tr><th>Latitude, Longitude</th><td>{{ $calc->latitude ?? 'N/A' }}, {{ $calc->longitude ?? 'N/A' }}</td></tr>
    </table>

    <h3>4. Kesimpulan Status</h3>
    <h3 class="{{ $calc->status === 'SAFE' ? 'status-layak' : 'status-bahaya' }}">STATUS: {{ $calc->status }}</h3>
    @if($calc->peat_warning)
        <p style="color: red; font-weight: bold;">PERINGATAN DINI: Tanah gambut terdeteksi (Rf > 5%) di profil CPT!</p>
    @endif

    <div class="footer">
        * Laporan ini dihasilkan secara otomatis dan telah disesuaikan dengan validasi LRFD sederhana. 
        Sesuai dengan pedoman rekayasa nasional, hasil evaluasi mematuhi parameter persyaratan dari 
        <strong>SNI 1726:2019</strong> (Tata Cara Perencanaan Ketahanan Gempa untuk Struktur Bangunan Gedung dan Non Gedung) 
        dan <strong>SNI 1727:2020</strong> (Beban Desain Minimum dan Kriteria Terkait untuk Bangunan Gedung dan Struktur Lain).
    </div>
</body>
</html>
