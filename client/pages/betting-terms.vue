<script setup lang="ts">
import { ref, onMounted } from 'vue'

const siteName = ref('')
const siteHost = ref('')

onMounted(() => {
  siteHost.value = window.location.host
  siteName.value = capitalizeFirstLetter(window.location.host.split('.')[0])
})

function capitalizeFirstLetter(string: string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
</script>

<template>
  <h2 class="text-2xl">Termos de apostas</h2>

  <div class="bg-white/5 rounded-lg max-w-none px-7 py-6 mt-6 prose text-white prose-headings:text-white prose-a:text-white prose-h2:mt-0">
    <article>
      <h2> Regulation </h2>
      <p> These Terms and Conditions apply to you and are binding on you if you participate in {{ siteName }}. </p>
      <p> With the aim of a harmonious, lasting and fair relationship with our customers and in order to avoid doubts
        regarding the development of sports games, {{ siteName }} has rules for any customer. These rules will always prevail
        for any and all registrations at {{ siteName }}. For this reason, you must accept our terms when registering. By
        participating in our betting services at <a href="/">{{ siteHost }}</a> ("the Service"), you agree that
        you have read and understood these Terms and Conditions and acknowledge that these Terms and Conditions will
        apply to your account. You must read the Terms carefully, if you do not agree with them and/or cannot accept
        them, please do not use, visit or access the Site. By checking the box "I accept {{ siteName }}'s terms and
        conditions" as part of the registration process, you agree to be bound by these Terms and Conditions, which
        include, and are inseparably linked to, our Privacy Policy. You are subject to the Terms and Conditions in any
        case if you use the Service, which includes but is not limited to registering to use our Service, initiating or
        making a deposit through the Service or playing games. </p>
      <h2>Definitions</h2>
      <p>In these Terms and Conditions:</p>
      <p> "{{ siteName }}" means the brand and all products offered online (access via a computer or laptop) via the website
        <a href="/">{{ siteHost }}</a> and on "Mobile" (access via a mobile phone or tablet) via {{ siteHost }}
        or via app. This includes: sports, casino, live casino and virtual sports. </p>
      <p>About {{ siteName }} and the protection of customer funds </p>
      <p> The customer's "Gaming Agreement" is made with {{ siteName }}, and is celebrated with the successful registration of
        an account at {{ siteName }}. </p>
      <p> All client funds held by the platform are held separate from platform funds in accounts separate from your
        trading account. This means that measures have been taken to protect the Client's Funds, but that, in the event
        of insolvency, there is no absolute guarantee that all funds will be refunded. </p>
      <h2>Your Participation in {{ siteHost }}</h2>
      <p> 1.1. It is expressly forbidden to register and bet for people under 18 years of age. </p>
      <p> 1.2. Registration using data owned by third parties is expressly prohibited. </p>
      <p>1.3. Acceptance</p>
      <p> By accepting these Terms and Conditions, you are fully aware that there is a risk of losing or winning money
        in the game and you are fully responsible for such loss or gain. You agree that your participation in {{ siteName }}
        is at your sole discretion and risk. In connection with your losses, you will have no claim against {{ siteName }} or
        any partner, or their respective directors, officers or employees. </p>
      <p>1.4. Identification and Documentation</p>
      <p> To participate in {{ siteName }}, you must enter your personal information during the account registration process.
        When making a withdrawal request, you may also be asked to submit valid identification documents that show your
        age and address. After this request, the withdrawal will not be processed for payment until {{ siteName }} has
        received all the requested identification documents. Acceptable identification documentation includes, but is
        not limited to: a copy of a valid photo ID, such as a passport or driver's licence; copy of a recent bill that
        confirms residency, such as an electricity bill, phone bill, etc. (important: the invoice must not be older than
        3 months); and copy of a recent bank account statement (note: account statement must relate to the financial
        method used and must not be older than 3 months). </p>
      <p>1.5. ID checks</p>
      <p>{{ siteName }} reserves the right to successfully receive and confirm your identification documents, either
        internally or by a third party, before being able to withdraw your account. </p>
      <p>1.6. Age Verification Policy and Identification</p>
      <p> Payment for a withdrawal request will only be made to a registered customer. You hereby authorise us and our
        appointed agents to confirm your identity and clarify your right to use the money you have wagered at {{ siteName }}.
        We may hold funds in your {{ siteName }} customer account until your age has been successfully verified. If, after
        completion of the age verification process, it is determined that you are indeed a minor, the account will be
        permanently blocked. </p>
      <p>1.7. Risk</p>
      <p> You agree that your participation in {{ siteName }} is at your own discretion and risk. Furthermore, you agree that
        your Participation in {{ siteName }} is for your personal entertainment and non-professional use and that you are
        acting on your own behalf. </p>
      <p>1.8. Acceptable use</p>
      <p> You represent, warrant and agree that you will comply with all applicable laws, statutes and regulations
        regarding your use of the website and the Service. We are not responsible for any illegal or unauthorised use of
        the website or the Service by you. By accepting these Terms and Conditions, you agree to assist us, to the best
        of our ability, with compliance with applicable laws and regulations. </p>
      <h2>Your account</h2>
      <p>2.1. Single account </p>
      <p> You can only register and personally operate a single account with {{ siteName }}. If you have more than one
        account, we reserve the right to suspend both accounts permanently. {{ siteName }} may, at its sole discretion, refuse
        to register a customer account or close an existing customer, but all established contractual obligations will
        be fulfilled. </p>
      <p>2.2. Reliability of Information</p>
      <p> You must keep your registration information up to date at all times. If you change your contact or personal
        details, please contact Customer Service to update your account information. The name provided at {{ siteName }} at
        the time of registration must be identical to the one contained in your personal documents. </p>
      <p>2.3. Password</p>
      <p> The customer account registration process requires you to choose your own password combination. You must keep
        this information secret. Any action taken by your account will be retained if your username and password were
        entered correctly. {{ siteName }} cannot assume any responsibility for the use or misuse of unauthorised personal
        data. </p>
      <p>2.4. Bank Details Verification</p>
      <p> When using bank financial account transactions at {{ siteName }}, the name of the account holder must be the same as
        bold when registering a {{ siteName }} account. </p>
      <p>2.5. Any Liability</p>
      <p>{{ siteName }} will not accept any liability for damages or losses deemed or alleged to arise out of or in connection
        with your participation; including, without limitation, delays or interruptions in operation or transmission,
        loss or corruption of data, line or communication failures, misuse of the Offering or a person's website, its
        content, or any errors or omissions in website content. </p>
      <p>2.6. Account Transfers</p>
      <p> Transferring funds between individual accounts is strictly prohibited. Customers are prohibited from selling,
        transferring and/or acquiring accounts to/from other customers. </p>
      <p>2.7. Account Suspension</p>
      <p>{{ siteName }} reserves the right to suspend, close or cancel your customer account at its sole discretion, in case
        of suspicion that: winnings were obtained illegally; violated these Terms and Conditions, during the entire
        period of suspension it will not be possible to unlock the account. </p>
      <p>2.8. Account closure and temporary suspension</p>
      <p> If you wish to temporarily close your customer account, please contact Customer Service for assistance. </p>
      <p>2.9. Changes</p>
      <p>{{ siteName }} reserves the right to suspend, modify, delete or add content to the website or the Services, at its
        sole discretion, with immediate effect and without prior notice. We will not be liable for any loss suffered as
        a result of any changes made to or for any modification or suspension or interruption of the website or the
        Services, and you will have no claim against {{ siteName }} in this regard. </p>
      <h2>3. Deposits and Withdrawals</h2>
      <p>3.1. Records</p>
      <p> It is the bank account holder's responsibility to maintain copies of transaction records and these Terms and
        Conditions, as updated from time to time. </p>
      <p>3.2. Withdrawals</p>
      <p> The minimum withdrawal amount is $ 20.00 (twenty reais) and the maximum amount is $ 4.999,00 (four thousand
        ninety-nine reais), and you can only request a single withdrawal every 24 hours. {{ siteName }} reserves the right to
        change any of these values without prior notice. </p>
    </article>
    <hr class="opacity-5 my-5">
    <div class="pb-3 text-texts">
      <h5>Ao utilizar este site, você está de acordo com o regulamento acima descrito.</h5>
    </div>
  </div>
</template>
