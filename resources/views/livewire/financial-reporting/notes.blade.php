<section
    class="relative w-10 h-10 flex items-center justify-center hidden md:block"
    x-data="{ isCommentBoxVisible: false }"
    x-on:click.outside="isCommentBoxVisible = false">
    <section class="w-full h-full flex items-center justify-center font-inter">
        <button class="relative" x-on:click="isCommentBoxVisible = true">
            @if($numNotes > 0)
                <div class="text-neutralTwo bg-white w-3.5 h-3.5 text-center absolute -top-1 -right-0.5 text-xs rounded-full">{{ $numNotes }}</div>
            @endif
            <x-financial-reporting.assets.comment />
        </button>
    </section>
    <section
        x-cloak
        x-show="isCommentBoxVisible"
        class="absolute top-0 right-0 w-96 h-128 bg-white drop-shadow-none rounded-lg md:flex md:flex-col">
        <section class="w-full flex items-center justify-between border-neutralThree-opacity-30 border-b-2 p-2">
            <h3 class="text-md font-bold">{{ $reportName }} Thread</h3>
            <button x-on:click="isCommentBoxVisible = false">
                <x-financial-reporting.assets.x :color="$xColor" />
            </button>
        </section>
        <section class="w-full h-3/4 overflow-y-scroll scrollbar p-2">
        @if(!$notes->isEmpty())
            @foreach($notes as $i=>$note)
                <section class="w-full {{ $i === $numNotes - 1 ? "" : "border-b-2 border-dashed border-neutralThree-opacity-50 mb-2 py-4"}}">
                    <div class="flex items-center gap-2">
                        <p class="font-bold">{{ $note->author }}</p>
                        <p class="text-neutralThree text-xs">{{ $note->created_at }}</p>
                        <button wire:click="delete({{$i}})">
                            <x-financial-reporting.assets.c-trash-icon />
                        </button>
                    </div>
                    <p class="whitespace-normal break-all">{{ $note->content }}</p>
                </section>
            @endforeach
        @else
            <p class="text-center">Start taking notes/comments!</p>
        @endif
        </section>
        <form class="p-2" wire:submit="add">
            <section class="relative border-2 border-neutralThree-opacity-50 rounded-lg flex flex-col">
                <input class="w-full p-2 border-0 focus:outline-0" type="textarea" wire:model="note" placeholder="Type your comment here" />
                <button class="bg-neutralThree text-white px-4 py-2 m-1.5 self-end rounded-lg" type="submit">
                    Send
                </button>
                <div class="absolute bottom-6 left-2 w-3/4 h-4 text-xs">
                @error('comment')
                    <span class="text-amber-500">{{ $message }}</span>
                @enderror
                </div>
            </section>
        </form>
    </section>
</section>
