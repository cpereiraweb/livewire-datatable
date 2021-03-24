<?php


namespace LuanFreitasDev\LivewireDataTables;

class Column
{

    public array $column = [];

    /**
     * @return static
     */
    public static function add()
    {
        return new static();
    }

    /**
     * Column title representing a field
     *
     * @param string $title
     * @return $this
     */
    public function title(string $title): Column
    {
        $this->column['title'] = $title;
        return $this;
    }

    /**
     * Will enable the column for search
     *
     * @return $this
     */
    public function searchable(): Column
    {
        $this->column['searchable'] = true;
        return $this;
    }

    /**
     * Will enable the column for sort
     *
     * @return $this
     */
    public function sortable(): Column
    {
        $this->column['sortable'] = true;
        return $this;
    }

    /**
     * Field name in the database
     *
     * @param string $field
     * @return $this
     */
    public function field(string $field): Column
    {
        $this->column['field'] = $field;
        return $this;
    }

    /**
     * Class tag in html
     *
     * @param string $class
     * @return $this
     */
    public function className(string $class): Column
    {
        $this->column['class'] = $class;
        return $this;
    }

    /**
     * When the field has any changes within the scope using Collection
     *
     * @return $this
     */
    public function html(): Column
    {
        $this->column['html'] = true;
        $this->column['sortable'] = false;
        return $this;
    }

    public function visibleInExport(bool $visible): Column
    {
        $this->column['visible_in_export'] = $visible;
        return $this;
    }

    public function make(): array
    {
        return $this->column;
    }

}
