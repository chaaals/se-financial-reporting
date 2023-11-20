<div>
    @if($trial_balances)
    <section>
        @foreach($trial_balances as $tb)
            <a href="/trial-balances/{{ $tb->tb_id }}">{{ $tb->tb_name }}</a>
            <div>{{ $tb->period }}</div>
            <div>{{ $tb->tb_data }}</div>
        @endforeach
    </section>
    @endif
</div>
