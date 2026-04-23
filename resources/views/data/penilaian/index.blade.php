@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Penilaian Alternatif']
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
                                    <th>Alternatif</th>
                                    @foreach ($kriteria as $k)
                                        <th>{{ $k->nama }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alternatif as $alt)
                                    <tr>
                                        <td>{{ $alt->nama }}</td>
                                        @foreach ($kriteria as $k)
                                            @php
                                                $nilai = $alt->penilaians->firstWhere('id_kriteria', $k->id_kriteria);
                                            @endphp
                                            <td>
                                                @php
                                                    $isTahun = strtolower($k->nama) === 'tahun';
                                                    $val = $nilai ? $nilai->nilai : '';
                                                    if (!$isTahun && $val !== '') {
                                                        $val = number_format($val, 0, ',', '.');
                                                    }
                                                @endphp
                                                <input
                                                    type="text"
                                                    class="form-control input-nilai"
                                                    name="nilai[{{ $alt->id_alternatif }}][{{ $k->id_kriteria }}]"
                                                    value="{{ $val }}"
                                                    placeholder="Isi nilai"
                                                    data-kriteria="{{ strtolower($k->nama) }}"
                                                >
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

    // Format angka menjadi ribuan
    function formatNumber(n) {
        return n.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Saat user input nilai
    $('.input-nilai').on('input', function () {
        const kriteria = $(this).data('kriteria').toLowerCase();
        let val = $(this).val();

        if (kriteria !== 'tahun') {
            val = formatNumber(val);
            $(this).val(val);
        }
    });

    // Prevent input selain angka
    $('.input-nilai').on('keypress', function (e) {
        if (e.which < 48 || e.which > 57) {
            e.preventDefault();
        }
    });

    $('#form-penilaian').on('submit', function(e) {
        e.preventDefault();

        $(this).find('.input-nilai').each(function () {
            const kriteria = $(this).data('kriteria').toLowerCase();
            if (kriteria !== 'tahun') {
                $(this).val($(this).val().replace(/\./g, '')); // hapus titik
            }
        });

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
