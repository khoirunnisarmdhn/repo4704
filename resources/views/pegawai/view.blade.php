<!-- Menghubungkan dengan view template layout2 -->
@extends('layout2')

@section('konten')
 
<h1>Data Pegawai</h1>
 
<ul>
	@foreach($pegawai as $p)
		<li>{{ "ID Pegawai : ". $p->id_pegawai . ' | Nama Pegawai : ' . $p->nama_pegawai }}</li>
	@endforeach
</ul>
 
@endsection