<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
<script>
    flatpickr(document.getElementsByClassName('rangeCalendar'), {
            mode: "range",
            defaultHour: 0,
            locale: "pt",
            dateFormat: "d/m/Y H:i",
            enableTime: true,
            time_24hr: true,
            onClose: function(selectedDates, dateStr, instance){
                let data = [];
                data.push({
                    selectedDates: dateStr,
                    values: instance._input.attributes['data-key'].value
                })
                window.livewire.emit('pikerFilter', data);
            }
        }
    );
</script>
