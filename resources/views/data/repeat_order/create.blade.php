@extends('layouts.stisla')

@section('title', $title)

@section('content')
    @include('components.breadcrumbs', [
        'title' => $title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard.index')],
            ['label' => 'Data Order CRM', 'url' => route('admin.data.repeat_order.index')],
            ['label' => 'Tambah']
        ]
    ])

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.data.repeat_order.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Tanggal Order</label>
                        <div class="input-group date" id="tanggalPicker" data-target-input="nearest">
                            <input type="text" name="tanggal" class="form-control datetimepicker-input" style="border-color: #aaa" data-target="#tanggalPicker"/>
                            <div class="input-group-append " data-target="#tanggalPicker" data-toggle="datetimepicker">
                                <div class="input-group-text" style="border-color: #aaa"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Lokasi Gudang</label>
                        <select name="lok_gudang" class="form-control select2">
                            <option value="">-- Pilih --</option>
                            <option value="jakarta">Jakarta</option>
                            <option value="surabaya">Surabaya</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Nama CRM</label>
                        <input type="text" name="nama_crm" class="form-control" style="border-color: #aaa" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Advertiser</label>
                        <select name="adv_id" class="form-control select2">
                            <option value="">-- Pilih --</option>
                            @foreach ($adv as $advp)
                                <option value="{{ $advp->id }}" {{ old('adv_id', $repeadorder->adv_id ?? '') == $advp->id ? 'selected' : '' }}>
                                    {{ $advp->kode ? $advp->kode . '.' : '' }}{{ $advp->deskripsi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Customer</label>
                        <input type="text" name="customer" class="form-control" style="border-color: #aaa">
                    </div>

                    <div class="form-group col-md-4">
                        <label>SKU Produk</label>
                        <select name="sku_produk_id" class="form-control select2">
                            <option value="">-- Pilih --</option>
                            @foreach ($sku as $skup)
                                <option value="{{ $skup->id }}" {{ old('sku_produk_id', $repeadorder->sku_produk_id ?? '') == $skup->id ? 'selected' : '' }}>
                                    {{ $skup->kode ? $skup->kode . ' - ' : '' }}{{ $skup->deskripsi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" style="border-color: #aaa">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Qty Produk</label>
                        <input type="text" id="qty_produk" name="qty_produk" class="form-control" style="border-color: #aaa" min="1">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Harga Produk</label>
                        <input type="text" name="harga_produk" id="harga_produk" class="form-control" style="border-color: #aaa">
                    </div>

                    <div class="form-group col-md-4">
                        <label>No. HP</label>
                        <input type="text" name="no_hp" class="form-control" style="border-color: #aaa">
                    </div>

                    <div class="form-group col-md-12">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" style="border-color: #aaa" rows="2"></textarea>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Provinsi</label>
                        <select id="provinsi" name="provinsi" class="form-control select2">
                            <option value="">-- Pilih --</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Kabupaten</label>
                        <select id="kabupaten" name="kabupaten" class="form-control select2">
                            <option value="">-- Pilih --</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Kecamatan</label>
                        <select id="kecamatan" name="kecamatan" class="form-control select2">
                            <option value="">-- Pilih --</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Kelurahan</label>
                        <select id="kelurahan" name="kelurahan" class="form-control select2">
                            <option value="">-- Pilih --</option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Kode Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" class="form-control" style="border-color: #aaa" readonly>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Kode Promo</label>
                        <select name="kode_promo_id" class="form-control select2">
                            <option value="">-- Pilih --</option>
                            @foreach ($kodePromo as $promo)
                                <option value="{{ $promo->id }}" {{ old('kode_promo_id', $repeadorder->kode_promo_id ?? '') == $promo->id ? 'selected' : '' }}>
                                    {{ $promo->kode ? $promo->kode . ' - ' : '' }}{{ $promo->deskripsi }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group col-md-4">
                        <label>Pembayaran</label>
                        <select name="pembayaran" id="pembayaran" class="form-control select2">
                            <option value="">-- Pilih --</option>
                            <option value="transfer">Transfer</option>
                            <option value="cod">COD</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Ekspedisi</label>
                        <select name="ekpedisi" class="form-control select2">
                            <option value="">-- Pilih --</option>
                            <option value="jne">JNE</option>
                            <option value="jnt">JNT</option>
                            <option value="ninja">Ninja Express</option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Ongkir</label>
                        <input type="text" name="ongkir" id="ongkir" class="form-control" style="border-color: #aaa">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Diskon Ongkir</label>
                        <input type="text" name="diskon_ongkir" id="diskon_ongkir" class="form-control" style="border-color: #aaa">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Admin COD</label>
                        <input type="text" name="admin_cod" id="admin_cod" class="form-control" style="border-color: #aaa">
                        <small class="form-text text-muted" id="note_cod"></small>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Diskon Admin COD</label>
                        <input type="text" name="diskon_admin_cod" id="diskon_admin_cod" class="form-control" style="border-color: #aaa">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Total Pembayaran</label>
                        <input type="text" name="total_pembayaran" id="total_pembayaran" class="form-control" style="border-color: #aaa" readonly>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Tanggal Transfer</label>
                        <div class="input-group date" id="tanggalTf" data-target-input="nearest">
                            <input type="text" name="tanggal_tf" class="form-control datetimepicker-input" style="border-color: #aaa" data-target="#tanggalTf"/>
                            <div class="input-group-append " data-target="#tanggalTf" data-toggle="datetimepicker">
                                <div class="input-group-text" style="border-color: #aaa"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Bukti Transfer</label>
                        <input type="file" name="bukti_tf" class="form-control-file border" style="border-color: #aaa">
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.data.repeat_order.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function () {
        $('select[name="ekpedisi"]').on('change', function () {
            updateBiayaByEkspedisi();
        });

        $('#pembayaran').on('change', function () {
            updateFieldsByPayment();
            updateBiayaByEkspedisi();
            updateTotal();
        });

        function updateBiayaByEkspedisi() {
            let ekspedisi = $('select[name="ekpedisi"]').val();
            let pembayaran = $('#pembayaran').val();

            if (!ekspedisi || !tarifEkspedisi[ekspedisi]) return;

            let ongkir = tarifEkspedisi[ekspedisi].ongkir;
            let feePercent = tarifEkspedisi[ekspedisi].fee_cod_percent || 0;
            let qty = unformatNumber($('#qty_produk').val());
            let harga = unformatNumber($('#harga_produk').val());
            let totalBarang = qty * harga;
            let adminCOD = Math.round(((totalBarang + ongkir) * feePercent) / 100);


            if (pembayaran === 'transfer') {
                setField('#ongkir', ongkir, false);
                setField('#admin_cod', 0, true);
                setField('#diskon_ongkir', 0, false);
                setField('#diskon_admin_cod', 0, true);
            } else if (pembayaran === 'cod') {
                setField('#admin_cod', adminCOD, false);
                setField('#ongkir', ongkir, true);
                setField('#diskon_admin_cod', 0, false);
                setField('#diskon_ongkir', 0, false);
            } else {
                setField('#ongkir', ongkir, false);
                setField('#admin_cod', adminCOD, false);
                setField('#diskon_ongkir', 0, false);
                setField('#diskon_admin_cod', 0, false);
            }

            updateTotal(); // agar total langsung ikut berubah
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function unformatNumber(str) {
            return parseInt(String(str).replace(/[^\d]/g, '')) || 0;
        }

        function setField(selector, value, isReadOnly) {
            $(selector).val(formatNumber(value));
            if (isReadOnly) {
                $(selector).attr('readonly', 'readonly');
            } else {
                $(selector).removeAttr('readonly');
            }
        }

        function updateFieldsByPayment() {
            let pembayaran = $('#pembayaran').val();

            if (pembayaran === 'transfer') {
                // Ongkir & Diskon Ongkir aktif dan tampil
                $('#ongkir, #diskon_ongkir')
                    .removeAttr('readonly')
                    .closest('.form-group').show();

                // Admin COD & Diskon Admin COD disembunyikan
                $('#admin_cod, #diskon_admin_cod')
                    .closest('.form-group').hide();
                $('input[name="tanggal_tf"]').closest('.form-group').show();
                $('input[name="bukti_tf"]').closest('.form-group').show();
            } else if (pembayaran === 'cod') {
                // Ongkir & Diskon Ongkir tetap tampil (bisa readonly jika ingin)
                $('#ongkir, #diskon_ongkir')
                    .removeAttr('readonly')
                    .closest('.form-group').show();

                // Admin COD & Diskon Admin COD tampil
                $('#admin_cod, #diskon_admin_cod')
                    .removeAttr('readonly')
                    .closest('.form-group').show();
                $('input[name="tanggal_tf"]').closest('.form-group').hide();
                $('input[name="bukti_tf"]').closest('.form-group').hide();
            } else {
                // Default: semua field aktif dan tampil
                $('#ongkir, #diskon_ongkir, #admin_cod, #diskon_admin_cod')
                    .removeAttr('readonly')
                    .closest('.form-group').show();
                $('input[name="tanggal_tf"]').closest('.form-group').show();
                $('input[name="bukti_tf"]').closest('.form-group').show();
            }
        }

        function updateTotal() {
            let qty = unformatNumber($('#qty_produk').val());
            let harga = unformatNumber($('#harga_produk').val());
            let ongkir = unformatNumber($('#ongkir').val());
            let diskonOngkir = unformatNumber($('#diskon_ongkir').val());
            let adminCOD = unformatNumber($('#admin_cod').val());
            let diskonAdminCOD = unformatNumber($('#diskon_admin_cod').val());
            let pembayaran = $('#pembayaran').val();

            let total = 0;

            if (pembayaran === 'transfer') {
                total = (qty * harga) + ongkir - diskonOngkir;
            } else if (pembayaran === 'cod') {
                total = (qty * harga) + adminCOD + ongkir - diskonAdminCOD - diskonOngkir;
            } else {
                total = (qty * harga) + ongkir - diskonOngkir + adminCOD - diskonAdminCOD;
            }

            $('#total_pembayaran').val(formatNumber(total));
        }

        // Formatting angka saat input
        function addNumberFormatting() {
            let fields = ['#harga_produk', '#ongkir', '#diskon_ongkir', '#admin_cod', '#diskon_admin_cod'];
            fields.forEach(function (selector) {
                $(selector).on('input', function () {
                    let cursorPos = this.selectionStart;
                    let rawValue = unformatNumber($(this).val());
                    $(this).val(formatNumber(rawValue));
                    this.setSelectionRange(cursorPos, cursorPos);
                });
            });
        }

        function updateCODNote() {
            let pembayaran = $('#pembayaran').val();
            let ekspedisi = $('select[name="ekpedisi"]').val();
            let fee = tarifEkspedisi[ekspedisi]?.fee_cod_percent || 0;
            let ongkir = tarifEkspedisi[ekspedisi]?.ongkir || 0;

            if (pembayaran === 'cod' && ekspedisi) {
                $('#note_cod').html(`${ekspedisi.toUpperCase()} FeeCOD (<b>${fee}%</b>)`);
            } else {
                $('#note_cod').text('');
            }
        }

        $('#pembayaran, select[name="ekpedisi"]').on('change', function () {
            updateCODNote();
        });

        $('#tanggalPicker').datetimepicker({ format: 'DD-MM-YYYY' });
        $('#tanggalTf').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            icons: {
                time: 'fa fa-clock',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-calendar-check-o',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            },
            sideBySide: true,
            useCurrent: false
        });

        $('#qty_produk, #harga_produk, #ongkir, #diskon_ongkir, #admin_cod, #diskon_admin_cod')
            .on('input', function(){
                updateTotal();
            });

        $('#pembayaran').on('change', function() {
            updateFieldsByPayment();
            updateTotal();
        });

        updateFieldsByPayment();
        updateTotal();
        addNumberFormatting();
    });

    const wilayahData = {
        "DKI Jakarta": {
            "Jakarta Pusat": {
                "Gambir": {
                    "Gambir": "10110"
                },
                "Sawah Besar": {
                    "Pasar Baru": "10710"
                }
            },
            "Jakarta Selatan": {
                "Pasar Minggu": {
                    "Ragunan": "12550"
                },
                "Tebet": {
                    "Manggarai Selatan": "12860"
                }
            },
            "Jakarta Timur": {
                "Pulogadung": {
                    "Jati": "13220"
                },
                "Jatinegara": {
                    "Cipinang": "13340"
                }
            }
        },
        "Riau": {
            "Kota Batam": {
                "Bengkong": {
                    "Tanjung Buntung": "29432"
                },
                "Batam Kota": {
                    "Baloi Permai": "29431"
                }
            }
        },
        "Kalimantan Selatan": {
            "Kabupaten Tapin": {
                "Lokpaikat": {
                    "Bitahan Baru": "71154"
                },
                "Bakarangan": {
                    "Masta": "71152"
                }
            }
        }
    };

    function populateSelect($select, data, placeholder = '-- Pilih --') {
        $select.empty().append(`<option value="">${placeholder}</option>`);
        Object.keys(data).forEach(key => {
            $select.append(`<option value="${key}">${key}</option>`);
        });
    }

    $(document).ready(function () {
        const $provinsi = $('#provinsi');
        const $kabupaten = $('#kabupaten');
        const $kecamatan = $('#kecamatan');
        const $kelurahan = $('#kelurahan');
        const $kodePos = $('#kode_pos');

        populateSelect($provinsi, wilayahData);

        $provinsi.on('change', function () {
            const val = $(this).val();
            if (val && wilayahData[val]) {
                populateSelect($kabupaten, wilayahData[val]);
            }
            $kecamatan.empty().append('<option value="">-- Pilih --</option>');
            $kelurahan.empty().append('<option value="">-- Pilih --</option>');
            $kodePos.val('');
        });

        $kabupaten.on('change', function () {
            const prov = $provinsi.val();
            const kab = $(this).val();
            if (prov && kab && wilayahData[prov][kab]) {
                populateSelect($kecamatan, wilayahData[prov][kab]);
            }
            $kelurahan.empty().append('<option value="">-- Pilih --</option>');
            $kodePos.val('');
        });

        $kecamatan.on('change', function () {
            const prov = $provinsi.val();
            const kab = $kabupaten.val();
            const kec = $(this).val();
            if (prov && kab && kec && wilayahData[prov][kab][kec]) {
                populateSelect($kelurahan, wilayahData[prov][kab][kec]);
            }
            $kodePos.val('');
        });

        $kelurahan.on('change', function () {
            const prov = $provinsi.val();
            const kab = $kabupaten.val();
            const kec = $kecamatan.val();
            const kel = $(this).val();
            if (prov && kab && kec && kel && wilayahData[prov][kab][kec][kel]) {
                $kodePos.val(wilayahData[prov][kab][kec][kel]);
            }
        });
    });

    // Dummy tarif ekspedisi
    const tarifEkspedisi = {
        "jne": { "ongkir": 50000, "fee_cod_percent": 2.5 },
        "jnt": { "ongkir": 28000, "fee_cod_percent": 2.5 },
        "ninja": { "ongkir": 30000, "fee_cod_percent": 3 }
    };

updateFieldsByPayment();
updateBiayaByEkspedisi();
updateTotal();
</script>
@endpush