# Livewire DataTables

A [Laravel Livewire](https://laravel-livewire.com) table component with searching, sorting, checkboxes, pagination and export data.

With the component you can quickly generate a table from an entity or a collection.

Bootstrap version - Product Model
![Laravel Livewire Tables](examples/bootstrap.png)

Tailwind version - User Model
![Laravel Livewire Tables](examples/example.png)

Exported example with selected data

![Laravel Livewire Tables](examples/export.png)

[See these other examples!](examples)


- [Support](https://github.com/luanfreitasdev/livewire-datatable/issues)
- [Contributions](https://github.com/luanfreitasdev/livewire-datatable/pulls)

# Installation

Installing this package via composer:

    composer require luanfreitasdev/livewire-datatable

Add Providers

    'providers' => [
        ...
        LuanFreitasDev\LivewireDataTables\Providers\DataTableServiceProvider::class,
        ...
    ];

Be sure to enter livewire policies

    @livewireStyle and @livewireScripts

You can use either tailwind or bootstrap

[Install Tailwindcss](https://tailwindcss.com/docs/guides/laravel)

[Bootstrap 5](https://getbootstrap.com/docs/5.0/getting-started/introduction/) 

# Making Table Components

Component generated for an entity

Using the `make` command:

    php artisan make:table --name=ProductTable --model=App\Models\Product

## Options

### `--name`

Name of component table

Example:

    --name=ProductTable

### `--model`

Full model path

Example:

    --model=App\Models\Product

### `--publish`

Publish stubs file into the path 'stubs'

Example:

    php artisan make:table --publish

### `--template`

Sometimes you can use ready-made templates for creating different types of tables

Example:

    php artisan make:table --template=stubs/table.sub or php artisan make:table --template=stubs/table_with_buttons.sub

This creates your new table component in the `app/Http/Livewire` folder.

# Using Table Components

After making a component, you may want to edit the `setUp`, `dataSource`, `columns` and `actions` methods:

    class ProductTable extends DataTableComponent
    {
        use ActionButton;

        public function setUp()
        {
            $this->showCheckBox()
                ->showPerPage()
                ->showSearchInput();
        }
    
        public function dataSource(): User
        {
            return new User();
        }
    
        public function columns(): array
        {
            return [
                Column::add()
                    ->title('Cod')
                    ->field('id')
                    ->searchable()
                    ->sortable()
                    ->html()
                    ->make(),
    
                Column::add()
                    ->title('Nome')
                    ->field('name')
                    ->searchable()
                    ->sortable()
                    ->make(),
    
                Column::add()
                    ->title('Em destaque')
                    ->field('featured')
                    ->searchable()
                    ->sortable()
                    ->make(),
    
                Column::add()
                    ->title('Sub Grupo')
                    ->field('subgroup')
                    ->searchable()
                    ->make(),
    
                Column::add()
                    ->title('Tipo')
                    ->field('type')
                    ->searchable()
                    ->make(),
    
                Column::add()
                    ->title('Situação')
                    ->field('active')
                    ->html()
                    ->className('text-center')
                    ->searchable()
                    ->visibleInExport(false)
                    ->make(),
    
                Column::add()
                    ->title('Situação')
                    ->field('active_export')
                    ->visibleInExport(true)
                    ->make(),
            ];
        }
    
        public function actions(): array
        {
            return [
                Button::add('edit')
                ->i('fa fa-edit', 'Editar')
                ->class('btn btn-primary')
                ->route('companies.products.edit', ['product' => 'id'])
                ->make()
            ];
        }
    }

### `->route(string, array)`

string = route name
array = route parameters, for example route resource: `Route::resource('products', 'ProductController');`

    array example:
        [
            'id' => 'id'
        ] 
    represents:
        [
            'parameter_name' => 'field' (to get value from this column) 
        ]
    in this case we will have:
        [
            'id' => 1
        ]


To generate from a collection, update dataSource method to

    public function dataSource(): Collection
    {
        $products = Products::query()->with('group')->get();
        $data = DataTable::eloquent($products)
            ->addColumn('id', function(Products $product) {
                return $product->id;
            })
            ->addColumn('name', function(Products $product) {
                return $product->name;
            })
            ->addColumn('featured', function(Products $product) {
                return ($product->featured == '') ? 'Não': 'Sim';
            })
            ->addColumn('subgroup', function(Products $product) {
                return $product->group->name;
            })
            ->addColumn('type', function(Products $product) {
                return $product->productType();
            })
            ->addColumn('active', function(Products $product) {
                return $product->hasActive();
            })
            ->addColumn('active_export', function(Products $product) {
            if ($product->active == 1) {
                return 'Ativo';
            }
            return 'Inativo';
        })
        ->make();
        return new Collection($data);
       
    }

Remember, each element must be an object within the array

To show tailwind version add  method `tailwind()` into setUp method:


    public function setUp()
    {
        ->tailwind();
    }

And then call him:

    <livewire:product-table/> ou @livewire('product-table')

# Publishing Files

Publishing files is optional.

Publishing the table view files:

    php artisan vendor:publish --tag=datatable-views
