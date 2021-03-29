<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .accordion-button {
        padding: 0.7rem 0.7rem;
    }
</style>
<div class="accordion pt-3 pb-2" id="accordion">
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse" aria-expanded="true" aria-controls="collapse">
                Filtro
            </button>
        </h2>
        <div id="collapse" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion">
            <div class="accordion-body">
                <div class="row mb-3">
                    @foreach($make_filters['filter_date_between'] as $field => $date)
                        <div class="{!! (isset($date['class'])? $date['class']: 'col-3') !!} pt-2">
                            <label for="input_{!! $field !!}">{!! $date['label'] !!}</label>
                            <input id="input_{!! $field !!}"
                                   data-key="filters.date_piker.{!! $field !!}"
                                   wire:model="filters.{!! $field !!}"
                                   wire:ignore
                                   autocomplete="false"
                                   value=""
                                   class="form-control flatpickr flatpickr-input active rangeCalendar"
                                   type="text"
                                   placeholder="Selecione o período.."
                            >
                        </div>
                    @endforeach

                </div>

                <div class="col-lg-12 col-12 mx-auto" style="text-align: right">
                    <div style="   ">
                        <button class="mt-2 btn btn-primary" wire:click.prevent="filter()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                                <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                            </svg>  Filtrar</button>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>

