<table style="width:100%" class="table-data" border="1">
    <thead>
        <tr>
            <th class="align-middle text-center" width="2%">No</th>
            <th class="align-middle text-center">Judul Penelitian</th>
            @if(!$keterangan['prodi'])
            <th class="align-middle text-center" width="10%">Program Studi</th>
            @endif
            <th class="align-middle text-center" width="25%">Dosen Terlibat</th>
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
            <td>
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach($d->researchTeacher as $rt)
                    <li>
                        {{$rt->teacher->nama}} ({{$rt->status}})<br>
                    </li>
                    @endforeach
                    {{-- @if($data->researchStudent)
                        @foreach($data->researchStudent as $rs)
                        <li>
                            {{$rs->student->nama}} (Mahasiswa)<br>
                        </li>
                        @endforeach
                    @endif --}}
                </ul>
                {{-- {{ $d->researchKetua->teacher->nama }}<br>
                <small class="text-muted">NIDN.{{ $d->researchKetua->teacher->nidn }}</small> --}}
            </td>
            <td class="align-middle text-center">{{ $d->academicYear->tahun_akademik.' - '.$d->academicYear->semester }}</td>
            <td>{{ $d->sumber_biaya ?? '-' }}</td>
            <td class="align-middle text-right">{{ rupiah($d->jumlah_biaya) ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
