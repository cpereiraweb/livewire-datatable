<?php


namespace LuanFreitasDev\LivewireDataTables;

use Illuminate\Support\Collection;

class DataTable
{

    private Collection $model;
    private array $data = [];
    private array $columns = [];

    public function __construct(Collection $model)
    {
        $this->model = $model;
    }

    /**
     * @param Collection $model
     * @return DataTable
     */
    public static function eloquent(Collection $model): DataTable
    {
        return new static($model);
    }

    /**
     * @param string $field
     * @param \Closure $closure
     * @return $this
     */
    public function addColumn(string $field, \Closure $closure): DataTable
    {
        $this->columns[$field] = $closure;
        return $this;
    }

    /**
     * @return array
     */
    public function make(): array
    {
        $this->model->map(function ($row, $index) {
            foreach ($this->columns as $field => $closure) {
                $this->data[$index][$field] = $closure($row);
            }
        });
        return $this->prepareData();
    }

    /**
     * @return array
     */
    private function prepareData(): array
    {
        $data = [];
        foreach ($this->data as $obj) {
            $data[$obj['id']] = (object) $obj;
        }
        return $data;
    }

}
