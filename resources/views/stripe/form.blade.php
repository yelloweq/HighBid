<x-app-layout>


    <div class="container py-5 mx-auto text-white">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="bg-blue-secondary text-white rounded-lg shadow-sm p-5">
                    <div class="tab-content">
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            <div class="col-lg-8 mx-auto text-center">
                                <h1 class="display-4 text-xl">Save Card</h1>
                            </div>
                        </div>
                        <form role="form" hx-swap="none" id="payment-form">
                            @csrf

                            <div id="card-element" class="text-white my-8 mx-2"></div>
                            <x-primary-button type="submit">Save Card</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Document is ready');
        var stripe = Stripe('{{ env('STRIPE_PRIVATE_KEY') }}');
        console.log('Stripe initialized:', stripe);
        var elements = stripe.elements();
        var styleOptions = {
            style: {
                base: {
                    color: '#ffffff', // Set text color
                    '::placeholder': { // Set placeholder text color
                        color: '#bbbbbb' // Slightly lighter placeholder for contrast
                    },
                    backgroundColor: 'transparent',
                    fontSize: '16px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    padding: '10px 12px', // Adjust padding here, not via TailwindCSS classes
                },
                invalid: {
                    color: '#fa755a',
                    '::placeholder': {
                        color: '#ffcccc'
                    }
                }
            }
        };

        var card = elements.create('card', styleOptions);
        card.mount('#card-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // This should always run if the JS is correct

            console.log('Form submission intercepted'); // Check this is logged

            stripe.createToken(card).then(function(result) {
                console.log('createToken result:', result); // Check what is returned
                if (result.error) {
                    console.log('Error creating token:', result.error.message);
                } else {
                    console.log('Token created:', result.token.id);
                    stripeTokenHandler(result.token);
                }
            });
        });


        function stripeTokenHandler(token) {
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            console.log('Form data before submit:', form.innerHTML); // Check what's being submitted
            if (token) {
                console.log('Token exists, submitting form', token.id);

                var formData = new FormData(form);
                var index = 0;
                for (var pair of formData.entries()) {
                    console.log('FormData entry ' + index + ': ' + pair[0] + ', ' + pair[1]);
                    index++;
                }
                fetch('card/save', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest', // Necessary if Laravel checks for XMLHttpRequest
                        }
                    }).then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.success && data.redirectUrl) {
                            window.location.href = data.redirectUrl; // Perform the redirect
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
            }
        }


    });
</script>
