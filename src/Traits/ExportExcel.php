<?php


namespace LuanFreitasDev\LivewireDataTables\Traits;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait ExportExcel
{
    public function exportToExcel(): BinaryFileResponse
    {

        $data          = $this->dataSource();
        $except        = [];
        $title         = [];
        $headers       = [];
        $file_name     = 'excel';

        if (count($this->checkbox_values)) {
            $data = $data->whereIn('id', $this->checkbox_values);
        }

        foreach ($this->columns as $column) {
            if (!isset($column['visible_in_export'])) {
                $title[] = $column['title'];
            } else {
                if ($column['visible_in_export'] === true) {
                    $title[] = $column['title'];
                } else {
                    $except[] = $column['field'];
                }
            }
        }
        $headers[] = $title;

        if(is_a($this->model, 'Illuminate\Support\Collection')) {

            $data = $data->map(function ($item) use ($except) {
                $item = collect($item);
                return $item->except($except)->toArray();
            });
            $build_xlsx = \SimpleXLSXGen::fromArray(array_merge($headers, $data->toArray()), $file_name);

        } else {

            $file_name  = strtolower($this->model->getTable());
            $build_xlsx = \SimpleXLSXGen::fromArray(array_merge($headers, $data->get()->toArray()), $file_name);

        }

        Storage::disk('public')
            ->put($file_name.'_export.xlsx', $build_xlsx);

        return response()
            ->download(storage_path("app/public/".$file_name.'_export.xlsx'));
    }
}
