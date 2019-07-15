<template>
    
    <!-- https://www.npmjs.com/package/vue-stripe -->
    <!-- Comencem per : son variables -->
    <stripe-checkout
            button="Suscribirme"
            buttonClass="btn btn-course"
            :stripe-key="stripe_key"
            :product="product"
    >
    </stripe-checkout>
</template>

<script>
    //documentacio vue-stripe
    import { StripeCheckout } from 'vue-stripe';
    export default {
        components: {
            StripeCheckout
        },
        name: "stripe-form",
        props: {
            stripe_key: '',
            name: '',
            amount: '',
            description: ''
        },
        computed: {
            product () {
                return {
                    name: this.name,
                    amount: parseFloat(this.amount),
                    description: this.description
                }
            }
        }
    }
</script>