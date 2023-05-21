@php
    $textCenter = 'vertical-align:middle;text-align:center;';
@endphp
<table border=1>
    <tr>
        <td style="font-weight: bold;{{ $textCenter }}">Status</td>
        <td style="font-weight: bold;{{ $textCenter }}">Keterangan</td>
    </tr>
    <tr>
        <td style="{{ $textCenter }}">A</td>
        <td style="{{ $textCenter }}">Absen</td>
    </tr>
    <tr>
        <td style="{{ $textCenter }}">I</td>
        <td style="{{ $textCenter }}">Izin</td>
    </tr>
    <tr>
        <td style="{{ $textCenter }}">H</td>
        <td style="{{ $textCenter }}">Hadir</td>
    </tr>
    <tr>
        <td style="{{ $textCenter }}">L</td>
        <td style="{{ $textCenter }}">Libur</td>
    </tr>
    <tr>
        <td style="{{ $textCenter }}">K</td>
        <td style="{{ $textCenter }}">Kosong</td>
    </tr>
</table>
<table border=1>
    <thead>
        <tr>
            <th rowspan="2" style="width:300%;{{$textCenter}}">Nama Pegawai</th>
            <th rowspan="2" style="width:200%;{{$textCenter}}">NIK</th>
            <th rowspan="2" style="width:250%;{{$textCenter}}">Jenis Pegawai</th>
            <th colspan="{{$date}}" style="height:400%;{{$textCenter}}"> {{ $month }} - {{ $year}}</th>
        </tr>
        <tr>
            @for($i = 1; $i <= $date; $i++) 
                <th style="height:300%;{{$textCenter}}">{{$i}}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($module as $key => $value)
        <tr>
            <td>{{ $module[$key]['nama'] }}</td>
            <td style="text-align:left;">{{ (int)$module[$key]['nik'] }}</td>
            <td>{{ $module[$key]['jenis_pegawai'] }}</td>
            @foreach ($module[$key]['kehadiran'] as $item) 
                @if($item['status'] == "Absen")
                    <td style="{{$textCenter}}background-color:#d63031;">A</td>
                @elseif($item['status'] == "Hadir")
                    <td style="{{$textCenter}}background-color:#55efc4;">H</td>
                @elseif($item['status'] == "Izin")
                    <td style="{{$textCenter}}background-color:#ffeaa7;">I</td>
                @elseif($item['status'] == "Libur")
                    <td style="{{$textCenter}}background-color:#74b9ff;">L</td>
                @elseif($item['status'] == "Kosong")
                    <td style="{{$textCenter}}background-color:#b2bec3;">K</td>
                @endif
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
