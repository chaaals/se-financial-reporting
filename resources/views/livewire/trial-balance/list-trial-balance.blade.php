<div>
    @if($trial_balances)
    <section>
        @foreach($trial_balances as $tb)
            <div>{{ $tb->tb_name }}</div>
            <div>{{ $tb->period }}</div>
            <div>{{ $tb->tb_data }}</div>
        @endforeach
    </section>
    @endif
</div>
