@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Penilaian Siswa']
        ]
    ])

    <div class="section-body">
        <div class="card card-primary">
            <div class="card-body" style="overflow-x: auto;">
                <div style="min-width: max-content;">
                    <form id="form-penilaian">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    @foreach ($kriteria as $k)
                                        <th>{{ $k->nama }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswa as $alt)
                                    <tr>
                                        <td>{{ $alt->nama }}</td>
                                        @foreach ($kriteria as $k)
                                            @php
                                                $nilai = $alt->penilaians->firstWhere('id_kriteria', $k->id_kriteria);
                                            @endphp
                                            <td>
                                                @php
                                                    $nilai = $alt->penilaians->firstWhere(
                                                        'id_kriteria',
                                                        $k->id_kriteria
                                                    );
                                                @endphp

                                                <input type="number" class="form-control input-nilai"
                                                    name="nilai[{{ $alt->id_siswa }}][{{ $k->id_kriteria }}]"
                                                    value="{{ $nilai ? $nilai->nilai : '' }}" placeholder="Isi nilai"
                                                    min="0">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary mt-3">Simpan Semua</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Hanya boleh angka
        $('.input-nilai').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $('#form-penilaian').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.data.penilaian.bulkstore') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire('Berhasil!', response.message, 'success');
                },
                error: function(xhr) {
                    Swal.fire('Gagal!', 'Ada kesalahan saat menyimpan data.', 'error');
                }
            });
        });
    </script>
@endpush
