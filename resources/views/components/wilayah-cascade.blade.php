@props([
    'provinceName' => 'provinsi_id',
    'regencyName' => 'kabupaten_id',
    'districtName' => 'kecamatan_id',
    'villageName' => 'kelurahan_id',
    'postalCodeName' => 'pos_code',
    'showPostalCode' => true,
    'id' => null,
])

@php
    $uid = $id ?? 'wc_' . substr(md5(uniqid()), 0, 8);
@endphp

<div id="{{ $uid }}" class="wilayah-cascade-wrapper">
    <style>
        #{{ $uid }} .wc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }
        #{{ $uid }} .wc-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        #{{ $uid }} .wc-label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }
        #{{ $uid }} .wc-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            background-color: #ffffff;
            color: #0f172a;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        #{{ $uid }} .wc-select:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }
        #{{ $uid }} .wc-select:disabled {
            background-color: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
        }
        #{{ $uid }} .wc-badge-pos {
            margin-top: 4px;
            font-size: 12px;
            color: #0284c7;
            font-weight: 600;
        }
    </style>

    <div class="wc-grid">
        <!-- 1. Provinsi -->
        <div class="wc-group">
            <label class="wc-label" for="{{ $uid }}_prov">Provinsi</label>
            <select id="{{ $uid }}_prov" name="{{ $provinceName }}" class="wc-select">
                <option value="">-- Pilih Provinsi --</option>
            </select>
        </div>

        <!-- 2. Kabupaten / Kota -->
        <div class="wc-group">
            <label class="wc-label" for="{{ $uid }}_kab">Kabupaten / Kota</label>
            <select id="{{ $uid }}_kab" name="{{ $regencyName }}" class="wc-select" disabled>
                <option value="">-- Pilih Kab/Kota --</option>
            </select>
        </div>

        <!-- 3. Kecamatan -->
        <div class="wc-group">
            <label class="wc-label" for="{{ $uid }}_kec">Kecamatan</label>
            <select id="{{ $uid }}_kec" name="{{ $districtName }}" class="wc-select" disabled>
                <option value="">-- Pilih Kecamatan --</option>
            </select>
        </div>

        <!-- 4. Kelurahan / Desa -->
        <div class="wc-group">
            <label class="wc-label" for="{{ $uid }}_kel">Kelurahan / Desa</label>
            <select id="{{ $uid }}_kel" name="{{ $villageName }}" class="wc-select" disabled>
                <option value="">-- Pilih Kelurahan/Desa --</option>
            </select>
            @if ($showPostalCode)
                <div id="{{ $uid }}_pos_wrap" class="wc-badge-pos" style="display:none;">
                    Kode Pos: <span id="{{ $uid }}_pos">-</span>
                    <input type="hidden" id="{{ $uid }}_pos_input" name="{{ $postalCodeName }}" value="">
                </div>
            @endif
        </div>
    </div>

    <script>
        (function() {
            const root = document.getElementById('{{ $uid }}');
            const selProv = root.querySelector('#{{ $uid }}_prov');
            const selKab = root.querySelector('#{{ $uid }}_kab');
            const selKec = root.querySelector('#{{ $uid }}_kec');
            const selKel = root.querySelector('#{{ $uid }}_kel');
            const posWrap = root.querySelector('#{{ $uid }}_pos_wrap');
            const posSpan = root.querySelector('#{{ $uid }}_pos');
            const posInput = root.querySelector('#{{ $uid }}_pos_input');

            // Load provinces on initialize
            fetch('/api/v1/provinsi')
                .then(r => r.json())
                .then(res => {
                    const data = res.data || [];
                    data.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = `${p.name} (${p.code})`;
                        selProv.appendChild(opt);
                    });
                })
                .catch(err => console.error('WilayahCascade Error:', err));

            selProv.addEventListener('change', function() {
                const provId = this.value;
                selKab.innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';
                selKab.disabled = true;
                selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                selKec.disabled = true;
                selKel.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                selKel.disabled = true;
                if (posWrap) posWrap.style.display = 'none';

                if (!provId) return;

                fetch(`/api/v1/provinsi/${provId}/kabupaten`)
                    .then(r => r.json())
                    .then(res => {
                        (res.data || []).forEach(k => {
                            const opt = document.createElement('option');
                            opt.value = k.id;
                            opt.textContent = `${k.type} ${k.name}`;
                            selKab.appendChild(opt);
                        });
                        selKab.disabled = false;
                    });
            });

            selKab.addEventListener('change', function() {
                const kabId = this.value;
                selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                selKec.disabled = true;
                selKel.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                selKel.disabled = true;
                if (posWrap) posWrap.style.display = 'none';

                if (!kabId) return;

                fetch(`/api/v1/kabupaten/${kabId}/kecamatan`)
                    .then(r => r.json())
                    .then(res => {
                        (res.data || []).forEach(kc => {
                            const opt = document.createElement('option');
                            opt.value = kc.id;
                            opt.textContent = kc.name;
                            selKec.appendChild(opt);
                        });
                        selKec.disabled = false;
                    });
            });

            selKec.addEventListener('change', function() {
                const kecId = this.value;
                selKel.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                selKel.disabled = true;
                if (posWrap) posWrap.style.display = 'none';

                if (!kecId) return;

                fetch(`/api/v1/kecamatan/${kecId}/kelurahan`)
                    .then(r => r.json())
                    .then(res => {
                        (res.data || []).forEach(kl => {
                            const opt = document.createElement('option');
                            opt.value = kl.id;
                            opt.textContent = kl.name;
                            opt.dataset.pos = kl.pos_code || '';
                            selKel.appendChild(opt);
                        });
                        selKel.disabled = false;
                    });
            });

            selKel.addEventListener('change', function() {
                const opt = this.selectedOptions[0];
                const pos = opt ? opt.dataset.pos : '';
                if (posWrap && pos) {
                    posSpan.textContent = pos;
                    posInput.value = pos;
                    posWrap.style.display = 'block';
                } else if (posWrap) {
                    posWrap.style.display = 'none';
                    posInput.value = '';
                }
            });
        })();
    </script>
</div>
