@component('mail::message')
# New Appointment Request

A client has submitted a new appointment request.

---

**Location:** {{ $location->name }}  
**Client:** {{ $client->first_name }} {{ $client->last_name }}  
**Pet:** {{ $pet->name }}  
**Service:** {{ $service->name }}  
**Date:** {{ \Carbon\Carbon::parse($appointment->date)->format('l, F j, Y') }}  
**Time:** {{ \Carbon\Carbon::parse($appointment->time)->format('g:i A') }}

@if($appointment->notes)
**Notes:**  
{{ $appointment->notes }}
@endif

---

Please log in to PETSAppeal to review and approve or decline this request.

@endcomponent
