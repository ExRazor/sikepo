<table style="width:100%" class="table-data" border="1">
    <thead>
        <tr>
            <th class="align-middle text-center" width="2%">No</th>
            <th class="align-middle text-center" width="15%">Nama Dosen</th>
            @if(!$keterangan['prodi'])
            <th class="align-middle text-center" width="10%">Program Studi</th>
            @endif
            <th class="align-middle text-center">Judul Penelitian</th>
            <th class="align-middle text-center" width="10%">Tahun</th>
            <th class="align-middle text-center" width="10%">Sumber Biaya</th>
            <th class="align-middle text-center" width="10%">Biaya</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td class='text-center'>{{ $loop->iteration }}</td>
            <td>
                {{ $d->researchKetua->teacher->nama }}<br>
                <small class="text-muted">NIDN.{{ $d->researchKetua->teacher->nidn }}</small>
            </td>
            @if(!$keterangan['prodi'])
            <td>{{ $d->researchKetua->teacher->latestStatus->studyProgram->nama }}</td>
            @endif
            <td>{{ $d->judul_penelitian }}</td>
            <td class="align-middle text-center">{{ $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester }}</td>
            <td>{{ $d->sumber_biaya ?? '-' }}</td>
            <td>{{ rupiah($d->jumlah_biaya) ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
