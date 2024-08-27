<?php

namespace App\Http;

use Lib\Validator;

/**
 * Clase Request
 *
 * Esta clase maneja las solicitudes HTTP, incluyendo datos de formularios, archivos subidos,
 * métodos de solicitud, validación y encabezados.
 */
class Request
{
    use RequestData;
    use RequestFiles;
    use RequestMethods;
    use RequestValidation;
    use RequestHeaders;

    protected $data;
    protected $files;
    protected $errors = [];

    /**
     * Constructor de la clase Request
     *
     * Inicializa una nueva instancia de la clase Request, combinando los datos de $_GET y $_POST
     * en la propiedad $data y asignando los archivos subidos de $_FILES a la propiedad $files.
     */
    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST);
        $this->files = $_FILES;
    }
}
