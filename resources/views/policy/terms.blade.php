<x-user-layout title="Terms & conditions">

    <x-breadcrumbs :links="['Terms & conditions' => '']" />
    
    <div class="flex justify-between items-end mb-4">
        <x-headline>Terms & conditions</x-headline>
        <p class="text-right">Last updated: 2025-11-07</p>
    </div>

    <x-card class="mb-4">
        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Scope and basic principles</h2>

            <p class="text-justify mb-4">
                These general terms of use (hereinafter referred to as the “terms”) define the conditions for using the website owned exclusively by {{ env('COMPANY_NAME') }} (registered office: {{ env('COMPANY_ADDRESS') }}), available at <a href="{{ env('APP_URL') }}" class="text-blue-700 hover:underline">{{ env('APP_URL') }}</a> (hereinafter referred to as the "website").
            </p>

            <p class="text-justify mb-4">
                A User is any adult natural person, business entity, or organization with legal personality who visits the Website, books an appointment through it for any service, or subscribes to the newsletter. Minors are not considered users.
            </p>

            <p class="text-justify mb-4">
                By using any of the Website's services or subscribing to the newsletter, the User accepts these terms as binding. Acceptance also means that the User agrees to follow updates to these terms. If the user does not accept these terms, they are not entitled to use the Website or its services.
            </p>

            <p class="text-justify mb-4">
                The website owner/operator may modify these terms unilaterally at any time. The revised terms take effect on the date they are published on the website. The website's operators may appear under the name "{{ env('APP_NAME') }}".
            </p>

            <p class="text-justify mb-4">
                The user must provide accurate, truthful data. If {{ env('COMPANY_NAME') }} becomes aware that the user has provided false data, it may partially or fully restrict the user's access to the services.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Basic definitions</h2>

            <ul class="list-disc *:ml-6 *:mb-2">
                <li>
                    <span class="font-bold">Operator:</span> {{ env('COMPANY_NAME') }}
                </li>
                <li>
                    <span class="font-bold">Appointment booking:</span> reserving a time slot through the website.
                </li>
                <li>
                    <span class="font-bold">Service:</span> Haircut, shaving, and related booking transactions offered through the website. These are tied to a specific time, date, and location.
                </li>
                <li>
                    <span class="font-bold">Website:</span> <a href="{{ env('APP_URL') }}" class="text-blue-700 hover:underline">{{ env('APP_URL') }}</a> and all of its subpages.
                </li>
            </ul>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Operation of {{ env('COMPANY_NAME') }}</h2>

            <p class="text-justify mb-4">The website offers multiple appointment times determined by {{ env('APP_NAME') }}. Users select their desired time, location, and barber based on availability.</p>

            <ul class="list-disc *:ml-6 *:mb-2">
                <li>
                    <span class="font-bold">Possible changes:</span> The service date or barber may change in exceptional cases (e.g., illness, vacation). {{ env('COMPANY_NAME') }} will make every effort to minimize such disruptions and ensure quality service.
                </li>
                <li>
                    <span class="font-bold">Timing:</span> Services are intended to begin and end punctually within the booked timeframe, though this may vary occasionally.
                </li>
                <li>
                    <span class="font-bold">Cancellations:</span> Users may cancel their appointment free of charge up to 1 hour before the scheduled time.
                </li>
                <li>
                    <span class="font-bold">Cash payment:</span> Users may select cash payment when booking. In this case, payment is made on-site at the appointment time.
                </li>
                <li>
                    <span class="font-bold">Complaints:</span> Complaints cannot be accepted after departure from the service.
                </li>
            </ul>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Behvioral norms</h2>
            <p class="text-justify">
                While using {{ env('COMPANY_NAME') }}'s services and website, the user must respect other users regardless of race, gender, religion, or identity and must not intentionally or unintentionally offend others through words or actions.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Booking and cancellation</h2>
            <p class="text-justify mb-4">
                Appointments can be started by clicking the "BOOK AN APPOINTMENT" button on the website. After selecting the barber, service, date and time, and entering their data, the user can confirm the booking by clicking "Confirm appointment".
            </p>
            <h3 class="font-bold mb-2">To finalize a booking, the user must provide:</h3>
            <ul class="list-disc *:ml-6 *:mb-2 mb-4">
                <li>
                    Their first name
                </li>
                <li>
                    Their email address
                </li>
                <li>
                    The selected service
                </li>
                <li>
                    The selected barber
                </li>
                <li>
                    The selected date and time
                </li>
            </ul>

            <h3 class="font-bold mb-2">Upon successful booking, the service provider sends an email confirmation containing:</h3>
            <ul class="list-disc *:ml-6 *:mb-2 mb-4">
                <li>
                    Date and time of the service
                </li>
                <li>
                    The selected service and its price
                </li>
                <li>
                    The selected barber
                </li>
                <li>
                    The comment wrote by the user (optional)
                </li>
            </ul>

            <h3 class="font-bold mb-2">Cancellations</h3>
            <p class="text-justify mb-4">
                The User must cancel no later than 1 hour before the appointment via phone (<a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>) or by clicking the "View my appointment" button in the confirmation email and then clicking the "Cancel" button on the website. Cancellations through other channels (Facebook, other emails, etc.) are not accepted. If the user fails to cancel within this time, the cancellation cannot be accepted.
            </p>

            <h3 class="font-bold mb-2">Booking modifications</h3>
            <p class="text-justify">
                The User can modify their appointment via phone (<a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>) or by clicking the "View my appointment" button in the confirmation email and then clicking the "Edit" button on the website. Bookings cannot be retroactively reduced in value due to subsequent promotional offers.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Photography</h2>
            <p class="text-justify">
                By finalizing the booking and participating in the service, the user accepts that photos may be taken at the location, which {{ env('COMPANY_NAME') }} may use without restriction.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Promotions</h2>
            <p class="text-justify">
                {{ env('COMPANY_NAME') }} may offer discounts on the original price of services and may change the conditions unilaterally. Third parties may also offer discounts (e.g., coupon campaigns) through separate agreements. In such cases, Perneczky Barber Shop is not responsible for the partner's terms or data management.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Data management</h2>
            <p class="text-justify mb-2">
                The service provider maintains a record of user data. These data may only be transferred to third parties with the User's prior consent. By providing their data, the User authorizes the service provider to handle the data according to:
            </p>
            <ul class="list-disc *:ml-6 *:mb-2 mb-2">
                <li>
                    Act CXII of 2011 on the right of informational self-determination and freedom of information
                </li>
                <li>
                    Act CVIII of 2001 on electronic commerce and information society services
                </li>
            </ul>
            <p class="text-justify">
                For more details, please see our <a href="{{ route('privacy') }}" class="text-blue-700 hover:underline">privacy policy page</a>.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Image and audio recordings</h2>
            <p class="text-justify">
                During services, image and/or audio recordings may be made, which {{ env('APP_NAME') }} may use for marketing purposes. By finalizing a booking or participating in the service, the user consents to the use of such materials for PR and marketing activities.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Liability</h2>
            <p class="text-justify mb-2">
                {{ env('COMPANY_NAME') }} does not guarantee that the services (including the website and related content) will operate without interruption or error. It assumes no liability for:
            </p>
            <ul class="list-disc *:ml-6 *:mb-2">
                <li>
                    Errors or omissions on the website or during service
                </li>
                <li>
                    Malfunctions, interruptions, or unavailability of the website
                </li>
                <li>
                    The availability of content (e.g., time slots)
                </li>
                <li>
                    Complaints made after leaving the service
                </li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Governing law and authorities</h2>
            <p class="text-justify mb-4">
                The relationship between the Service Provider and the User is governed by Hungarian law. In case of disputes, the parties agree to the exclusive jurisdiction of the competent court at the service provider's registered office. For consumer complaints, users may contact:
            </p>

            <h3 class="font-bold mb-2">Consumer Protection Authority (NFH)</h3>
            <ul class="list-disc *:ml-6 *:mb-2 mb-4">
                <li>
                    1364 Budapest, Pf. 144
                </li>
                <li>
                    Email: <a href="mailto:fogyved_kmf_budapest@nfh.hu" class="text-blue-700 hover:underline">fogyved_kmf_budapest@nfh.hu</a>
                </li>
            </ul>

            <h3 class="font-bold mb-2">National Media and Infocommunications Authority (NMHH)</h3>
            <ul class="list-disc *:ml-6 *:mb-2">
                <li>
                    1525 Budapest, Pf. 75
                </li>
                <li>
                    Email: <a href="mailto:info@nmhh.hu" class="text-blue-700 hover:underline">info@nmhh.hu</a>
                </li>
            </ul>
        </div>

    </x-card>
</x-user-layout>