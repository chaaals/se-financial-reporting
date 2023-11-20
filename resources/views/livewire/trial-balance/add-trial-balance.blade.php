<div>
    <div
        x-data="{ uploading: false }"
        x-on:livewire-upload-start="uploading = true"
        x-on:livewire-upload-finish="uploading = false"
        x-on:livewire-upload-error="uploading = false"
    >
        <form wire:submit.prevent="add">
            <input
                type="file"
                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel""
                wire:model.live="imported_spreadsheet"
            />

            <button type="submit">Add Trial Balance</button>
        </form>
        <div x-show="uploading" x-cloak>Loading file...</div>
    </div>

    @if($spreadsheet)
        <section style="padding: 1rem;">
            <table>
                <thead>
                    @foreach($spreadsheet["headers"] as $rows)
                        <tr>
                            @foreach($rows as $col_index=>$col)
                                @if($col || !in_array($col_index, [1,2,3,6,8,9]))
                                    <th style="border: 1px solid red; white-space: nowrap; min-width: 100px;">{{ $col }}</th>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </thead>
                <tbody>
                    @foreach($spreadsheet["data"] as $rows)
                        <tr>
                            @foreach($rows as $col_index=>$col)
                                @if(!in_array($col_index, [1,2,3,6,8,9]))
                                    <td>
                                        <pre>{{ $col }}</pre>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif
</div>
