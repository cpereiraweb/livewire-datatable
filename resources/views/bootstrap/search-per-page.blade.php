<div class="dt--top-section">
    <div class="row">
        <div class="col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center">
            <div class="" id="zero-config_length">
                <label>
                    @if($perPage_input)
                        <select wire:model="perPage" class="form-control">
                            @foreach($perPageValues as $value)
                            <option>{{ $value }}</option>
                            @endforeach
                        </select>
                    @endif
                </label> <span style="padding-left: 10px">{{ __('Resultados por página') }}</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3">
            <div id="zero-config_filter" class="">
                <label>
                    @if($search_input)
                        <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Buscar...">
                    @endif

                </label>
            </div>
        </div>
    </div>
</div>

