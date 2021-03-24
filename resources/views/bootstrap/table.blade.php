<div>
    <div class="">
        <div class="col-md-12" style="margin: 10px 0 10px;">
            <button class="btn btn-success" wire:click="exportToExcel()">
                {{ (count($checkbox_values)) ? 'Exportar selecionados': 'Exportar todos' }}
            </button>
        </div>

        <div class="col-md-12">
            @include('livewire-datatables::bootstrap.search-per-page')
        </div>

        @include('livewire-datatables::assets.style')
        <div class="table-responsive col-md-12" style="margin: 10px 0 10px;">

            <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                <thead>
                <tr>
                    @if($checkbox)
                        <th class="checkbox-column">
                            <label class="new-control new-checkbox checkbox-primary"
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
                                    <th wire:click="setOrder('{{$column['field']}}')">{{$column['title']}}</th>
                                @elseif($column['visible_in_export'] === false)
                                    <th wire:click="setOrder('{{$column['field']}}')">{{$column['title']}}</th>
                                @endif

                            @else
                                @if(!isset($column['visible_in_export']))
                                    <th>{{$column['title']}}</th>
                                @elseif($column['visible_in_export'] === false)
                                    <th>{{$column['title']}}</th>
                                @endif
                            @endif
                        @else
                            @if(!isset($column['visible_in_export']))
                                <th>{{$column['title']}}</th>
                            @elseif($column['visible_in_export'] === false)
                                <th>{{$column['title']}}</th>
                            @endif
                        @endif
                    @endforeach
                    @if(isset($actionBtns) && count($actionBtns))
                        <th class="text-center" colspan="{{count($actionBtns)}}">Ações</th>
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
                                    <td class="{{(isset($column['class'])? $column['class']: "")}}">
                                        {!! $row->$field !!}
                                    </td>
                                @elseif($column['visible_in_export'] === false)
                                    <td class="{{(isset($column['class'])? $column['class']: "")}}">
                                        {!! $row->$field !!}
                                    </td>
                                @endif
                            @else
                                @if(!isset($column['visible_in_export']))
                                    <td class="{{(isset($column['class'])? $column['class']: "")}}">{{$row->$field}}</td>
                                @elseif($column['visible_in_export'] === false)
                                    <td class="{{(isset($column['class'])? $column['class']: "")}}">{{$row->$field}}</td>
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
                                                <button type="button" wire:click="actionCall('{{$action['action']}}','{{ json_encode($parameters)}}')" class="{{$action['class']}}">
                                                    {{ $action['caption'] }}
                                                </button>
                                            @endif
                                            @if(isset($action['i']))
                                                <button type="button" wire:click="actionCall('{{$action['action']}}','{{ json_encode($parameters) }}')" class="{{$action['class']}}">
                                                    <i class="{{$action['i']['class']}}" title="{{$action['i']['text']}}"></i> {{($action['i']['caption'])? $action['i']['text']: ""}}
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
             <nav aria-label="Templates page navigation">
                @if(method_exists($data, 'links'))
                    {!! $data->links() !!}
                @endif
            </nav>
        </div>
    </div>

</div>



