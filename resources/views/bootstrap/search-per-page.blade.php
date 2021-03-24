<div class="dt--top-section">
    <div class="row">
        <div class="col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center">
            <div class="dataTables_length" id="zero-config_length">
                <label>
                    @if($perPage_input)
                        <select wire:model="perPage" class="form-control">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                    @endif
                </label>
            </div>
        </div>
        <div class="col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3">
            <div id="zero-config_filter" class="dataTables_filter">
                <label>
                    @if($search_input)
                        <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Buscar...">
                    @endif

                </label>
            </div>
        </div>
    </div>
</div>

