@php
    $textCenter = 'vertical-align:middle;text-align:center;';
@endphp
<table>
    <thead>
        <tr>
            <th rowspan="2" style="width:300%;{{$textCenter}}">Nama Pegawai</th>
            <th rowspan="2" style="width:200%;{{$textCenter}}">NIK</th>
            <th rowspan="2" style="width:250%;{{$textCenter}}">Jenis Pegawai</th>
            <th colspan="3" style="height:400%;{{$textCenter}}"> {{ $month }} - {{ $year}}</th>
        </tr>
        <tr>
            <th style="height:300%;{{$textCenter}}">Hadir</th>
            <th style="height:300%;{{$textCenter}}">Izin</th>
            <th style="height:300%;{{$textCenter}}">Absen</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($module as $key => $value)
        <tr>
            <td>{{ $module[$key]['nama'] }}</td>
            <td style="text-align:left;">{{ $module[$key]['nik'] }}</td>
            <td style="text-align:left;">{{ $module[$key]['jenis_pegawai'] }}</td>
            <td style="text-align:center;">{{ $module[$key]['hadir'] }}</td>
            <td style="text-align:center;">{{ $module[$key]['izin'] }}</td>
            <td style="text-align:center;">{{ $module[$key]['absen'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
