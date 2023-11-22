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

            <button type="submit">Add Financial Report</button>
        </form>
        <div x-show="uploading" x-cloak>Loading file...</div>
    </div>

    @if($spreadsheet)
        <!-- TODO add logic for viewing excel file -->
    @endif
</div>
