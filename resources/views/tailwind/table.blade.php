
<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

            <button class="mb-1 focus:outline-none text-sm py-2.5 px-5 rounded-full border items-center inline-flex" wire:click="exportToExcel()"
            >
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
                {{ (count($checkbox_values)) ? 'Exportar selecionados': 'Exportar todos' }}
            </button>

            @include('livewire-datatables::tailwind.search-per-page')

            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        @if($checkbox)
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                        @endif
                        @foreach($columns as $column)

                            @if(isset($column['sortable']))
                                <th scope="col" class="cursor-pointer hover:text-black hover:text-current px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" wire:click="setOrder('{{$column['field']}}')">{{$column['title']}}</th>
                            @else
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">{{$column['title']}}</th>
                            @endif
                        @endforeach
                        @if(isset($actionBtns) && count($actionBtns))
                            <th scope="col" colspan="{{count($actionBtns)}}" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        @endif

                    </tr>
                    </thead>
                    <tbody class="text-gray-800">
                    @foreach($data as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 ">
                            @if($checkbox)
                                <td class="px-6 py-4 whitespace-nowrap" style="width: 50px;">
                                    <input type="checkbox" wire:model="checkbox_values" value="{{ $row->{$checkbox_attribute} }}">
                                </td>
                            @endif
                            @foreach($columns as $column)
                                    @php
                                        $field = $column['field'];
                                    @endphp
                                <td class="px-6 py-4 whitespace-nowrap">

                                    @if(isset($column['date']['format']))
                                            {!! \Carbon\Carbon::parse($row->$field)->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format($column['date']['format']) !!}
                                    @else
                                            {{$row->$field}}
                                    @endif

                                </td>
                            @endforeach
                            @if(isset($actionBtns) && count($actionBtns))
                                @foreach($actionBtns as $action)
                                    <td class="px-4 py-2 whitespace-nowrap" style="width: 50px;">
                                        @php
                                            $parameters = [];
                                            foreach ($action['param'] as $param => $value) {
                                                $parameters[$param] = $row->$value;
                                            }
                                        @endphp
                                        <button wire:click="actionCall('{{$action['action']}}','{{ json_encode($parameters)}}')" type="button" class="
                                                    {{ (isset($action['class'])) ? 'focus:outline-none text-sm py-2.5 px-5 rounded-full border '.$action['class']
                                                            :'focus:outline-none text-sm py-2.5 px-5 rounded-full border'
                                                    }}"
                                        >
                                            {{ (isset($action['caption'])) ? $action['caption']: 'Editar' }}
                                        </button>
                                    </td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <div class="p-2">
                    @if(method_exists($data, 'links'))
                        {!! $data->links() !!}
                    @endif
                </div>
            </div>


        </div>
    </div>
</div>
