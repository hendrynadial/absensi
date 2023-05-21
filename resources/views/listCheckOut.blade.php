<div class="modal-header">
    <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Karyawan Check Out</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table id="datatableOut" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>Jenis Pegawai</th>
                <th>NIK</th>
                <th>Nama Pegawai</th>
                <th>Jam Check-Out</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modul as $value)
            <tr>
                <td>{{ $value->RelasiPegawai->jenis_pegawai }}</td>
                <td>{{ $value->RelasiPegawai->nik }}</td>
                <td>{{ $value->RelasiPegawai->nama }}</td>
                <td>{{ $value->check_out }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $('#datatableOut').DataTable();
</script>
