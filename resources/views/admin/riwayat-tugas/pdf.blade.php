<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Tugas</title>

 <style>
    @page {
        margin: 20px;
    }

    body {
        font-family: DejaVu Sans;
        font-size: 10px;
        color: #333;
        line-height: 1.4;
    }

    *{
        box-sizing:border-box;
    }

    .header{
        width:100%;
        border-bottom:4px solid #C11A1A;
        padding-bottom:10px;
        margin-bottom:15px;
    }

    .header h2{
        margin:0;
        color:#C11A1A;
        font-size:22px;
        font-weight:bold;
        text-align:center;
    }

    .header h3{
        margin:4px 0;
        text-align:center;
        font-size:16px;
        color:#222;
    }

    .header p{
        margin:0;
        text-align:center;
        color:#666;
        font-size:10px;
    }

    .info{
        width:100%;
        margin-bottom:15px;
    }

    .info td{
        border:none;
        padding:4px;
        font-size:10px;
    }

    .periode{
        background:#F8F8F8;
        border-left:5px solid #C11A1A;
        padding:8px;
        margin-bottom:15px;
        font-size:11px;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    thead th{
        background:#C11A1A;
        color:white;
        border:1px solid #999;
        padding:8px 5px;
        text-align:center;
        font-size:10px;
    }

    tbody td{
        border:1px solid #CCC;
        padding:6px 5px;
        font-size:9px;
        vertical-align:middle;
    }

    tbody tr:nth-child(even){
        background:#F8F8F8;
    }

    .text-center{
        text-align:center;
    }

    .status-release{
        color:#C11A1A;
        font-weight:bold;
    }

    .status-progress{
        color:#E67E22;
        font-weight:bold;
    }

    .status-validasi{
        color:#1F4E79;
        font-weight:bold;
    }

    .status-selesai{
        color:#1E8449;
        font-weight:bold;
    }

    .footer{
        margin-top:25px;
        width:100%;
    }

    .footer table{
        border:none;
    }

    .footer td{
        border:none;
        font-size:10px;
    }

    .ttd{
        width:220px;
        text-align:center;
    }

    .garis{
        margin-top:55px;
        border-top:1px solid #000;
    }

    .print{
        margin-top:20px;
        text-align:left;
        color:#666;
        font-size:9px;
    }
</style>
</head>

<body>

<table width="100%" style="border:none; margin-bottom:15px;">
    <tr>

        <td width="18%" style="border:none; text-align:center;">
            <img src="{{ public_path('images/logo2.png') }}" width="70">
        </td>

        <td width="82%" style="border:none; text-align:center;">

            <h2 style="margin:0; color:#C11A1A; font-size:22px;">
                POLITEKNIK INDUSTRI PETROKIMIA BANTEN
            </h2>

            <h3 style="margin:5px 0;">
                LAPORAN RIWAYAT TUGAS
            </h3>

            <p style="margin:0; color:#666;">
                Computerized Maintenance Management System (CMMS)
            </p>

        </td>

    </tr>
</table>

<hr style="border:2px solid #C11A1A;">
    <hr>

    <div class="periode">
    <strong>Periode :</strong>

    @if($startDate && $endDate)
        {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
        s/d
        {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
    @else
        Semua Data
    @endif
</div>

    <table>

        <thead>

            <tr>
                <th>No</th>
                <th>Pemberi</th>
                <th>Jenis</th>
                <th>Tanggal</th>
                <th>Mekanik</th>
                <th>Equipment</th>
                <th>Tag</th>
                <th>EQ Class</th>
                <th>BoM</th>
                <th>Task</th>
                <th>Lokasi</th>
                <th>Status</th>
            </tr>

        </thead>

        <tbody>

            @forelse($riwayat as $i => $t)

                @php
                    $status = strtolower($t['status']);
                    $validasi = $t['validasi_mp'] ?? 0;

                    if($status == 'pending'){
                        $statusTampil = 'Release Order';
                    }elseif($status == 'dikerjakan'){
                        $statusTampil = 'Dikerjakan';
                    }elseif($status == 'selesai' && !$validasi){
                        $statusTampil = 'Menunggu Validasi MP';
                    }else{
                        $statusTampil = 'Selesai';
                    }
                @endphp

                <tr>

                    <td>{{ $i+1 }}</td>

                    <td>{{ $t['pemberi_tugas'] }}</td>

                    <td>{{ $t['jenis'] }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($t['tgl_mulai'])->format('d-m-Y') }}
                    </td>

                    <td>{{ $t['nama_mekanik'] }}</td>

                    <td>{{ $t['equipment'] }}</td>

                    <td>{{ $t['tag_number'] }}</td>

                    <td>{{ $t['eq_class'] }}</td>

                    <td>{{ $t['bom'] }}</td>

                    <td>{{ $t['task_list'] }}</td>

                    <td>{{ $t['lokasi'] }}</td>

                 <td class="text-center">

@if($statusTampil=='Release Order')
    <span class="status-release">{{ $statusTampil }}</span>

@elseif($statusTampil=='Dikerjakan')
    <span class="status-progress">{{ $statusTampil }}</span>

@elseif($statusTampil=='Menunggu Validasi MP')
    <span class="status-validasi">{{ $statusTampil }}</span>

@else
    <span class="status-selesai">{{ $statusTampil }}</span>
@endif

</td>

                </tr>

            @empty

                <tr>
                    <td colspan="12">
                        Tidak ada data
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

  <div class="footer">

<table width="100%">

<tr>

<td width="60%">
    <div class="print">
        Dicetak pada :
        {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</td>

<td class="ttd">

Lumajang,
{{ \Carbon\Carbon::now()->format('d-m-Y') }}

<br><br><br><br>

<div class="garis"></div>

Maintenance Planning

</td>

</tr>

</table>

</div>

</body>

</html>
