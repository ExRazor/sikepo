<table style="width:100%" class="table-data" border="1">
    <thead>
        <tr>
            <th class="align-middle text-center" width="2%">No</th>
            <th class="align-middle text-center" width="15%">Nama Pembuat</th>
            @if(!$keterangan['prodi'])
            <th class="align-middle text-center" width="10%">Kegiatan</th>
            @endif
            <th class="align-middle text-center">Judul Luaran</th>
            <th class="align-middle text-center">Jenis Luaran</th>
            <th class="align-middle text-center" width="10%">Tahun</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td class='text-center'>{{ $loop->iteration }}</td>
            <td>
                {{ $d->teacher->nama }}<br>
                <small class="text-muted">NIDN.{{ $d->teacher->nidn }}</small>
            </td>
            @if(!$keterangan['prodi'])
            <td>{{ $d->teacher->latestStatus->studyProgram->nama }}</td>
            @endif
            <td>{{ $d->judul_luaran }}</td>
            <td>{{ $d->jenis_luaran }}</td>
            <td class="align-middle text-center">{{ $d->academicYear->tahun_akademik }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
