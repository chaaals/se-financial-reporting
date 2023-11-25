<div>
    Add Financial Report
    <div>
        <label for="report_name">Report Name:</label>
        <input id="report_name" type="text" wire:model="report_name" placeholder="optional">
    </div>
    <div>
        <label for="report_status">Report Status:</label>
        <select id="report_status" wire:model="report_status">
            <option value="Draft">Draft</option>
            <option value="For Approval">For Approval</option>
        </select>
    </div>
    <div>
        <label for="report_type">Report Type:</label>
        <select id="report_type" wire:model="report_type">
            <option value="Quarterly">Quarterly</option>
            <option value="Annual">Annual</option>
        </select>
    </div>
    <div>
        <label for="tb_ids">Trial Balance:</label>
        @if ($trial_balances)
            <select id="tb_ids" wire:model="tb_id">
                @foreach ($trial_balances as $tb)
                    <option value="{{ $tb->tb_id }}">{{ $tb->tb_name }}</option>
                @endforeach
            </select>
        @else
            <input id="tb_ids" type="text" value="No Trial Balance Available" disabled>
        @endif
    </div>

    @if ($trial_balances->isEmpty())
        <div class="text-red-500">Trial balance is empty. Cannot save.</div>
    @else
        <button wire:click="add">Save</button>
    @endif
    <a href="/financial-reports">Cancel</a>
</div>
