<tr class="p-2">
    <td class="text-ellipsis">
        <p class="text-lg p-2"><strong>{{ $log->description }}</strong></p>
    </td>
    <td class="text-ellipsis">
        <p class="text-lg p-2">{{ date('M d, Y H:m', strtotime($log->created_at)) }}</></p>
    </td>
    <td class="text-ellipsis">
        <p class="text-lg p-2">{{ $log->getExtraProperty('user') }}</p>
    </td>
</tr>