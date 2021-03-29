<?php


namespace LuanFreitasDev\LivewireDataTables;

class Filter
{

    private array $filter = [];

    /**
     * Button constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return Filter
     */
    public static function add(): Filter
    {
        return new static();
    }

    /**
     * @return array
     */
    public function make(): array {
        return $this->button;
    }
}
