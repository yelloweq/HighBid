<x-app-layout>

    <section class="mx-auto max-w-7xl">

        <div class="w-full">
            <h1 class="text-3xl text-white font-semibold mt-8 mb-8">Frequently Asked Questions</h1>
        </div>
        <div id="accordion-collapse" data-accordion="collapse">
            <h2 id="accordion-collapse-heading-1">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right  border border-b-0  rounded-t-xl focus:ring-4  focus:ring-gray-800 border-gray-700 text-gray-900  hover:bg-gray-800 hover:text-white gap-3"
                    data-accordion-target="#accordion-collapse-body-1" aria-expanded="true"
                    aria-controls="accordion-collapse-body-1">
                    <span>How can I register as a bidder on your website?</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                <div class="p-5 border border-b-0  border-gray-700 bg-gray-900">
                    <p class="mb-2 t text-gray-400">
                        <strong>Registration Process</strong>: Simply click on the "Register" button on the homepage and fill out the required information. Once registered, you can start bidding immediately.
                    </p>
                    <p class=" text-gray-400 pt-2">

                        <strong>Verification</strong>: We may require email verification to complete the registration process and ensure the security of our platform.
                    </p>
                </div>
            </div>
            <h2 id="accordion-collapse-heading-2">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right  border border-b-0  rounded-t-xl focus:ring-4  focus:ring-gray-800 border-gray-700 text-gray-900  hover:bg-gray-800 hover:text-white gap-3"
                    data-accordion-target="#accordion-collapse-body-2" aria-expanded="true"
                    aria-controls="accordion-collapse-body-2">
                    <span>What payment methods do you accept?</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-collapse-body-2" class="hidden" aria-labelledby="accordion-collapse-heading-2">
                <div class="p-5 border border-b-0  border-gray-700 bg-gray-900">
                    <p class="mb-2 t text-gray-400">
                        <strong>Accepted Payment Methods</strong>: We accept various payment methods including credit/debit cards, PayPal, and bank transfers. Specific payment methods may vary depending on your location.


                    </p>
                    <p class=" text-gray-400 pt-2">
                        <strong>Payment Security</strong>: Our payment processing is secure and encrypted to safeguard your financial information.
                    </p>
                </div>
            </div>
            <h2 id="accordion-collapse-heading-3">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right  border  focus:ring-4  focus:ring-gray-800 border-gray-700 text-gray-400  hover:bg-gray-800 hover:text-white gap-3"
                    data-accordion-target="#accordion-collapse-body-3" aria-expanded="false"
                    aria-controls="accordion-collapse-body-3">
                    <span>Can I sell items on your auction website?</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                <div class="p-5 border border-t-0  border-gray-700">
                    <p class="mb-2  text-gray-400">Yes, any registered user can list their own auctions. After creating one, go to your dashboard for the status information.</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
