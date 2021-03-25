<?php

namespace LuanFreitasDev\LivewireDataTables;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use LuanFreitasDev\LivewireDataTables\Helpers\CollectionHelper;
use LuanFreitasDev\LivewireDataTables\Traits\Checkbox;
use LuanFreitasDev\LivewireDataTables\Traits\ExportExcel;

class DataTableComponent extends Component
{

    use WithPagination, Checkbox, ExportExcel;

    /**
     * @var
     */
    public $model;
    /**
     * @var array
     */
    public array $headers = [];
    /**
     * @var array
     */
    public array $class = [];
    /**
     * @var bool
     */
    public bool $search_input = true;
    /**
     * @var string
     */
    public string $search = '';
    /**
     * @var bool
     */
    public bool $perPage_input = true;
    /**
     * @var string
     */
    public string $orderBy = 'id';
    /**
     * @var bool
     */
    public bool $orderAsc = false;
    /**
     * @var int
     */
    public int $perPage;
    /**
     * @var array
     */
    private array $searchable = [];
    /**
     * @var array
     */
    private array $badge;
    /**
     * @var array
     */
    public array $columns = [];
    /**
     * @var string
     */
    private string $theme = 'bootstrap';
    /**
     * @var string
     */
    protected string $paginationTheme = 'bootstrap';

    /**
     * @var string
     */
    public string $icon_sort = '';

    /**
     * @var array|int[]
     */
    public array $perPageValues = [10,25,50,100];

    /**
     * @return $this
     */
    public function tailwind(): DataTableComponent {
        $this->theme = 'tailwind';
        return $this;
    }

    /**
     * Apply checkbox, perPage and search view and theme
     *
     */
    public function setUp()
    {
        $this->showCheckBox()
            ->showPerPage()
            ->showSearchInput();
    }

    /**
     * @return $this
     * Show search input into component
     */
    public function showSearchInput(): DataTableComponent
    {
        $this->search_input = true;
        return $this;
    }

    /**
     * @param int $perPage
     * @return $this
     */
    public function showPerPage(int $perPage=10): DataTableComponent
    {
        if (\Str::contains($perPage, [10,25,50,100])) {
            $this->perPage_input = true;
            $this->perPage = $perPage;
        }
        return $this;
    }

    public function mount() {

        $this->icon_sort = 'M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z';
        $this->columns = $this->columns();

        if (empty($this->model)) {
            $this->model = $this->dataSource();
        }
        $this->setUp();
        $this->paginationTheme = $this->theme;

        if (method_exists($this,'initActions')) {
            $this->initActions();
        }
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::add()
                ->title('ID')
                ->field('id')
                ->searchable()
                ->sortable()
                ->make(),

            Column::add()
                ->title('Criado em')
                ->field('created_at')
                ->searchable()
                ->make(),
        ];
    }

    public function dataSource() {
        return null;
    }

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        $this->model   = $this->dataSource();
        $collect_data  = collect([]);
        $data          = [];
        $searchable    = collect($this->columns)->pluck('searchable')->toArray();

        if ($this->model != null) {
            if(is_a($this->model, 'Illuminate\Support\Collection')) {

                if (!empty($this->search)) {
                    $this->model->each(function ($item) use ($searchable, $collect_data) {
                        foreach ($searchable as $key => $value) {
                            $field = $this->columns[$key]['field'];

                            if (Str::contains(strtolower($item->$field), strtolower($this->search))){
                                if (!in_array(strtolower($item->$field), $collect_data->toArray())) {
                                    $collect_data->push($item);
                                }
                            }
                        }
                    });

                    foreach ($collect_data->toArray() as $obj) {
                        $data[] = (object) $obj;
                    }
                    $collect_data = collect($data);

                } else {
                    $collect_data = $this->model;
                }

                $collect_data = $collect_data->sortBy($this->orderBy, SORT_REGULAR, $this->orderAsc);
                $collect_data = CollectionHelper::paginate($collect_data, $this->perPage);

            } else {

                $data = $this->model->where('id', 'like', '%'.$this->search.'%');

                foreach ($searchable as $key => $value) {
                    if (Str::contains($this->columns[$key]['field'], Schema::connection(config('database.default'))->getColumnListing($this->model->getTable()))) {
                        $data->orWhere($this->columns[$key]['field'], 'like', '%' . $this->search . '%');
                    }
                }

                $data = $data->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');

                $data = $data->paginate($this->perPage);

            }
        }

        return view('livewire-datatables::'.$this->theme.'.table', [
            'data' => (count($data)) ? $data : $collect_data
        ]);
    }

    /**
     * @param $field
     */
    public function setOrder ($field) {
        if (!empty($field)) {
            $this->orderBy = $field;
            if ($this->orderAsc === true) {
                $this->orderAsc = false;
                $this->icon_sort = 'M3.5 3.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 12.293V3.5zm4 .5a.5.5 0 0 1 0-1h1a.5.5 0 0 1 0 1h-1zm0 3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1h-3zm0 3a.5.5 0 0 1 0-1h5a.5.5 0 0 1 0 1h-5zM7 12.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5z';
            } else {
                $this->orderAsc = true;
                $this->icon_sort = 'M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z';
            }
        }
    }

}
