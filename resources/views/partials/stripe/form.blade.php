<form action="{{ route('subscriptions.process_subscription') }}" method="POST">
    {{-- //amb la directiva @csrf s'envia un token amb la peticio --}}
    @csrf
    <input
        class="form-control"
        name="coupon"
        placeholder="{{ __("¿Tienes un cupón?") }}"
    />
    {{-- Es refreix al tupus de plan --}}
    <input type="hidden" name="type" value="{{ $product['type'] }}" />
    <hr />
    <stripe-form
    {{-- Variables de la definicio del formulari
    a stripeForm.vue --}}
        stripe_key="{{ env('STRIPE_KEY') }}"
        name="{{ $product['name'] }}"
        amount="{{ $product['amount'] }}"
        description="{{ $product['description'] }}"
    ></stripe-form>
</form>