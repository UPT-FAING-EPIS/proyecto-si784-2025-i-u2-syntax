<?php

abstract class BaseController
{
    public function handle($accion)
    {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET' && method_exists($this, $accion . 'Get')) {
            return $this->{$accion . 'Get'}();
        }

        if ($metodo === 'POST' && method_exists($this, $accion . 'Post')) {
            return $this->{$accion . 'Post'}();
        }

        http_response_code(404);
        echo "
            <div style='padding: 2rem; font-family: sans-serif; text-align: center;'>
                <h2 style='color: red;'>Página no encontrada o método no permitido</h2>
                <p>La acción <strong>'$accion'</strong> no está disponible en este controlador.</p>
                <p><a href='javascript:history.back()' style='color: blue; text-decoration: underline;'>Volver a la página anterior</a></p>
            </div>
            <script>
                setTimeout(() => {
                    history.back();
                }, 5000);
            </script>
        ";
        exit;
    }
}
