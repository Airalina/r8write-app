@component('mail::message')
{{ __('Esta es una nueva cotizacion') }}

{{ __('Descripcion:')  }} {{ $quote['description']  }}

{{ __('Fecha:')  }} {{ $quote['date']  }}


@endcomponent
