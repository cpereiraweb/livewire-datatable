### Example 1 - button with fontawesome icon

![Laravel Livewire Tables](example1.png)

    class UserTable extends DataTableComponent
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
                    ->title('ID')
                    ->field('id')
                    ->searchable()
                    ->sortable()
                    ->bodyAttribute('text-center', '')
                    ->make(),
    
                Column::add()
                    ->title('Nome')
                    ->field('name')
                    ->searchable()
                    ->sortable()
                    ->make(),
    
                Column::add()
                    ->title('E-mail')
                    ->field('email')
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
    
        public function actions(): array
        {
            return [
                Button::add('view')
                    ->i('fa fa-eye', 'Visualizar')
                    ->class('btn btn-primary')
                    ->route('user.view', ['id' => 'id'])
                    ->make(),
    
                Button::add('edit')
                    ->i('fa fa-edit', 'Editar')
                    ->class('btn btn-success')
                    ->route('user.edit', ['id' => 'id'])
                    ->make(),
    
                Button::add('delete')
                    ->i('fa fa-trash', 'Excluir')
                    ->class('btn btn-danger')
                    ->route('user.edit', ['id' => 'id'])
                    ->make(),
            ];
        }
    
    }


### Example 2 - with custom html and simple button

![Laravel Livewire Tables](example2.png)

```php
    class UserTable extends DataTableComponent
    {
        use ActionButton;
    
        public function setUp()
        {
            $this->showCheckBox()
                ->showPerPage()                
                ->showSearchInput();
        }

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
        
            public function columns(): array
            {
                return [
                    Column::add()
                        ->title('Cod')
                        ->field('id')
                        ->searchable()
                        ->headerAttribute('text-center', 'width:95px')
                        ->sortable()
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
                        ->sortable()
                        ->searchable()
                        ->make(),
        
                    Column::add()
                        ->title('Tipo')
                        ->field('type')
                        ->searchable()
                        ->sortable()
                        ->make(),
        
                    Column::add()
                        ->title('Situação')
                        ->field('active')
                        ->html()
                        ->bodyAttribute('text-center')
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
                        ->make(),
        
                     Button::add('delete')
                         ->i('fa fa-trash', 'Deletar')
                         ->class('btn btn-danger')
                         ->route('companies.products.edit', ['product' => 'id'])
                         ->make()
                ];
            }
    
    }
```
