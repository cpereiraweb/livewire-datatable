<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

            <button class="mb-1 focus:outline-none text-sm py-2.5 px-5 rounded-full border items-center inline-flex"
                    wire:click="exportToExcel()"
            >
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                </svg>
                {{ (count($checkbox_values)) ? 'Exportar selecionados': 'Exportar todos' }}
            </button>

            @include('livewire-datatables::tailwind.search-per-page')

            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>

                        @include('livewire-datatables::tailwind.checkbox-all')

                        @foreach($columns as $column)

                            <th scope="col"
                                class="@if(isset($column['sortable'])) cursor-pointer hover:text-black hover:text-current @endif px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                @if(isset($column['sortable'])) wire:click="setOrder('{{$column['field']}}')" @endif>{{$column['title']}}</th>

                        @endforeach

                        @if(isset($actionBtns) && count($actionBtns))
                            <th scope="col" colspan="{{count($actionBtns)}}"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        @endif

                    </tr>
                    </thead>
                    <tbody class="text-gray-800">
                    @foreach($data as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 ">

                            @include('livewire-datatables::tailwind.checkbox-row')

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

                            @include('livewire-datatables::tailwind.actions')

                        </tr>
                    @endforeach

                    </tbody>
                </table>

                @if(!is_array($data))
                    <div class="p-2">
                        @if(method_exists($data, 'links'))
                            {!! $data->links() !!}
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
