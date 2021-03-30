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
use LuanFreitasDev\LivewireDataTables\Traits\Filter;

class DataTableComponent extends Component
{

    use WithPagination, Checkbox, ExportExcel, Filter;

    /**
     * @var
     */
    private $model;
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
     * @var array
     */
    public array $icon_sort = [];

    /**
     * @var array|int[]
     */
    public array $perPageValues = [10, 25, 50, 100];

    protected $listeners = [
        'pikerFilter' => 'pikerFilter'
    ];

    /**
     * @return $this
     */
    public function tailwind(): DataTableComponent
    {
        $this->theme = 'tailwind';
        return $this;
    }

    public function pikerFilter($data)
    {

        $input = explode('.', $data[0]['values']);
        $this->filters->put($input[2], $data[0]['selectedDates']);

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
    public function showPerPage(int $perPage = 10): DataTableComponent
    {
        if (\Str::contains($perPage, $this->perPageValues)) {
            $this->perPage_input = true;
            $this->perPage = $perPage;
        }
        return $this;
    }

    public function mount()
    {

        $this->columns = $this->columns();

        $this->renderFilter();

        $this->headerIcons();

        if (empty($this->model)) {
            $this->model = $this->dataSource();
        }

        $this->setUp();

        $this->paginationTheme = $this->theme;

        if (method_exists($this, 'initActions')) {
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
                ->make(),
        ];
    }

    public function dataSource()
    {
        return null;
    }

    /**
     * @return Application|Factory|View
     */
    public function render(): Factory|View|Application
    {
        $this->model = $this->dataSource();
        $data = [];

        if (filled($this->model)) {

            if (is_a($this->model, 'Illuminate\Support\Collection')) {

                $prepare = CollectionHelper::prepareFromCollection($this->model, $this->search, $this->columns());
                $filtered = $this->prepareFilter($prepare);

                $hydrate_data = $filtered->sortBy($this->orderBy, SORT_REGULAR, $this->orderAsc);
                $data = CollectionHelper::paginate($hydrate_data, $this->perPage);

            } else {

                $data = $this->model->where('id', 'like', '%' . $this->search . '%');

                if (!$this->filter_action) {

                    foreach ($this->columns() as $key => $value) {
                        $field = $value['field'];
                        if ($value['searchable'] === true) {
                            if (Str::contains($field, Schema::connection(config('database.default'))
                                ->getColumnListing($this->dataSource()->getTable()))) {
                                $filter = $this->columns[$key]['field'];
                                $data->orWhere($filter, 'like', '%' . $this->search . '%');
                            }
                        }
                    }

                } else {

                    $data = $this->prepareFilter($data);

                }

                $data = $data->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');

                $data = $data->paginate($this->perPage);

            }
        }

        return view('livewire-datatables::' . $this->theme . '.table', [
            'data' => $data
        ]);
    }

    /**
     * @param $field
     */
    public function setOrder($field)
    {
        if (!empty($field)) {
            $this->orderBy = $field;
            $this->headerIcons();
            if ($this->orderAsc === true) {
                $this->orderAsc = false;
                $this->icon_sort[$field] = 'M3.5 3.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 12.293V3.5zm4 .5a.5.5 0 0 1 0-1h1a.5.5 0 0 1 0 1h-1zm0 3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1h-3zm0 3a.5.5 0 0 1 0-1h5a.5.5 0 0 1 0 1h-5zM7 12.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5z';
            } else {
                $this->orderAsc = true;
                $this->icon_sort[$field] = 'M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z';
            }
        }
    }

    private function headerIcons()
    {
        foreach ($this->columns as $column) {
            $this->icon_sort[$column['field']] = 'M3.5 3.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 12.293V3.5zm4 .5a.5.5 0 0 1 0-1h1a.5.5 0 0 1 0 1h-1zm0 3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1h-3zm0 3a.5.5 0 0 1 0-1h5a.5.5 0 0 1 0 1h-5zM7 12.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5z';
        }
    }

}
