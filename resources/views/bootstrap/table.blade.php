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
                        @if($checkbox)
                            <th class="checkbox-column">
                                <label class="new-control new-checkbox checkbox-primary" id="new-control"
                                       style="height: 18px; margin: 0 auto;">
                                    <input type="checkbox" class="new-control-input" wire:model="checkbox_all">
                                    <span class="new-control-indicator"></span>
                                </label>
                            </th>
                        @endif
                        @foreach($columns as $column)
                            @if(isset($column['sortable']))
                                @if ($column['sortable'] === true)
                                    @if(!isset($column['visible_in_export']))
                                        <th wire:click="setOrder('{{$column['field']}}')"
                                            class="{{(isset($column['header_class'])? $column['header_class']: "")}}"
                                            style="cursor:pointer;{{(isset($column['header_style'])? $column['header_style']: "")}}"
                                        >
                                            <svg style="margin-top: -3px;" xmlns="http://www.w3.org/2000/svg" width="16"
                                                 height="16" fill="currentColor" class="bi bi-sort-up"
                                                 viewBox="0 0 16 16">
                                                <path d="{{$icon_sort[$column['field']]}}"/>
                                            </svg>
                                            {{$column['title']}}</th>
                                    @elseif($column['visible_in_export'] === false)
                                        <th wire:click="setOrder('{{$column['field']}}')"
                                            class="{{(isset($column['header_class'])? $column['header_class']: "")}}"
                                            style="cursor:pointer;{{(isset($column['header_style'])? $column['header_style']: "")}}"
                                        >
                                            <svg style="margin-top: -3px;" xmlns="http://www.w3.org/2000/svg" width="16"
                                                 height="16" fill="currentColor" class="bi bi-sort-up"
                                                 viewBox="0 0 16 16">
                                                <path d="{{$icon_sort[$column['field']]}}"/>
                                            </svg>
                                            {{$column['title']}}</th>
                                    @endif

                                @else
                                    @if(!isset($column['visible_in_export']))
                                        <th class="{{(isset($column['header_class'])? $column['header_class']: "")}}"
                                            style="{{(isset($column['header_style'])? $column['header_style']: "")}}"
                                        >{{$column['title']}}</th>
                                    @elseif($column['visible_in_export'] === false)
                                        <th class="{{(isset($column['header_class'])? $column['header_class']: "")}}"
                                            style="{{(isset($column['header_style'])? $column['header_style']: "")}}"
                                        >{{$column['title']}}</th>
                                    @endif
                                @endif
                            @else
                                @if(!isset($column['visible_in_export']))
                                    <th class="{{(isset($column['header_class'])? $column['header_class']: "")}}">{{$column['title']}}</th>
                                @elseif($column['visible_in_export'] === false)
                                    <th class="{{(isset($column['header_class'])? $column['header_class']: "")}}">{{$column['title']}}</th>
                                @endif
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
                            @if($checkbox)
                                <td class="checkbox-column">
                                    <label class="new-control new-checkbox checkbox-primary"
                                           style="height: 18px; margin: 0 auto;">
                                        <input type="checkbox" wire:model="checkbox_values"
                                               class="new-control-input todochkbox" id="todo-1"
                                               value="{{ $row->{$checkbox_attribute} }}">
                                        <span class="new-control-indicator"></span>
                                    </label>
                                </td>
                            @endif
                            @foreach($columns as $column)
                                @php
                                    $field = $column['field'];
                                @endphp

                                @if(isset($column['html']))
                                    @if(!isset($column['visible_in_export']))
                                        <td class="{{(isset($column['body_class'])? $column['body_class']: "")}}">
                                            {!! $row->$field !!}
                                        </td>
                                    @elseif($column['visible_in_export'] === false)
                                        <td class="{{(isset($column['body_class'])? $column['body_class']: "")}}">
                                            {!! $row->$field !!}
                                        </td>
                                    @endif
                                @else
                                    @if(!isset($column['visible_in_export']))
                                        <td class="{{(isset($column['body_class'])? $column['body_class']: "")}}"
                                            style="{{(isset($column['body_style'])? $column['body_style']: "")}}"
                                        >{{$row->$field}}</td>
                                    @elseif($column['visible_in_export'] === false)
                                        <td class="{{(isset($column['body_class'])? $column['body_class']: "")}}"
                                            style="{{(isset($column['body_style'])? $column['body_style']: "")}}"
                                        >{{$row->$field}}</td>
                                    @endif
                                @endif
                            @endforeach
                            @if(isset($actionBtns) && count($actionBtns))
                                <td class="text-center">
                                    <ul class="table-controls">
                                        @foreach($actionBtns as $action)
                                            @php
                                                $parameters = [];
                                                foreach ($action['param'] as $param => $value) {
                                                    $parameters[$param] = $row->$value;
                                                }
                                            @endphp
                                            <li>
                                                @if(isset($action['caption']))
                                                    <button type="button" id="actionCall"
                                                            wire:click="actionCall('{{$action['action']}}','{{ json_encode($parameters)}}')"
                                                            class="{{$action['class']}}">
                                                        {{ $action['caption'] }}
                                                    </button>
                                                @endif
                                                @if(isset($action['i']))
                                                    <button type="button" id="actionCall"
                                                            wire:click="actionCall('{{$action['action']}}','{{ json_encode($parameters) }}')"
                                                            class="{{$action['class']}}">
                                                        <i class="{{$action['i']['class']}}"
                                                           title="{{$action['i']['text']}}"></i> {{($action['i']['caption'])? $action['i']['text']: ""}}
                                                    </button>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            @endif
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



