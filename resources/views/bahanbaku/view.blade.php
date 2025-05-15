<!-- Menghubungkan dengan view template layout2 -->
@extends('layout2')

@section('konten')
 
<h1>Data Bahanbaku</h1>
 
<ul>
	@foreach($bahanbaku as $p)
		<li>{{ "Kode Bahanbaku : ". $p->kode_bahanbaku . ' | Nama Bahanbaku : ' . $p->nama_bahanbaku }}</li>
	@endforeach
</ul>
 
@endsection