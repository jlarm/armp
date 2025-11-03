@component('mail::message')
Please click the link to complete your registration.

@component('mail::button', ['url' => $acceptUrl])
Register
@endcomponent

Thanks,<br>
ARMP
@endcomponent
