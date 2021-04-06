@component('mail::message')

{{ __('Hello '.$subscriberName.',') }}

{{ __('A File has been uploaded on Fileshare.') }}

@component('mail::panel')
# {{ __($fileName) }}
@endcomponent

@component('mail::button', ['url' => route('file-uploads') ])
View File
@endcomponent
@component('mail::button', ['url' => route('file-uploads.download',Crypt::encryptString($fileId)) ])
Download File
@endcomponent

Thanks,<br>
{{ config('app.name').' Team.' }}
@endcomponent
