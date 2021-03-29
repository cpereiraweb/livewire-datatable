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
        $this->column['html'] = false;
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
     * Class html tag header table
     *
     * @param string $class
     * @param string|null $style
     * @return $this
     */
    public function headerAttribute(string $class, string $style=null): Column
    {
        $this->column['header_class'] = $class;
        $this->column['header_style'] = $style;
        return $this;
    }

    /**
     * Class html tag body table
     *
     * @param string $class
     * @param string|null $style
     * @return $this
     */
    public function bodyAttribute(string $class, string $style=null): Column
    {
        $this->column['body_class'] = $class;
        $this->column['body_style'] = $style;
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
        $this->column['searchable'] = false;
        return $this;
    }


    /**
     * Add the @datatableFilter directive before the body
     *
     * @param string $class
     * @return $this
     */
    public function filterDateBetween(string $class=''): Column
    {
        $this->column['filter_date_between'] = [
            'enabled' => true,
            'class' => (blank($class)) ? 'col-3': $class
        ];
        return $this;
    }

    public function make(): array
    {
        return $this->column;
    }


}
