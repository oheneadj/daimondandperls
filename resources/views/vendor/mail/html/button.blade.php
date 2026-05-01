@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin:24px 0;">
<tr>
<td align="{{ $align }}">
  <a href="{{ $url }}" target="_blank" rel="noopener"
     style="display:inline-block;padding:14px 32px;background-color:#D52518;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;border-radius:10px;letter-spacing:0.3px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    {!! $slot !!}
  </a>
</td>
</tr>
</table>
