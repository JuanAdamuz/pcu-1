<?php
/**
 * Copyright (c) 2017. Apecengo
 * Todos los derechos reservados.
 * No se permite la copia, distribución o reproducción por ningún medio.
 * Para más información sobre usos permitidos, ver el archivo LICENSE.md.
 */

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
