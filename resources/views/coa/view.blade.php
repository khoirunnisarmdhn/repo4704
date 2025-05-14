<!-- Menghubungkan dengan view template layout2 -->
@extends('layout2')

@section('konten')
 
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data CoA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h1 class="text-center mb-4">Data Chart of Accounts (CoA)</h1>

    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
						<th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Header Akun</th>
						<th>Dibuat</th>
						<th>Diedit</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($coa as $p)
                        <tr>
							<td>{{ $p->kode_akun}}</td>
                            <td>{{ $p->nama_akun }}</td>
                            <td>{{ $p->header_akun }}</td>
							<td>{{ $p->created_at }}</td>
							<td>{{ $p->updated_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Tidak ada data CoA tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
 
@endsection