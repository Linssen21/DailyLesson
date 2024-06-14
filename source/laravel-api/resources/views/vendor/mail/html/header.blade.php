@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="Laravel Logo">
        </a>
        <h4 class="m-0">{{ $slot }}</h4>
    </td>
</tr>