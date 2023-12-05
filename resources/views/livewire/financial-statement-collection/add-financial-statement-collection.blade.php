<div>
    <div>
        <form wire:submit.prevent="add">
            <div>
                <label htmlFor='fsName'>Financial Statement Name</label>
                <input id='fsName' type='text' wire:model='fsName' placeholder='optional' @if($confirming) disabled @endif/>
            </div>
            <div>
                <label for="tbs">Trial Balance</label>
                @if($confirming)
                <select id="tbs" wire:model="tbID" disabled>
                    <option value="{{ $this->tbID }}">{{ $this->tbName }} </option>
                @else
                <select id="tbs" wire:model="tbID">
                    @foreach($trialBalances as $trialBalance)
                        <option value="{{ $trialBalance->tb_id }}">{{ $trialBalance->tb_name }} </option>
                    @endforeach
                @endif
                </select>
                <div>@error('fsType')<span>{{ $message }}</span>@enderror</div>
            </div>
            <div>
                <label for="interim_period">Interim Period</label>
                <select id="interim_period" wire:model="interimPeriod" @if($confirming) disabled @endif>
                    <option value="Quarterly">Quarterly</option>
                    <option value="Annual">Annual</option>
                </select>
                <div>@error('interimPeriod')<span>{{ $message }}</span>@enderror</div>
            </div>
            <div>
                <label htmlFor='fsPeriod'>Quarter</label>
                <input id='fsPeriod' type='date' wire:model='date' @if($confirming) disabled @endif />
                <div>@error('date')<span>{{ $message }}</span>@enderror</div>
            </div>
            <button type="submit">Add Financial Statement</button>
        </form>
        <!-- show confirmation -->
        @if ($confirming)
            <div>
                <span>Add all financial statements?</span>
                <button wire:click="addFS">Yes</button>
                <button wire:click="cancelAddFS">No</button>
            </div>
        @endif
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
