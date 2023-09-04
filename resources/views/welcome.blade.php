<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Web Dev Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
</head>

<body>
    <div class="container mt-5">
        <div class="mb-3">
            <h3>Soal 1</h3>
            <form action="{{ route('bilangan.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="bilangan" class="form-label">Masukkan Jumlah Perulangan</label>
                    <input type="number" name="bilangan" id="bilangan" class="form-control">
                    @error('bilangan')
                        <div class="mt-2"><span class="text-danger">{{ $message }}</span></div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
        <hr>
        <div class="mb-3">
            <h3>Soal 2</h3>
            <form id="form-ongkir">
                @csrf
                <div class="mb-3">
                    <label for="origin" class="form-label">Asal Pengiriman</label>
                    <select name="origin" class="form-select" id="origin">
                        <option value="501" selected>Yogyakarta</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="destination" class="form-label">Tujuan Pengiriman</label>
                    <select class="destination form-control form-select" name="destination" id="destination">
                    </select>
                </div>
                <div class="mb-3">
                    <label for="weight" class="form-label">Berat Barang <strong>(gram)</strong></label>
                    <input type="number" name="weight" id="weight" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="courier" class="form-label">Kurir</label>
                    <select name="courier" id="courier" class="form-select">
                        <option value="jne">JNE</option>
                        <option value="pos">POS</option>
                        <option value="tiki">TIKI</option>
                    </select>
                </div>
                <button type="button" id="btn-ongkir" class="btn btn-primary">Cek Ongkir</button>
            </form>
        </div>
        <div class="mb-3" id="pengiriman">
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#destination').select2({
                theme: 'bootstrap-5'
            });

            async function getCity() {
                let url = "http://localhost:8000/city";
                try {
                    let response = await fetch(url);
                    return await response.json();
                } catch (error) {
                    console.log(error);
                }
            }

            async function renderCity() {
                let city = await getCity();
                let {
                    status
                } = city.rajaongkir;

                if (status['code'] == 200) {
                    let {
                        results
                    } = city.rajaongkir;

                    let html = '';

                    results.forEach(data => {
                        let htmlSegment =
                            `<option value="${data['city_id']}">${data['city_name']}, ${data['province']}</option>`;
                        html += htmlSegment;
                    });

                    $('#destination').append(html);
                }
            }

            renderCity();

            $('#btn-ongkir').click(function(e) {
                e.preventDefault();

                let origin = $('#origin').val();
                let destination = $('#destination').val();
                let weight = $('#weight').val();
                let courier = $('#courier').val();
                let token = $("meta[name='csrf-token']").attr("content");

                $.ajax({
                    url: `http://localhost:8000/ongkir`,
                    type: "POST",
                    cache: false,
                    data: {
                        "_token": token,
                        "origin": origin,
                        "destination": destination,
                        "weight": weight,
                        "courier": courier
                    },
                    success: function(response) {
                        let {
                            status
                        } = response.rajaongkir;
                        if (status['code'] === 200) {
                            let {
                                results
                            } = response.rajaongkir;
                            let html = '';

                            results[0].costs.forEach(data => {
                                let htmlSegment = `
                                <div class="card my-3">
                                    <div class="card-header">${data.service}</div>
                                    <div class="card-body">
                                        <p>${data.description}</p>
                                        <p>Estimasi : ${data.cost[0].etd} /hari</p>
                                        <p>Catatan : ${data.cost[0].note}</p>
                                    </div>
                                    <div class="card-footer">
                                    <h5>Rp. ${numberWithDots(data.cost[0].value)}</h5>
                                    </div>
                                </div>`;

                                html += htmlSegment;
                            });

                            $('#pengiriman').html(html);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            });

            function numberWithDots(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        });
    </script>
</body>

</html>
