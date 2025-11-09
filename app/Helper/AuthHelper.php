<?php

if (!function_exists('usuario_autenticado')) {
    function usuario_autenticado()
    {
        return session('usuario') !== null;
    }
}

if (!function_exists('es_admin')) {
    function es_admin()
    {
        $usuario = session('usuario');
        return $usuario && $usuario['rol'] === 'admin';
    }
}

if (!function_exists('obtener_usuario')) {
    function obtener_usuario()
    {
        return session('usuario');
    }
}