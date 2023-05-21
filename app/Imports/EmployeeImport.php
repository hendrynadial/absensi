<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class EmployeeImport implements ToModel,WithProgressBar,WithHeadingRow
{
    use Importable;
    public function model(array $row)
    {
        $tanggalLahir = gettype($row['tanggal_lahir']) == "integer" ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d') : $row['tanggal_lahir'];
        $tanggalBergabung = gettype($row['tanggal_bergabung']) == "integer" ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_bergabung'])->format('Y-m-d') : $row['tanggal_bergabung'];
        $nip = str_replace(" ","",$row['nip']);
        $nik = str_replace(" ","",$row['nik']);

        $modul = Employee::where('nik',$nik)->first();
        if($modul != null){
            $unit = json_decode($modul->unit,TRUE);
            array_push($unit,$row['unit']);
            $modul->unit = json_encode($unit);
            
            $jabatan = json_decode($modul->jabatan,TRUE);
            array_push($jabatan,$row['jabatan']);
            $modul->jabatan = json_encode($jabatan);

            $modul->update();
            return $modul;

        }else{
            
            return new Employee([
                'jenis_pegawai'=> $row['jenis_karyawan'],
                'nip' => $nip,
                'nik' => $nik,
                'nama' => $row['nama'],
                'jenis_kelamin' => trim($row['jenis_kelamin']," "),
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $tanggalLahir,
                'alamat' => $row['alamat'],
                'email' => $row['email'],
                'no_hp' => $row['no_hp'] ?? "0",
                'jabatan' => json_encode([$row['jabatan']]),
                'agama' => trim($row['agama']," "),
                'status_pegawai' => $row['status_karyawan'],
                'tanggal_bergabung' => $tanggalBergabung,
                'unit' => json_encode([$row['unit']]) ?? null,
                'id_time_settings_employee' => $row['waktu_guru'] ?? null,
            ]);
        }
    }
}