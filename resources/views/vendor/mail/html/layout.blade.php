<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
* { box-sizing: border-box; }
body { margin: 0; padding: 0; background-color: #F4F4F6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
table { border-collapse: collapse; }
.wrapper { width: 100%; background-color: #F4F4F6; padding: 32px 16px; }
.content { width: 100%; max-width: 600px; margin: 0 auto; }
.header-cell { padding: 32px 0 24px; text-align: center; }
.logo-wrap { display: inline-block; }
.logo-img { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; }
.brand-name { font-size: 20px; font-weight: 700; color: #1C1A18; margin: 10px 0 0; letter-spacing: -0.3px; }
.brand-tagline { font-size: 12px; color: #888; margin: 2px 0 0; letter-spacing: 0.5px; text-transform: uppercase; }
.body-cell { background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
.accent-bar { height: 4px; background: linear-gradient(90deg, #D52518 0%, #F96015 100%); }
.content-cell { padding: 36px 40px; }
.footer-cell { padding: 24px 0 8px; text-align: center; }
.footer-text { font-size: 12px; color: #9CA3AF; line-height: 1.6; margin: 0; }
.footer-links { margin: 8px 0 0; }
.footer-links a { color: #D52518; text-decoration: none; font-size: 12px; margin: 0 8px; }
p { font-size: 15px; color: #1C1A18; line-height: 1.7; margin: 0 0 16px; }
h1 { font-size: 22px; font-weight: 700; color: #1C1A18; margin: 0 0 20px; }
a { color: #D52518; }
@media only screen and (max-width: 600px) {
  .content-cell { padding: 28px 24px !important; }
  .wrapper { padding: 16px 8px !important; }
}
</style>
{!! $head ?? '' !!}
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="600" cellpadding="0" cellspacing="0" role="presentation">

  {{-- Header --}}
  <tr>
    <td class="header-cell" align="center">
      @php
        $logoPath = dpc_setting('business_logo');
        $companyName = dpc_setting('company_name') ?? config('app.name');
      @endphp
      @if($logoPath)
        <img src="{{ rtrim(config('app.url'), '/') . '/storage/' . $logoPath }}" alt="{{ $companyName }}" class="logo-img" width="56" height="56" style="width:56px;height:56px;border-radius:50%;object-fit:cover;display:block;margin:0 auto;" />
      @else
        <div style="width:56px;height:56px;border-radius:50%;background-color:#D52518;display:inline-block;text-align:center;line-height:56px;font-size:22px;font-weight:900;color:#fff;margin:0 auto;">D</div>
      @endif
      <p class="brand-name" style="font-size:20px;font-weight:700;color:#1C1A18;margin:10px 0 0;letter-spacing:-0.3px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">{{ $companyName }}</p>
      <p class="brand-tagline" style="font-size:11px;color:#9CA3AF;margin:3px 0 0;letter-spacing:0.8px;text-transform:uppercase;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">Catering &amp; Events</p>
    </td>
  </tr>

  {{-- Body --}}
  <tr>
    <td>
      <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr><td class="accent-bar" style="height:4px;background:linear-gradient(90deg,#D52518 0%,#F96015 100%);border-radius:12px 12px 0 0;"></td></tr>
        <tr>
          <td class="content-cell" style="background-color:#FFFFFF;padding:36px 40px;border-radius:0 0 16px 16px;">
            {!! Illuminate\Mail\Markdown::parse($slot) !!}
            {!! $subcopy ?? '' !!}
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- Footer --}}
  <tr>
    <td class="footer-cell" align="center" style="padding:24px 0 8px;">
      @php
        $phone = dpc_setting('business_phone');
        $email = dpc_setting('business_email');
        $address = dpc_setting('business_address');
      @endphp
      @if($address)
        <p class="footer-text" style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">{{ $address }}</p>
      @endif
      @if($phone || $email)
        <p class="footer-text" style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:4px 0 0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
          @if($phone)<a href="tel:{{ $phone }}" style="color:#D52518;text-decoration:none;">{{ $phone }}</a>@endif
          @if($phone && $email) &nbsp;&middot;&nbsp; @endif
          @if($email)<a href="mailto:{{ $email }}" style="color:#D52518;text-decoration:none;">{{ $email }}</a>@endif
        </p>
      @endif
      <p class="footer-text" style="font-size:11px;color:#C4C4C4;margin:12px 0 0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">&copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.</p>
    </td>
  </tr>

</table>
</td>
</tr>
</table>
</body>
</html>
