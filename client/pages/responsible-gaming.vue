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
  <h2 class="text-2xl">Jogo responsável</h2>

  <div
    class="bg-white/5 rounded-lg max-w-none px-7 py-6 mt-6 prose text-white prose-headings:text-white prose-a:text-white prose-h2:mt-0">
    <article>
      <h2>1. Responsibility to Our Players</h2>
      <p><b>{{ siteName }}</b> values its players and is proud to be chosen as sports book and casino provider. </p>
      <p> The company’s responsible gaming program focuses on the social impact of gambling upholding the following
        three conventions to: </p>
      <ul>
        <li>Prevent problem gambling</li>
        <li>Prevent underage gambling</li>
        <li>Ensure gambling is conducted in a fair and open way</li>
      </ul>
      <p><b>{{ siteName }}</b> recognizes that for a small portion of players wagering may lead to negative consequences in
        other areas of their life. <b>{{ siteName }}</b> encourages responsible gaming for its players and employees. For
        the players, <b>{{ siteName }}</b> achieves this by offering limitsetting and cool-off capabilities, making problem
        gambling resources available in all sports books and online gaming, and helping with self-exclusion both
        in-house and in local jurisdictions. For employees, <b>{{ siteName }}</b> offers annual mandatory training for the
        ticket writers, customer service staff and corporate employees. </p>
      <h3>1.1. Warning signs of problem gambling can include:</h3>
      <ul>
        <li>Losing time from work, school, or family due to betting</li>
        <li>Repeatedly failed in attempts to stop or to control betting</li>
        <li>Borrowing money to gamble or pay betting debts</li>
        <li>Betting to escape worry or trouble</li>
        <li>Neglecting the care of one’s self or family in order to bet</li>
        <li>Lying about the amount of time or money spent on betting</li>
        <li>Selling or pawning personal possessions to get money to bet</li>
        <li>Having feelings of hopelessness, depression, or suicide as a result of betting</li>
      </ul>
      <p> If you or someone you know is experiencing any of these warning signs, <b>{{ siteName }}</b> encourages you to
        measure your gambling risk level by taking a gambling risk test through the regulatory agencies available in
        your jurisdiction. </p>
      <h2>2. Limit Setting</h2>
      <p><b>{{ siteName }}</b> empowers its players by offering several jurisdictionally based limit options to enjoy their
        gaming experience responsibly. Each of the following responsible gaming options are available for players.
        Players can use as many of the available options as they choose. </p>
      <div class="ml-4 mb-4">
        <div>
          <div><b>2.1</b> - Deposit Limit - Ability to set a maximum deposit limit for a selected time frame: daily,
            weekly, or monthly. </div>
          <div><b>2.2</b> - Spending Limit - Set a maximum bet spend for a selected time frame: daily, weekly, or
            monthly. </div>
          <div><b>2.3</b> - Daily Time Limit - Set the maximum time (measured in hours) that can be spent on an
            internet gaming system. </div>
          <div><b>2.4</b> - Cool Off Time Limit - Select when to take a break from a personal account from 3-30 days.
          </div>
        </div>
      </div>
      <h2>Commitment to Preventing Underage Gambling</h2>
      <p> A core aspect of <b>{{ siteName }}’</b>s Responsible Gaming program is the prevention of underage gambling.
        Players must present either a valid driver’s license, national ID, passport, or any valid ID to register and
        wager at the <b>{{ siteName }}</b> sports book website. </p>
      <p><b>{{ siteName }}</b> will verify the player’s identity using a Know Your Customer (KYC) process. If data is
        validated and the customer is confirmed to not be on a self-exclusion list and is over the legal age to gamble
        in that jurisdiction, the account set up is approved. If the KYC process fails to validate the customer, an
        alert message is sent to <b>{{ siteName }}’</b>s customer service team who will contact the requesting customer to
        provide the necessary information to validate identity, etcetera. </p>
      <ol type="1">
        <li><b>{{ siteName }}</b> encourages parents and concerned adults to talk to kids early: Begin a meaningful dialogue
          about the risks of gambling. It’s never too early to start the conversation about gambling and its risks. By
          opening a dialogue, concerned adults can help ensure that kids are better prepared to make responsible
          choices now and throughout their lives. </li>
        <li><b>{{ siteName }}</b> advises the players to keep their login information safe: The players must ensure that
          their account information is secure. Parents and other concerned adults should be mindful of the possibility
          that kids can access their accounts and passwords or use credit cards and personal information to create a
          fraudulent account. </li>
        <li><b>{{ siteName }}</b> suggests considering downloading computer safeguards: While content control tools may have
          their limitations, software is available that may block access to online gambling sites. </li>
        <li> Warning signs that kids may be gambling: Concerned adults must always be aware of the children under
          their responsibility. It’s important to pay attention to the following signs: <ul>
            <li>Kids who have friends who gamble regularly.</li>
            <li>Kids obsessed with the results of professional sports.</li>
            <li>Kids that lie or are secretive about gambling activities.</li>
            <li>Kids that borrow or take money from others to gamble.</li>
            <li>Kids that take money or possessions from the house.</li>
            <li>Kids that have lost interest in social activities and present general dissatisfaction.</li>
            <li>Kids that show unjustified euphoria alternating with moments of sadness and loneliness.</li>
            <li>Drop in kids’ school performance.</li>
            <li>Kids that display signs of apathy or nervousness when they cannot be in front of a computer.</li>
          </ul>
        </li>
      </ol>
    </article>
  </div>
</template>
