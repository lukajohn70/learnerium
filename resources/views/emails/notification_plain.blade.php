{{ $title }}

{{ $bodyMessage }}

@if(!empty($actionUrl))
View in Learnerium: {{ $actionUrl }}
@endif

---
You are receiving this notification because of your account activity on Learnerium ({{ url('/') }}).
Learnerium, powered by JLM Creative Media.
Contact: learnerium@jlm.com.ng | +234 815 091 7741
Manage notifications: {{ url('/settings/notifications') }}
