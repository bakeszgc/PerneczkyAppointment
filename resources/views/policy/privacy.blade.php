<x-user-layout title="Privacy">

    <x-breadcrumbs :links="['Privacy' => '']" />
    
    <div class="flex justify-between items-end mb-4">
        <x-headline>Privacy policy</x-headline>
        <p>Last updated: 2025-11-05</p>
    </div>

    <x-card class="mb-4">
        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Introduction</h2>
            <p class="text-justify">
                Thank you for taking the time to read the privacy policy of {{ env('APP_NAME') }}, owned by {{ env('COMPANY_NAME') }} ("we", "our", or "us"). We respect your privacy and are committed to protecting your personal data. This privacy policy explains how we collect, use, and safeguard your information when you use our website and online booking services. By using our services, you agree to the collection and use of information in accordance with this policy.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Who we are</h2>
            <ul class="*:mb-2 list-disc *:ml-6 mb-4">
                <li>
                    Data controller: {{ env('COMPANY_NAME') }}
                </li>
                <li>
                    Address: {{ env('COMPANY_ADDRESS') }}
                </li>
                <li>
                    Website: <a href="{{ env('APP_URL') }}" class="text-blue-700 hover:underline">{{ env('APP_URL') }}</a>
                </li>
                <li>
                    Email: <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>
                </li>
                <li>
                    Phone: <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>
                </li>
            </ul>
            <p class="text-justify">
                If you have any questions or concerns about this policy, or if you wish to exercise your data rights, you can contact us via email.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">What personal data we collect</h2>
            <p class="mb-4 text-justify">We collect and process the following types of data:</p>
            <div class="overflow-auto">
                <table class="w-full mb-4 overflow-auto">
                    <thead class="text-left *:*:p-2 *:*:border-2 bg-slate-300">
                        <th>
                            Data type
                        </th>
                        <th>
                            Attributes
                        </th>
                    </thead>
                    <tbody class="*:*:p-2 *:*:border-2"">
                        <tr>
                            <td>Identification details</td>
                            <td>your first name and your last name (only for registered customers)</td>
                        </tr>
                        <tr>
                            <td>Personal details</td>
                            <td>date of birth (optional)</td>
                        </tr>
                        <tr>
                            <td>Contact details</td>
                            <td>email address and phone number (optional)</td>
                        </tr>
                        <tr>
                            <td>Barber details</td>
                            <td>profile picture, display name and description (only collected from users with barber level access)</td>
                        </tr>
                        <tr>
                            <td>Booking details</td>
                            <td>selected barber, service, date, time and comment (optional) regarding the appointment</td>
                        </tr>
                        <tr>
                            <td>Account details</td>
                            <td>if you create an account (or  sign in using Google or Facebook), we store your login information and preferences using cookies</td>
                        </tr>
                        <tr>
                            <td>Communication data</td>
                            <td>messages, emails or feedback you send to us</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-justify">We do not collect or store sensitive personal data such as health information or political opinions.</p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">How and why we use your data</h2>
            <p class="mb-4 text-justify">
                We use your personal data for the following purposes and legal bases:
            </p>
            <div class="overflow-auto">
                <table class="w-full mb-4">
                    <thead class="text-left *:*:p-2 *:*:border-2 bg-slate-300">
                        <th>
                            Purpose
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Legal basis
                        </th>
                    </thead>
                    <tbody class="*:*:p-2 *:*:border-2"">
                        <tr>
                            <td>Booking management</td>
                            <td>To handle your appointment, send confirmations, updates and reminders</td>
                            <td>Contract</td>
                        </tr>
                        <tr>
                            <td>User account</td>
                            <td>To create and manage your online accout</td>
                            <td>Contract</td>
                        </tr>
                        <tr>
                            <td>Communication</td>
                            <td>To contact you regarding bookings, questions or issues</td>
                            <td>Legitimate interest</td>
                        </tr>
                        <tr>
                            <td>Marketing</td>
                            <td>To send promotional offers or updates (only with your consent)</td>
                            <td>Consent</td>
                        </tr>
                        <tr>
                            <td>Analytics & improvements</td>
                            <td>To analyze guest traffic statistics, understand website usage and improve performance</td>
                            <td>Legitimate interest</td>
                        </tr>
                        <tr>
                            <td>Legal & accounting obligations</td>
                            <td>To comply with financial, tax, or consumer protection laws</td>
                            <td>Legal obligation</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-justify">We never sell or rent your personal data to third parties.</p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Data retention</h2>
            <p class="mb-4 text-justify">
                We keep your personal data only as long as necessary for the purposes listed above:
            </p>
            <ul class="*:mb-2 list-disc *:ml-6 mb-4">
                <li>Booking and account data: 2 years after your last interaction</li>
                <li>Accounting or payment records: 8 years (as required by Hungarian tax law)</li>
                <li>Marketing consent data: until you withdraw your consent</li>
            </ul>
            <p class="text-justify">
                After these periods, your data is securely deleted or anonymized.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Who we share your data with</h2>
            <p class="mb-4 text-justify">
                We share personal data only with trusted service providers that help us operate our business, such as:
            </p>
            <ul class="*:mb-2 list-disc *:ml-6 mb-4">
                <li>Hosting provider: {{ env('HOSTING_PROVIDER_NAME') }}</li>
                <li>Email service: {{ env('HOSTING_PROVIDER_NAME') }} Webmail</li>
            </ul>
            <p class="text-justify">
                Each provider processes data only on our instructions and under a Data Processing Agreement compliant with GDPR. If any provider is located outside the EU, data transfers are protected using Standard Contractual Clauses approved by the European Commission.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Cookies and tracking technologies</h2>
            <p class="mb-4 text-justify">
                We use cookies and similar technologies to ensure the website functions properly and store user preferences.
            </p>
            <p class="text-justify">
                When you visit our website for the first time, you can choose to accept or reject non-essential cookies. For more details or managing your cookie preferences, please see our <a href="{{ route('cookies') }}" class="text-blue-700 hover:underline">cookie policy page</a>.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Your rights under GDPR</h2>
            <p class="mb-4 text-justify">As a data subject, you have the following rights:</p>
            <div class="overflow-auto">
                <table class="w-full mb-4">
                    <thead class="text-left *:*:p-2 *:*:border-2 bg-slate-300">
                        <th>
                            Right name
                        </th>
                        <th>
                            Description
                        </th>
                    </thead>
                    <tbody class="*:*:p-2 *:*:border-2"">
                        <tr>
                            <td>Right of access</td>
                            <td>to request a copy of your personal data</td>
                        </tr>
                        <tr>
                            <td>Right of rectification</td>
                            <td>to correct inaccurate or incomplete data</td>
                        </tr>
                        <tr>
                            <td>Right to erasure</td>
                            <td>to delete your data ("right to be forgotten")</td>
                        </tr>
                        <tr>
                            <td>Right to restriction of processing </td>
                            <td>to limit how we use your data</td>
                        </tr>
                        <tr>
                            <td>Right to data portability </td>
                            <td>to receive your data in a structured, machine-readable format</td>
                        </tr>
                        <tr>
                            <td>Right to object </td>
                            <td>to object to certain uses of your data (e.g. marketing)</td>
                        </tr>
                        <tr>
                            <td>Right withdraw consent</td>
                            <td>at any time, if processing is based on consent</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="mb-4 text-justify">
                To exercise these rights, contact us at <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>. We will respond within 30 days.
            </p>
            <p class="mb-4 text-justify">
                If you believe we have processed your data unlawfully, you can file a complaint with the Hungarian National Authority for Data Protection and Freedom of Information (NAIH):
            </p>
            <h3 class="font-bold mb-2">Nemzeti Adatvédelmi és Információszabadság Hatóság (NAIH)</h3>
            <ul class="*:mb-2 list-disc *:ml-6">
                <li>
                    Website: <a href="https://www.naih.hu" class="text-blue-700 hover:underline">https://www.naih.hu</a>
                </li>
                <li>
                    Address: 1055 Budapest, Falk Miksa utca 9-11.
                </li>
                <li>
                    Phone: <a href="tel:+3613911400" class="text-blue-700 hover:underline">+36 (1) 391-1400</a>
                </li>
            </ul>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Data security</h2>
            <p class="mb-4 text-justify">
                We take appropriate technical and organizational measures to protect your data, including:
            </p>
            <ul class="*:mb-2 list-disc *:ml-6 mb-4">
                <li>Encrypted (HTTPS) connections</li>
                <li>Secure servers and password protection</li>
                <li>Regular software and system updates</li>
            </ul>
            <p class="text-justify">
                While no system is 100% secure, we continually review and improve our safeguards.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Changes to this policy</h2>
            <p class="text-justify">
                We may update this privacy policy from time to time. Any changes will be published here with an updated "Last updated" date on the top of this page. If the changes are significant, we will notify users via email.
            </p>
        </div>

        <div>
            <h2 class="text-xl max-md:text-lg font-bold mb-4">Contact us</h2>
            <p class="text-justify mb-4">
                If you have any questions, concerns, or requests regarding this Privacy Policy or your personal data, please contact us:
            </p>
            <h3 class="font-bold mb-2">{{ env('APP_NAME') }}</h3>
            <ul class="*:mb-2 list-disc *:ml-6">
                <li>
                    Email: <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>
                </li>
                <li>
                    Phone: <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>
                </li>
                <li>
                    Address: {{ env('COMPANY_ADDRESS') }}
                </li>
            </ul>
        </div>
    </x-card>
</x-user-layout>