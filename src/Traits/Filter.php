<?php


namespace LuanFreitasDev\LivewireDataTables\Traits;


use Carbon\Carbon;
use Illuminate\Support\Collection;

trait Filter
{

    public Collection $make_filters;

    public Collection $filters;

    public bool $filter_action = false;

    private string $format_date = '';

    public function filter()
    {
        $this->filter_action = true;
    }

    public function clearFilter()
    {
        $this->filter_action = false;
    }

    private function renderFilter()
    {

        $this->filters = collect([]);
        $make_filters = [];

        foreach ($this->columns as $column) {
            if (isset($column['filter_date_between']['enabled'])) {
                $make_filters['filter_date_between'][$column['field']]['label'] = $column['title'];
                $make_filters['filter_date_between'][$column['field']]['class'] = $column['filter_date_between']['class'];
            }
        }

        $this->make_filters = collect($make_filters);

    }

    public function formatDate(string $format = ''): string
    {
        if ($this->format_date === '') {
            return 'Y-m-d H:i:s';

        }
        if ($format === '') {
            return $this->format_date;
        }

        $this->format_date = $format;
        return $format;
    }

    private function prepareFilter($data)
    {
        if ($this->filter_action) {
            foreach ($this->filters as $field => $value) {
                $date = explode('até', $value);
                if (isset($date[1]) && filled($date[0]) && filled($date[1])) {

                    $from = Carbon::createFromFormat('d/m/Y H:i', trim($date[0]))->format($this->formatDate());
                    $to = Carbon::createFromFormat('d/m/Y H:i', trim($date[1]))->format($this->formatDate());

                    $data = $data->whereBetween($field, [$from, $to]);

                }
            }
        }
        return $data;
    }


}
