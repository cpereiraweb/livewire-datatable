<div>
    <div class="">
        <div class="col-md-12" style="margin: 10px 0 10px;">
            <button class="btn btn-success" wire:click="exportToExcel()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                     class="bi bi-file-earmark-excel" viewBox="0 0 16 16">
                    <path
                        d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
                    <path
                        d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                </svg>
                {!! (count($checkbox_values)) ? 'Exportar selecionados': 'Exportar todos' !!}
            </button>
        </div>

        <div class="col-md-12">
            @include('livewire-datatables::bootstrap.search-per-page')
        </div>

        @if(count($make_filters) > 0)
            <div class="col-md-12">
                @include('livewire-datatables::bootstrap.filter')
            </div>
        @endif

        @include('livewire-datatables::assets.style')
        <div class="table-responsive col-md-12" style="margin: 10px 0 10px;">

            @if(count($data) == 0)
                <div class="alert alert-warning text-center">
                    Nenhum resultado encontrado
                    @if($filter_action === true)
                        <span wire:click.prevent="clearFilter()" style="font-weight: bold; cursor: pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-x" viewBox="0 0 16 16">
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>

                            Limpar filtro
                        </span>

                    @endif
                </div>
            @endif
            @if(count($data) > 0)
                <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                    <thead>
                    <tr>

                        @include('livewire-datatables::bootstrap.checkbox-all')

                        @foreach($columns as $column)

                            @if(!isset($column['visible_in_export']) || $column['visible_in_export'] === false)
                                <th @if(isset($column['sortable']) === true) wire:click="setOrder('{{$column['field']}}')"
                                    @endif
                                    class="{{(isset($column['header_class'])? $column['header_class']: "")}}"
                                    style="cursor:pointer;{{(isset($column['header_style'])? $column['header_style']: "")}}"
                                >
                                    @if(isset($column['sortable']) === true)
                                        <svg style="margin-top: -3px;" xmlns="http://www.w3.org/2000/svg" width="16"
                                             height="16" fill="currentColor" class="bi bi-sort-up"
                                             viewBox="0 0 16 16">
                                            <path d="{{$icon_sort[$column['field']]}}"/>
                                        </svg>
                                    @endif
                                    {{$column['title']}}
                                </th>
                            @endif

                        @endforeach
                        @if(isset($actionBtns) && count($actionBtns))
                            <th class="text-center">Ações</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($data as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 ">

                            @include('livewire-datatables::bootstrap.checkbox-row')

                            @foreach($columns as $column)
                                @php
                                    $field = $column['field'];
                                @endphp
                                @if(!isset($column['visible_in_export']) || ($column['visible_in_export'] === false))
                                    <td class="{{(isset($column['body_class'])? $column['body_class']: "")}}"
                                        style="{{(isset($column['body_style'])? $column['body_style']: "")}}"
                                    >
                                        @if(isset($column['html']))
                                            {!! $row->$field !!}
                                        @elseif(!isset($column['html']))
                                            {{$row->$field}}
                                        @endif
                                    </td>
                                @endif
                            @endforeach

                            @include('livewire-datatables::bootstrap.actions')
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                @if(!is_array($data))
                    <nav aria-label="">
                        @if(method_exists($data, 'links'))
                            {!! $data->links() !!}
                        @endif
                    </nav>
                @endif

            @endif

        </div>
    </div>

</div>

<style>
    table {
        width: 100%;
    }

    .table .checkbox-column {
        width: 50px !important;
        max-width: 50px !important;
        text-align: center;
    }
</style>



