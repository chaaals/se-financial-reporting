<div {{$attributes}} class="w-full bg-white drop-shadow-md rounded-lg p-4 cursor-pointer grow-0 shrink-0 basis-5/12 border-2 hover:border-primary">
    <h1 class="text-md font-bold">{{ $data->collection_name }}</h1>

    <p class="text-sm text-slate-500">{{ $data->interim_period }} Financial Statements</p>
    <p class="text-sm text-slate-500">{{ date('M d, Y', strtotime($data->date))}}</p>
    <div class="w-full rounded-xl text-white mt-2 p-1 text-center @if(strtolower($data->collection_status) === 'draft') {{'bg-draft'}}
    @elseif(strtolower($data->collection_status) === 'for approval'){{'bg-forapproval'}}
    @elseif(strtolower($data->collection_status === 'approved')){{'bg-approved'}}
    @elseif(strtolower($data->collection_status) === 'change requested')
    {{'bg-changerequested'}} @endif">
        <span>{{ $data->collection_status }}</span>
    </div>
</div>