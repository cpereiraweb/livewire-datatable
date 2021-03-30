<?php

namespace LuanFreitasDev\LivewireDataTables\Helpers;

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CollectionHelper
{
    public static function paginate(Collection $results, $pageSize): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage('page');

        $total = $results->count();

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param Collection $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param array $options
     * @return LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options): LengthAwarePaginator
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }

    public static function prepareFromCollection(\Illuminate\Support\Collection $model, string $search, $columns): Collection
    {
        $data_map = collect([]);
        $data_obj = [];

        if (!empty($search)) {
            $model->each(function ($item) use ($columns, $search, $data_map) {
                foreach ($columns as $key => $value) {
                    $field = $value['field'];
                    if ($value['searchable'] === true) {
                        if (Str::contains(strtolower($item->$field), strtolower($search))) {
                            if (!in_array(strtolower($item->$field), $data_map->toArray())) {
                                $data_map->push($item);
                            }
                        }
                    }
                }
            });

            foreach ($data_map->toArray() as $obj) {
                $data_obj[] = (object)$obj;
            }
            $data_map = collect($data_obj);

        } else {
            $data_map = $model;
        }
        return $data_map;
    }

}
