{{-- Minimal, renders your custom body --}}
@php
  $company = config('companyinfo.name', 'Springbord');
@endphp
<!doctype html>
<html>
  <body style="font-family: Arial, Helvetica, sans-serif; font-size:14px; color:#222;">
    <p>{!! nl2br(e($body)) !!}</p>
    <p style="margin-top:24px; color:#555;">
      Regards,<br>
      {{ $company }}
    </p>
  </body>
</html>
