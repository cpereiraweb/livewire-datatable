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
