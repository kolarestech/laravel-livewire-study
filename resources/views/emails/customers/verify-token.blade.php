@component('mail::message')
# Verify Token

You needs verify following token.

{{$token}}
{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
