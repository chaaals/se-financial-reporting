@foreach($accountTitles as $code=>$title)
    <tr>
        <td class="text-left pl-6">{{ $title }}</td>
        <td>{{ $code }}</td>
        <td>{{ $data[$code]["debit"] ?? "-" }}</td>
        <td>{{ $data[$code]["credit"] ?? "-" }}</td>
    </tr>
@endforeach