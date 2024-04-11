<div {{$attributes}} class="w-full bg-white drop-shadow-md rounded-lg p-4 cursor-pointer shrink-0 grow-0 basis-5/12 border-2 hover:border-primary">
    <h1 class="text-md font-bold">{{ $data->tb_name }}</h1>
    <p class="text-sm text-slate-500">{{ $data->interim_period }} Trial Balance</p>

    <p class="text-sm text-slate-500">{{ date('M d, Y', strtotime($data->tb_date))}}</p>
    <div class="w-full rounded-xl text-white mt-2 p-1 text-center @if(strtolower($data->tb_status) === 'draft') {{'bg-draft'}}
    @elseif(strtolower($data->tb_status) === 'for approval'){{'bg-forapproval'}}
    @elseif(strtolower($data->tb_status === 'approved')){{'bg-approved'}}
    @elseif(strtolower($data->tb_status) === 'change requested')
    {{'bg-changerequested'}} @endif">
        <span>{{ $data->tb_status }}</span>
    </div>
</div>