<!DOCTYPE html>
<html lang="en">
<head>
    <!-- masukkan header dari layouts -> header.blade -->
    @include('layouts.header')
</head>
<body>
    Halo {{ $nama }}, {{ $title }}
    <hr>
    
    <!-- Masukkan body dari layouts -> body.blade -->
    @include('layouts.body')

    <hr>
    <!-- masukkan footer dari layouts -> footer.blade -->
    @include('layouts.footer')
</body>
</html>