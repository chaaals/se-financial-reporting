<div>
    @if($trial_balance)
    <section>
            <div>{{ $trial_balance->tb_name }}</div>
            <div>{{ $trial_balance->period }}</div>
            <div>{{ $trial_balance->tb_data }}</div>
    </section>
    @endif
</div>
