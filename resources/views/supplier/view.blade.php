<!-- Menghubungkan dengan view template layout2 -->
@extends('layout2')

@section('konten')
 
<h1>Data Supplier</h1>
 
<ul>
	@foreach($pegawai as $p)
		<li>{{ "Kode Supplier : ". $p->Kode_supplier . ' | Nama Supplier : ' . $p->Nama_supplier }}</li>
	@endforeach
</ul>
 
@endsection