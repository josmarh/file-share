@component('mail::message')

{{ __('Hello '.$userName.',') }}

{{ __('Your File has been uploaded successfully.') }}

@component('mail::panel')
# {{ __($fileName) }}
@endcomponent

Thanks,<br>
{{ config('app.name').' Team.' }}
@endcomponent
