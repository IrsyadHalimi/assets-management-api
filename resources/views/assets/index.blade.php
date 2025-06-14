<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Aset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <style>
        #notification-tray {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            max-width: 350px;
        }

        .notification-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Manajemen Aset</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-light" onclick="openForm()">Tambah Aset</button>
                    <a href="{{ route('assets.printPdf') }}" class="btn btn-warning">Cetak PDF</a>

                    {{-- Tampilkan Logout jika sudah login --}}
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    @endauth

                    {{-- Tampilkan Login jika belum login --}}
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-success">Login</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">Daftar Aset</h3>

        <div class="container">
            <h4 class="mb-4">Grafik Aset per Kategori</h4>
            <div id="categoryChart" style="height: 400px; margin: 0 auto"></div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="assetTable">
                <thead class="table-dark text-center align-middle">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kode Aset</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Deskripsi</th>
                        <th>Harga Per Item</th>
                        <th>Jumlah</th>
                        <th>Total Nilai</th>
                        <th>Tanggal Pengesahan</th>
                        <th colspan="2">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="assetModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="assetForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Aset</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="assetId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" id="name" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="asset_code" class="form-label">Kode Asset</label>
                                <input type="text" class="form-control" id="asset_code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select id="category_id" class="form-control" name="category_id" required>
                                    <option value="">-- Memuat kategori... --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" id="location" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea id="description" class="form-control"></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Harga per Unit</label>
                                <input type="number" class="form-control" id="price">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="quantity" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="quantity">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="amount" class="form-label">Total Nilai</label>
                                <input type="number" class="form-control" id="amount" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="established_at" class="form-label">Tanggal Penetapan</label>
                                <input type="date" class="form-control" id="established_at">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/api/assets/chart/category')
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const categories = res.data.map(item => item.category);
                        const data = res.data.map(item => item.total);

                        Highcharts.chart('categoryChart', {
                            chart: {
                                type: 'column',
                                options3d: {
                                    enabled: true,
                                    alpha: 15,
                                    beta: 15,
                                    depth: 50,
                                    viewDistance: 25
                                }
                            },
                            title: {
                                text: 'Jumlah Aset per Kategori'
                            },
                            xAxis: {
                                categories: categories,
                                title: {
                                    text: 'Kategori'
                                }
                            },
                            yAxis: {
                                title: {
                                    text: 'Jumlah Aset'
                                },
                                allowDecimals: false
                            },
                            plotOptions: {
                                column: {
                                    depth: 25
                                }
                            },
                            series: [{
                                name: 'Jumlah Aset',
                                data: data,
                                colorByPoint: true
                            }]
                        });
                    } else {
                        alert('Gagal memuat data chart');
                        console.error(res.message || res.error);
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan');
                    console.error(error);
                });
        });

        const notyf = new Notyf({
            duration: 0,
            dismissible: true,
            position: {
                x: 'right',
                y: 'top',
            },
        });
        const assetModal = new bootstrap.Modal(document.getElementById('assetModal'));

        function loadAssets() {
            axios.get('/api/assets')
                .then(res => {
                    const tbody = document.querySelector('#assetTable tbody');
                    tbody.innerHTML = '';
                    let i = 1;
                    res.data.data.forEach(asset => {
                        let establishedAt = new Date(asset.established_at).toISOString().slice(0, 10);

                        const formatRupiah = (value) => {
                            if (value == null) return '-';
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(value));
                        };

                        tbody.innerHTML += `
                            <tr>
                                <td>${i++}</td>
                                <td>${asset.name}</td>
                                <td>${asset.asset_code}</td>
                                <td>${asset.category?.name ?? '-'}</td>
                                <td>${asset.location}</td>
                                <td>${asset.description || '-'}</td>
                                <td>${formatRupiah(asset.price)}</td>
                                <td>${asset.quantity}</td>
                                <td>${formatRupiah(asset.amount)}</td>
                                <td>${establishedAt}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick='editAsset(${JSON.stringify(asset)})'>Edit</button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick='deleteAsset(${asset.id})'>Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(() => notyf.error('Gagal memuat data aset'));
        }

        function openForm() {
            document.getElementById('assetForm').reset();
            document.getElementById('assetId').value = '';
            document.getElementById('modalTitle').textContent = 'Tambah Aset';
            assetModal.show();
        }

        function editAsset(asset) {
            let establishedAt = new Date(asset.established_at).toISOString().slice(0, 10);
            document.getElementById('assetId').value = asset.id;
            document.getElementById('name').value = asset.name;
            document.getElementById('asset_code').value = asset.asset_code;
            document.getElementById('category_id').value = asset.category_id;
            document.getElementById('location').value = asset.location;
            document.getElementById('description').value = asset.description || '';
            document.getElementById('price').value = asset.price;
            document.getElementById('quantity').value = asset.quantity;
            document.getElementById('amount').value = asset.amount;
            document.getElementById('established_at').value = establishedAt;
            document.getElementById('modalTitle').textContent = 'Edit Aset';
            assetModal.show();
        }

        document.getElementById('assetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('assetId').value;
            const data = {
                name: document.getElementById('name').value,
                asset_code: document.getElementById('asset_code').value,
                category_id: document.getElementById('category_id').value,
                location: document.getElementById('location').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value,
                quantity: document.getElementById('quantity').value,
                amount: document.getElementById('amount').value,
                established_at: document.getElementById('established_at').value,
            };
            console.log(data);
            const method = id ? 'put' : 'post';
            const url = id ? `/api/assets/${id}` : '/api/assets';

            axios[method](url, data)
                .then(res => {
                    notyf.success(res.data.message || 'Data berhasil disimpan');
                    assetModal.hide();
                    loadAssets();
                })
                .catch(err => {
                    if (err.response?.data?.errors) {
                        Object.values(err.response.data.errors).forEach(msgs => notyf.error(msgs[0]));
                    } else {
                        notyf.error(err.response?.data?.message || 'Terjadi kesalahan');
                    }
                });
        });

        function deleteAsset(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Aset yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/assets/${id}`)
                        .then(res => {
                            notyf.success(res.data.message || 'Aset berhasil dihapus');
                            loadAssets();
                        })
                        .catch(() => {
                            notyf.error('Gagal menghapus aset');
                        });
                }
            });
        }

        loadAssets();

        document.getElementById('price').addEventListener('input', updateAmount);
        document.getElementById('quantity').addEventListener('input', updateAmount);

        function updateAmount() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const qty = parseInt(document.getElementById('quantity').value) || 0;
            document.getElementById('amount').value = (price * qty).toFixed(2);
        }

        document.addEventListener("DOMContentLoaded", function () {
            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    let select = document.getElementById('category_id');
                    select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                    data.forEach(cat => {
                        let opt = document.createElement('option');
                        opt.value = cat.id;
                        opt.textContent = cat.name;
                        select.appendChild(opt);
                    });
                })
                .catch(() => {
                    alert('Gagal memuat kategori');
                });
        });
    </script>
</body>
</html>
