<table style="width:100%" class="table-data" border="1">
    <thead>
        <tr>
            <th class="align-middle text-center" width="3%">No</th>
            <th class="align-middle text-center">Judul Penelitian</th>
            @if(!$keterangan['prodi'])
            <th class="align-middle text-center" width="10%">Program Studi</th>
            @endif
            @if($tampil['ketua'] || $tampil['anggota'])
            <th class="align-middle text-center" width="25%">Dosen Terlibat</th>
            @endif
            <th class="align-middle text-center" width="10%">Tahun</th>
            <th class="align-middle text-center" width="10%">Sumber Biaya</th>
            <th class="align-middle text-center" width="10%">Biaya</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td class='text-center'>{{ $loop->iteration }}</td>
            <td>{{ $d->judul_penelitian }}</td>
            @if(!$keterangan['prodi'])
            <td>{{ $d->researchKetua->teacher->latestStatus->studyProgram->nama }}</td>
            @endif
            @if($tampil['ketua'] || $tampil['anggota'])
            <td>
                @if($tampil['ketua'] && $tampil['anggota'] == 0)
                {{ $d->researchKetua->teacher->nama }}<br>
                <small class="text-muted">NIDN.{{ $d->researchKetua->teacher->nidn }}</small>
                @endif
                @if($tampil['ketua'] == 0 && $tampil['anggota'])
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach($d->researchAnggota as $ra)
                    <li>
                        {{$ra->teacher->nama}} ({{$ra->status}})<br>
                    </li>
                    @endforeach
                </ul>
                @endif
                @if($tampil['ketua'] && $tampil['anggota'])
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach($d->researchTeacher as $rt)
                    <li>
                        {{$rt->teacher->nama}} ({{$rt->status}})<br>
                    </li>
                    @endforeach
                </ul>
                @endif
            </td>
            @endif
            <td class="align-middle text-center">{{ $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester }}</td>
            <td>{{ $d->sumber_biaya ?? '-' }}</td>
            <td class="align-middle text-right">{{ rupiah($d->jumlah_biaya) ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
