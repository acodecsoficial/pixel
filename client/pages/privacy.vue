<script setup lang="ts">
import { ref, onMounted } from 'vue'

const siteName = ref('')
const siteHost = ref('')

onMounted(() => {
  siteHost.value = window.location.host
  siteName.value = capitalizeFirstLetter(window.location.host.split('.')[0])
})

function capitalizeFirstLetter(string: string) {
  return string.charAt(0).toUpperCase() + string.slice(1)
}
</script>

<template>
  <h2 class="text-2xl">Política de Privacidade</h2>

  <div
    class="bg-white/5 rounded-lg max-w-none px-7 py-6 mt-6 prose text-white prose-headings:text-white prose-a:text-white prose-h2:mt-0">
    <article>
      <p>At <b>{{ siteHost }}</b>, one of our main priorities is the privacy of our clients. This Privacy Policy
        document contains types of information that are collected and processed by <b>{{ siteHost }}</b> and how we use
        it. </p>
      <p> If you have additional questions or require more information about our Privacy Policy, kindly contact us in the
        support email. </p>
      <p> This Privacy Policy applies to our data processing activities and is valid for all our clients with regards
        to the information that they shared with <b>{{ siteHost }}</b>. This policy is applicable to any information
        collected both online and offline. </p>
      <h2>Consent</h2>
      <p> By using our services, you hereby consent to our Privacy Policy and agree to its terms. </p>
      <h2>Information we collect</h2>
      <p> The personal information that you are asked to provide, and the reasons why you are asked to provide it,
        will be made clear to you at the point we ask you to provide your personal information. </p>
      <p> If you contact <b>{{ siteHost }}</b> directly, we may receive additional information about you such as your
        name, email address, phone number, the contents of the message and/or attachments you may send us, and any
        other information you may choose to provide. </p>
      <p> When you register for an Account, we may ask for your contact information, including items such as name,
        company name, address, email address, and telephone number. </p>
      <h2>How we use your information</h2>
      <p>We use the information we collect in various ways, including to:</p>
      <ul>
        <li>Provide, operate, and maintain our website.</li>
        <li>Improve, personalize, and expand our website.</li>
        <li>Understand and analyze how you use our website.</li>
        <li>Develop new products, services, features, and functionality.</li>
        <li>Communicate with you, either directly or through one of our partners, including for customer service, to
          provide you with updates and other information relating to the website, and for marketing and promotional
          purposes. </li>
        <li>Send you emails.</li>
        <li>Find and prevent fraud.</li>
      </ul>
      <h2>Site Cookies and Web Beacons</h2>
      <p> Like any other website, <b>{{ siteHost }}</b> website use 'cookies'. These cookies are used to store
        information including visitors' preferences, and the pages on the website that the visitor accessed or
        visited. The information is used to optimize the users' experience by customizing our web page content based
        on visitors' browser type and/or other information. </p>
      <h2>Third Party Privacy Policies</h2>
      <p><b>{{ siteHost }}</b>'s Privacy Policy does not apply to other advertisers or websites. Thus, we are advising
        you to consult the respective Privacy Policies of these third-party ad servers for more detailed information.
        It may include their practices and instructions about how to opt-out of certain options. </p>
      <p> You can choose to disable cookies through your individual browser options. To know more detailed information
        about cookie management with specific web browsers, it can be found at the browsers' respective websites. </p>
      <h2>GDPR Data Protection Rights</h2>
      <p> We would like to make sure you are fully aware of all of your data protection rights. Every user is entitled
        to the following: </p>
      <ul>
        <li>The right to access – You have the right to request copies of your personal data.</li>
        <li>We may charge you a small fee for this service.</li>
        <li>The right to rectification – You have the right to request that we correct any information you believe is
          inaccurate. You also have the right to request that we complete the information you believe is incomplete.
        </li>
        <li>The right to erasure – You have the right to request that we erase your personal data, under certain
          conditions. </li>
        <li>The right to restrict processing – You have the right to request that we restrict the processing of your
          personal data, under certain conditions. </li>
        <li>The right to object to processing – You have the right to object to our processing of your personal data,
          under certain conditions. </li>
        <li>The right to data portability – You have the right to request that we transfer the data that we have
          collected to another organization, or directly to you, under certain conditions. </li>
        <li>If you make a request, we have one month to respond to you. If you would like to exercise any of these
          rights, please contact us. </li>
      </ul>
      <h2>Children's Information</h2>
      <p> Another part of <b>{{ siteHost }}</b> priority is adding protection for children while using the internet. We
        encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.
      </p>
      <p><b>{{ siteHost }}</b> does not knowingly collect any Personal Identifiable Information from children under the
        age of 18. If you think that your child provided this kind of information on our website, we strongly
        encourage you to contact us immediately and we will do our best efforts to promptly remove such information
        from our records. </p>
    </article>
  </div>
</template>
