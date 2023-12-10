@foreach($accountTitles as $code=>$title)
    <tr>
        <td class="text-left pl-6">{{ $title }}</td>

        @if(is_array($data[$code]))
        <td>{{ $code }}</td>
        <td>{{ $data[$code]["debit"] ?? "-" }}</td>
        <td>{{ $data[$code]["credit"] ?? "-" }}</td>
        @else
        <td>{{ $data[$code] }}</td>
        @endif
    </tr>
@endforeach