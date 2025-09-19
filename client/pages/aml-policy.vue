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
  <h2 class="text-2xl">Política AML</h2>

  <div
    class="bg-white/5 rounded-lg max-w-none px-7 py-6 mt-6 prose text-white prose-headings:text-white prose-a:text-white prose-h2:mt-0">
    <article>
      <h2>Introduction</h2>
      <p><b>NovaWave Technology N.V.</b>, is licensed and regulated by the Governor of Curaçao, to offer remote
        (online) games over the internet, under the Remote Gaming Regulations. Under the license conditions issued by
        Curacao Authorities, <b>{{ siteName }}</b> is required to have in place adequate measures to prevent its systems
        from being used for the purposesof money laundering, terrorist financing or any other criminal activity.
        <b>{{ siteName }}</b> is therefore obliged to follow the provisions contained in the legal framework namely Proceeds
        of Crime Act 2002 (Part 7), and The Money Laundering, Terrorist Financing and Transfer of Funds (information
        on the payer) Regulation no. 692 of 26 ofJune of 2017 and any guidance notes issued by the relevant
        Authorities. </p>
      <h2>Objective of the Policy</h2>
      <p><b>{{ siteName }}</b> is fully committed to be constantly vigilant to prevent money laundering and combat the
        financing of terrorism in order to minimize and manage riskssuch as the risks to its reputational risk, legal
        risk and regulatory risk. It is also committed to its social duty to prevent serious crime and not to allow
        its systems to be abused in furtherance of these crimes. The company will endeavor to keep itself updatedwith
        developments both at national and international level on any initiatives to prevent money laundering and the
        financing of terrorism. It commits itself to protect, at all times, the organization and its operations and
        safeguards its reputation and all from the threat of money laundering, the funding of terrorist and other
        criminal activities. </p>
      <h2>Obligations</h2>
      <ul>
        <li>Appoint one of its senior officers as the designated Money Laundering Reporting Officer (MLRO) whose
          responsibilities will include the duties required by the laws regulations and guidance notes. </li>
        <li> All instances of suspected Money Laundering attempts must be reported to the MLRO, but that suspicion
          must not be conveyed to the customer and further actions must await consents. The MLRO is then responsible
          for liaising with, submitting Suspicious Activity Reports and seeking consents from the responsible legal
          authority in the jurisdiction where the transaction was done, when appropriate. He will also ensure that
          appropriate registers are kept for all related reporting. </li>
        <li> Take reasonable steps to establish the identity of any person for whom it is proposed to provide its
          service. </li>
        <li><b>{{ siteName }}</b> will implement a FULL ‘Know Your Customer’ policy. This will assist in tackling not just
          Money Laundering threats but other fraud threats generally. Customers will be advised at registration and
          generally on the supporting non-transactional website that Company will utilize online verification tools
          and that in instances where we are not able to satisfy criteria, ID and address verification documents will
          be requested. </li>
        <li>Keep at all times a secure online list of all registered Players.</li>
        <li>Retain identification and transactional documentation as defined in the laws regulations and guidance
          notes. </li>
        <li>The business will also not have any cash element to it, mitigating risks associated with counterfeit
          monies. </li>
        <li>Systems will be operating to record all transactions for an account. These account histories will be
          subject to a program of regular and continual checks against fraud and social responsibility indicators.
          Where unusual bet patterns and winning patterns are detected, these will also be cross- referenced to call
          history to ensure that staff are not colluding with customers or acting illicitly. </li>
        <li><b>{{ siteName }}</b> will also keep a register of those customers that it identifiesas high risk or politically
          exposed. Those accounts will be subject to greater scrutiny and frequency of checks. </li>
        <li>In general, where illegal or inappropriate behavior by staff is identified, investigatory and disciplinary
          procedures will follow those outlined in staff contracts as required by employment law directives and the
          need to use in greater propensity, measures such as suspension when for example transactional fraud is
          suspected and those activities form a standard part of an employee’s job description. </li>
        <li>All transactions carried out on our site may be controlled to avoid money laundering or terrorist
          financing activities. Suspicious transactions will be reported to the relevant authority, depending on the
          jurisdiction governing the transaction. </li>
        <li>If our risk and fraud department suspects fraud, it may require the player to provide appropriate
          documentation. If there is enough evidence of fraudulent behavior (eg bank repudiation, account operated by
          third parties, use of forged documents, fraud of means of payment, etc.), the account will be suspended
          until the situation is cleared. The suspension shall be communicated to the relevant authority together with
          the respective grounds (evidence or evidence collected) within 24 hours and to the authorities responsible
          for criminal proceedings. Parallel to the suspension, <b>{{ siteName }}</b> may proceed to the immobilization, as
          a precautionary measure, of the balance of the player account, until the decision of the competent
          authorities. </li>
        <li>Provide initial and ongoing training to all relevant staff, including, but not limited to: </li>
        <li>Staff personal responsibilities; <ol type="1">
            <li>Procedures in respect of identifying Players;</li>
            <li>Monitoring Player activity;</li>
            <li>Record-keeping;</li>
            <li>Reporting any unusual/suspicious transactions;</li>
            <li>Unusual Betting Patterns;</li>
            <li>Suspicious deposit and withdrawal patterns (size and frequency); </li>
            <li>Reliability of Card Data;</li>
            <li>Customer verification issues and Identity Theft;</li>
            <li>Customer verification issues and Identity Theft;</li>
            <li>Account linkage/multiple accounting;</li>
            <li>Account for High Risk Jurisdictions;</li>
            <li>Client due diligence (CDD), including enhanced requirements for high risk clients, which includes
              PEPs; <ul>
                <li>Ensure that this policy is developed and maintained in line with evolving statutory and regulatory
                  obligation and advice from the relevant authorities. </li>
                <li>Examine with special attention, and to the extent possible, the background and purpose of any
                  complex or large transactions andany transactions which are particularly likely, by their nature, to
                  be related to money laundering or the funding of terrorism. </li>
                <li><b>{{ siteName }}</b> may terminate Accounts with immediate effect and retain all account funds in the
                  event of a credible suspicion of fraudulent activity including, but not limited to, thesupply of
                  fraudulent, forged, altered or stolen personal data and,still, in the case of multiple account
                  registration. </li>
                <li>Report any suspicion or knowledge of money laundering of terrorist financing to the Financial
                  Intelligence Analysis Unit (FIAU) set up by law as the Government Agency responsible for the
                  collection, collation, processing, analysis and dissemination of information with a view to prevent
                  money laundering and combat the funding of terrorism. </li>
                <li>Suspicious Activity in this case is being referred to as suspicious transactions, extreme player
                  profiles, when deposits are not matching up amongst other elements. Other concrete examples of how
                  we identify players who require our team to undertake a risk monitoring approach of our customers
                  and when to specifically carry out enhanced due diligence checks on the Player Profiles can be
                  further required: <ol type="1">
                    <li>Passport or ID card.</li>
                    <li>Utility bill.</li>
                    <li>Bank statement.</li>
                    <li>Other proof of identity.</li>
                  </ol>
                </li>
                <li> The Enhanced Due Diligence Checks are subject to players’ profile and the amount of Risk they
                  pose to us. Only when we determine some of the above points or a combination of a few will we flag
                  the customer/customers in question and conduct risk monitoring. This will include checks of where
                  the customer works, value of house, where the customer lives and checks to see whether the value of
                  the house is logical compared to the customers spending. </li>
                <li> Within that framework, Suspicious Activity Reports (SARs) are an imposed requirement.
                  <b>{{ siteName }}</b> ensures that any employee reports to the Risk Team where they have groundsfor
                  knowledge or suspicion that a person or customer is engagedin ML or terrorist financing. Any
                  employee failing to so do is open to criminal prosecution. </li>
                <li> Escalations of SARs should be done in a confidential, discreet manner, in a handwritten form and
                  not via email so as to ensure maximum anonymity. </li>
                <li> An employee must not, under any circumstances, disclose or discuss any AML concern with the
                  person or persons subject to an investigation, or any other person for that matter. Disclosure (also
                  known as "tipping off") is strictly prohibited and carries with it very serious legal penalties.
                </li>
                <li> Furthermore, and in order to keep ourselves protected as much aspossible, no remark should ever
                  be left on an account that would give any indication that ML is suspected, a player being entitled,
                  at any point in time, to request the full notes/remarks on their account. </li>
                <li>Cooperate with all relevant administrative, enforcement and judicial authorities in their endeavor
                  to prevent and detect criminal activity. Moreover, the company shall: </li>
                <li> Not accept to open anonymous Accounts or Accounts in fictitious names such that the true
                  beneficial owner is not known. </li>
                <li> Not accept cash from Players.</li>
                <li> Funds may be received from Players only by any of the following methods: credit cards, debit
                  cards, electronic transfer, wire transfer cheques and any other method approved by the Regulator.
                </li>
                <li> Not register a Player who is under eighteen (18) years of age. </li>
                <li>Only register a single account in the name of a particular person: multi-account practices are
                  strictly prohibited. </li>
                <li>Transfer payments of winnings or refunds back to the same route from where the funds originated,
                  where possible. </li>
                <li>Not accept a wager unless a User Account has been established in the name of the Player and there
                  are adequate funds in the Account to cover the amount of the wager. </li>
                <li>Not accept a wager unless the funds necessary to cover the amount of the wager are provided in an
                  approved way. </li>
                <li>Not accept Players residing or playing from non-reputable jurisdictions. </li>
                <li> Not make a payment in excess of three hundred EUR (€ 300) or cumulative out of a User Account to
                  a Player until the Player’s identity, age, and place of residence have been verified. </li>
                <li>If no transaction has been recorded on a User Account for thirty months, close the User Account
                  and remit the balance in that account to the Player, or if the Player cannot be satisfactorily
                  located, to the regulator. </li>
                <li>Where it deems necessary, verify creditworthiness of the Player with third parties who previously
                  provided any information on the Player. </li>
                <li>If it becomes aware that a person has provided false information when providing due diligence
                  documents, not register such person. Where that person has already been registered, the company
                  shall immediately cancel that person’s registration as a Player with the company. </li>
              </ul>
            </li>
          </ol>
        </li>
      </ul>
      <p> The customer declares and expressly guarantees that all the amounts deposited and used to participate in
        <b>{{ siteName }}</b> remote online games come from legitimate sources and are not connected with any fraudulent
        activity, money laundering or activities that are considered to be illicit by law such as money laundering or
        funding of terrorism. Pursuant to the current law, and regulations which may be issued at any time by the
        regulator about the prevention of money laundering and the funding of terrorism, <b>{{ siteName }}</b> reserves the
        right to take any steps is considers necessary to comply with these requirements. The users cannot use loans
        to participate in <b>{{ siteName }}</b> website. <b>{{ siteName }}</b> will not grant the user any kind of loan or
        mechanism that allows them to take out loans or transfer funds between players, at any time. <b>{{ siteName }}</b>
        reserves the right to supervise any transfer of funds that are deposited or withdrawn by any user and to
        report any user information to the regulatory or criminalauthorities and/or payment service providers.
        <b>{{ siteName }}</b> keep the players' money in an account which is legally separate from <b>{{ siteName }}</b> own
        funds. And also reserves the right to retain all a customer’s funds at its own criteria if it considers that
        they derive directly or are related with illicit or fraudulent activities. After confirmation of the deposit,
        the customer can use the deposited funds for bets. The deposits are credited to the player’s account as soon
        as the bank transfer is successfully validated. Deposits and withdrawals are only made in Brazilian Reais
        ($), US Dollars (US$), and Euro (€). The minimum deposit amount is BRL 60, BRL 10, or EUR 10. It is the
        customer’s entire responsibility to check whether their bank imposes any fees on these transfers. The client
        declares that is the holder of the payment account provided to <b>{{ siteName }}</b> and the payment methods used
        are associated with that same account, or if not possible, associate it to an account owned by the client. The
        totalbalance of your player account includes Casino Balance, Real Sportsbook Balance, Casino Bonus Balance and
        Sportsbook Bonus Balance. The existence of two wallets (casino and sports betting) does not change the
        behavior of your player account when making an withdrawal request. Consequently, making a request of withdraw,
        regardless the balance to be withdrawn is transferred from your casino wallet, it may result in the loss or
        cancellation of sports betting bonuses. The withdrawals must be made by the same method as that used for the
        deposits whenever the method used allows it. If the deposit method does not allow withdrawals, these must be
        made by bank transfer to the account given by the user when they registered or to the payment account they
        indicatedand hold and that is chosen for this purpose. Withdrawals may only be processed from your Sports Cash
        Account. Any withdrawals from your Casino Cash Account can only be made by first transferring your withdrawal
        amount to your Sports Cash Account and then requesting the withdrawal be processed from the Sports Cash
        Account. Please note however that any transfer of funds from the Casino Cash Account to the Sports Cash
        Account will be considered a Withdrawal from the Casino Cash Account and may consequently result in the
        forfeiture or voidance of Casino product bonuses and promotions. <b>{{ siteName }}</b> reviews players spend and
        game play to check for suspicious activity. Before any withdrawal is processed the following procedures are
        carried out: </p>
      <ol type="1">
        <li>The customers deposit history is reviewed to confirm that no suspicious payments have been made to the
          customer’s account. The frequency of deposits and the sum of deposits are reviewed to ensure they are within
          normal range for the customer based on his depositing history and the general depositing range throughout
          our network. </li>
        <li>The customer’s turnover is reviewed to ensure that they have played in the casino and are not using
          <b>{{ siteName }}</b> as a method to move money. </li>
        <li>When possible, funds must always be refunded back to the original payment method used by the player to
          make a deposit. In order to fulfil the legal requirements your identity must be verified by <b>{{ siteName }}.</b>
          For this purpose, at the first time of the withdrawal request you should send us an email to
          support email with at least the following documents: </li>
        <li>Document of your identity with photo and date of birth;</li>
        <li>Proof of address;</li>
        <li>Proof of ownership of the account;</li>
        <li>Whenever you pretend to change your payment’s account you must send a new proof ofthe ownership of the new
          account. Without that your payment won’t be processed. Your Player Account shall not be used or treated as a
          bank account. In case of deposits or withdrawals without gaming activity, or activity using reduced
          proportions of initial deposits, <b>{{ siteName }}</b> reserves the right to request explanations before
          transferring the withdrawal request in question. Your withdrawal request will be cancelled within 72 hours
          from the date of your order if you don’t give to <b>{{ siteName }}</b> the necessary information to allow
          verification of your identity. Any withdrawal is free. </li>
      </ol>
      <h2>Politically exposed person</h2>
      <p>European guidelines regarding “The Prevention of Laundering the Proceeds from Criminal Activity (Money
        Laundering) and Terrorism Financing” entered into effect, changing the definition of politically exposed
        person. In order to minimize corruption and money laundering risks, <b>{{ siteName }}</b> is obliged by law to
        collect data andidentify which of the clients / clients’ beneficiaries should be considered as politically
        exposed persons, their family members or close associates. According to the law “On the Prevention of
        Laundering the Proceeds from Criminal Activity (Money Laundering)and Terrorism Financing” the following person
        shall be deemed a politically exposed person (hereinafter referred to as PEP): a person who holds or has held
        a prominent public position in a country of the European Union or European Economic Area or a third country,
        including senior-level state official, head of a state administrative unit (municipality), head of the
        government, minister (deputy minister or assistant deputy minister if there is such position in a relevant
        country), secretary of state or another highlevel official in the government or state administrative unit
        (municipality), member of parliament or similar legislative body, member of the management body (board) of a
        political party, judge of constitutional court, supreme court, or another level court (member of judicial
        body), member of the council or board of a supreme audit institution, member of the council or the board of a
        central bank, ambassador, charge d’affaires, armed forces higher officer, member of the council or board of a
        state capital company, head (director, deputy director) of international organization and member of the board
        or a person who holds an equal position at such organization. The following person shall be deemed a family
        member of a politically exposed person:</p>
      <ol type="1">
        <li> a spouse or person considered to be the equivalent to a spouse. A person may be deemed the equivalent to
          a spouse only provided that he / she has such status under the laws of the respective country; </li>
        <li>a child or a child of a politically exposed person’s spouse or person considered to be the equivalent to a
          spouse, his / her spouse or person considered to be the equivalent to a spouse; </li>
        <li>a parent, grandparent or grandchild;</li>
        <li>a brother or sister.</li>
      </ol>
      <p> A close associate of a politically exposed person is an individual publicly known to maintain business or
        other close relationship with a politically exposed person, or to be a shareholder in one and the same
        commercial company with a PEP, and also an individual who is the sole owner of such legal entity which is
        known to be actually established for the benefit of a PEP. </p>
      <h2>Forbidden Countries</h2>
      <p>The following countries are restricted from participation with <b>{{ siteName }}:</b></p>
      <ol type="1">
        <li> United States of America and its territories, Aruba, Bonaire, Curacao, France, The Netherlands, Saba,
          Statia, St. Maarten, Singapore. </li>
        <li> Iran, North Korea, Albania, Barbados, Botswana, Cambodia, Jamaica, Haiti, Mauritius, Myanmar (Burma),
          Nicaragua, Pakistan, Panama, South Sudan, Syria, Yemen, and Zimbabwe. </li>
      </ol>
    </article>
  </div>
</template>
