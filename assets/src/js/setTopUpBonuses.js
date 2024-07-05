export default function setTopUpBonuses(value, percent) {

    const top_up_amount = document.getElementById('top_up_amount');
    const bonusesCounter = document.getElementById('bonusesCounter');
    if(top_up_amount) {
        top_up_amount.setAttribute('data-value', value);
        bonusesCounter.innerText = percent.toFixed();
    }
}