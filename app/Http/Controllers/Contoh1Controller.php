<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;

class Contoh1Controller extends Controller
{
    public static function show()
    {
        return "Halo, ini adalah contoh kontroller sederhana tanpa view";
    }
}