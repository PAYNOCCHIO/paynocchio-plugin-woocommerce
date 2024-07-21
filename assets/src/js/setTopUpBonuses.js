export default function setTopUpBonuses(value, reward, replenishAmount, commissionAmount) {

    const top_up_amount = document.getElementById('top_up_amount');
    const topup_message = document.getElementById('topup_message');

    if (reward > 0) {
        topup_message.innerHTML = 'Your balance will be replenished by $<span id="replenishAmount">' + replenishAmount + '</span>, commission is $<span id="commissionAmount">' + commissionAmount + '</span>. You will get <span id="bonusesCounter">' + reward + '</span> bonuses.';
    } else {
        topup_message.innerHTML = 'Your balance will be replenished by $<span id="replenishAmount">' + replenishAmount + '</span>, commission is $<span id="commissionAmount">' + commissionAmount + '</span>.';
    }

    if(top_up_amount) {
        top_up_amount.setAttribute('data-value', value);
    }
}