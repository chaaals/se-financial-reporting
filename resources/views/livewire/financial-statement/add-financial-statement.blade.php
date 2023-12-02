<div>
    <div
        x-data="{ uploading: false }"
        x-on:livewire-upload-start="uploading = true"
        x-on:livewire-upload-finish="uploading = false"
        x-on:livewire-upload-error="uploading = false"
    >
        <form wire:submit.prevent="add">
            <div>
                <label htmlFor='fsName'>Financial Statement Name</label>
                <input id='fsName' type='text' wire:model='fsName' placeholder='optional' />
            </div>
            <div>
                <label for="fs_type">Type</label>
                <select id="fs_type" wire:model="fsType">
                    <option value="SFPO">SFPO</option>
                    <option value="SFPE">SFPE</option>
                    <option value="SCF">SCF</option>
                </select>
                <div>@error('fsType')<span>{{ $message }}</span>@enderror</div>
            </div>
            <div>
                <label for="interim_period">Interim Period</label>
                <select id="interim_period" wire:model="interimPeriod">
                    <option value="Quarterly">Quarterly</option>
                    <option value="Annual">Annual</option>
                </select>
                <div>@error('interimPeriod')<span>{{ $message }}</span>@enderror</div>
            </div>
            <div>
                <label htmlFor='fsPeriod'>Quarter</label>
                <input id='fsPeriod' type='date' wire:model='date' />
                <div>@error('date')<span>{{ $message }}</span>@enderror</div>
            </div>
            <div>
                <input 
                    type="file"
                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                    wire:model="importedSpreadsheet">
                <div>@error('importedSpreadsheet')<span>{{ $message }}</span>@enderror</div>
            </div>

            <button type="submit">Add Financial Statement</button>
        </form>
        <div x-show="uploading" x-cloak>Loading file...</div>
    </div>

    @if($preview)
        <section style="padding: 1rem;">
            <table>
                <thead>
                    @foreach($preview["headers"] as $rows)
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
                    @foreach($preview["data"] as $rows)
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