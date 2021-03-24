<?php


namespace LuanFreitasDev\LivewireDataTables\Traits;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use LuanFreitasDev\LivewireDataTables\DataTableComponent;

trait Checkbox
{

    /**
     * @var bool
     */
    public bool $checkbox = false;
    /**
     * @var bool
     */
    public bool $checkbox_all = false;
    /**
     * @var array
     */
    public array $checkbox_values = [];
    /**
     * @var string
     */
    public string $checkbox_attribute;

    /**
     * @param string $attribute
     * @return DataTableComponent
     */
    public function showCheckBox(string $attribute='id'): DataTableComponent
    {
        if(is_a($this->model, 'Illuminate\Support\Collection')) {
            $this->checkbox = true;
            $this->checkbox_attribute = 'id';
        } else {
            if ($this->model != null) {
                if (Str::contains($attribute, Schema::connection(config('database.default'))->getColumnListing($this->model->getTable()))) {
                    $this->checkbox = true;
                    $this->checkbox_attribute = $attribute;
                }
            }

        }
        return $this;
    }

    public function updatedCheckboxAll()
    {
        $this->checkbox_values = [];

        if ($this->checkbox_all) {
            $this->dataSource()->each(function ($model) {
                $this->checkbox_values[] = (string)$model->{$this->checkbox_attribute};
            });
        }
    }

}
