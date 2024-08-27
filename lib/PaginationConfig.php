<?php

namespace Lib;

class PaginationConfig
{
    public $campo;
    public $perPage;
    public $page;
    public $search;
    public $orderBy;
    public $orderDirection;
    public $additionalFilters;

    /**
     * Constructor de la clase PaginationConfig.
     *
     * @param string $campo El campo en el que se realizará la búsqueda.
     * @param int $perPage El número de resultados por página (por defecto es 10).
     * @param int|null $page El número de la página actual (por defecto es 1).
     * @param string $search El término de búsqueda (por defecto es una cadena vacía).
     * @param string $orderBy El campo por el cual se ordenarán los resultados (por defecto es 'id').
     * @param string $orderDirection La dirección de la ordenación, puede ser 'asc' o 'desc' (por defecto es 'asc').
     * @param array $additionalFilters Filtros adicionales para aplicar a la consulta.
     *
     * Este constructor inicializa los parámetros de configuración para la paginación.
     * Si no se proporcionan algunos parámetros, se utilizan valores por defecto.
     * Los parámetros `page`, `search`, `orderBy` y `orderDirection` pueden ser sobreescritos por valores en la URL.
     */
    public function __construct(
        $campo,
        $perPage = 10,
        $page = null,
        $search = '',
        $orderBy = 'id',
        $orderDirection = 'asc',
        $additionalFilters = []
    ) {
        $this->campo = $campo;
        $this->perPage = $perPage;

        // Extraer operaciones ternarias anidadas en declaraciones independientes
        $this->page = $page;
        if ($this->page === null) {
            $this->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        }

        $this->search = $search;
        if (empty($this->search)) {
            $this->search = isset($_GET['search']) ? $_GET['search'] : '';
        }

        $this->orderBy = $orderBy;
        if (empty($this->orderBy)) {
            $this->orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : 'id';
        }

        $this->orderDirection = $orderDirection;
        if (empty($this->orderDirection)) {
            $this->orderDirection = isset($_GET['order_direction']) ? $_GET['order_direction'] : 'asc';
        }

        $this->additionalFilters = $additionalFilters;
    }
}
