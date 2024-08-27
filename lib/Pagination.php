<?php

namespace Lib;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Lib\PaginationConfig;

class Pagination
{
    /**
     * Pagina los resultados de una consulta.
     *
     * @param Builder|array $query La consulta Eloquent o un array de resultados de una consulta SQL directa.
     * @param PaginationConfig $config La configuración de la paginación.
     * @return array Un arreglo asociativo con los resultados paginados y la información de paginación.
     *
     * Este método determina si la consulta es una instancia de Eloquent Builder o un array,
     * y llama al método correspondiente para paginar los resultados.
     */
    public static function paginate($query, PaginationConfig $config)
    {
        if ($query instanceof Builder) {
            return self::paginateEloquent($query, $config);
        } else {
            return self::paginateArray($query, $config);
        }
    }

    /**
     * Pagina los resultados de una consulta Eloquent.
     *
     * @param Builder $query La consulta Eloquent.
     * @param PaginationConfig $config La configuración de la paginación.
     * @return array Un arreglo asociativo con los resultados paginados y la información de paginación.
     *
     * Este método aplica los filtros de búsqueda y adicionales, ordena los resultados,
     * y devuelve los resultados paginados.
     */
    private static function paginateEloquent(Builder $query, PaginationConfig $config)
    {
        if (!empty($config->search)) {
            $query->where($config->campo, 'LIKE', '%' . $config->search . '%');
        }

        self::applyAdditionalFilters($query, $config->additionalFilters);

        $query->orderBy($config->orderBy, $config->orderDirection);

        $total = $query->count();
        $offset = ($config->page - 1) * $config->perPage;
        $items = $query->skip($offset)->take($config->perPage)->get();

        return self::formatResult($items, $total, $config);
    }

    /**
     * Pagina los resultados de un array.
     *
     * @param array $query Un array de resultados de una consulta SQL directa.
     * @param PaginationConfig $config La configuración de la paginación.
     * @return array Un arreglo asociativo con los resultados paginados y la información de paginación.
     *
     * Este método calcula el total de resultados, aplica la paginación,
     * y devuelve los resultados paginados.
     */
    private static function paginateArray(array $query, PaginationConfig $config)
    {
        $total = count($query);
        $offset = ($config->page - 1) * $config->perPage;
        $items = array_slice($query, $offset, $config->perPage);

        return self::formatResult($items, $total, $config);
    }

    /**
     * Aplica filtros adicionales a la consulta Eloquent.
     *
     * @param Builder $query La consulta Eloquent.
     * @param array $additionalFilters Filtros adicionales para aplicar a la consulta.
     *
     * Este método recorre los filtros adicionales y los aplica a la consulta Eloquent.
     */
    private static function applyAdditionalFilters(Builder $query, array $additionalFilters)
    {
        foreach ($additionalFilters as $filter) {
            if (isset($filter['campo']) && isset($filter['operador']) && isset($filter['valor']) && !empty($filter['valor'])) {
                $query->where($filter['campo'], $filter['operador'], $filter['valor']);
            }
        }
    }

    /**
     * Formatea los resultados de la paginación.
     *
     * @param array $items Los elementos paginados.
     * @param int $total El número total de resultados.
     * @param PaginationConfig $config La configuración de la paginación.
     * @return array Un arreglo asociativo con los resultados paginados y la información de paginación.
     *
     * Este método calcula el número total de páginas y devuelve un arreglo con los resultados paginados
     * y la información de paginación.
     */
    private static function formatResult($items, $total, PaginationConfig $config)
    {
        $totalPages = ceil($total / $config->perPage);

        return [
            'items' => $items,
            'total' => $total,
            'currentPage' => $config->page,
            'totalPages' => $totalPages,
            'search' => $config->search,
            'orderBy' => $config->orderBy,
            'orderDirection' => $config->orderDirection
        ];
    }
}
