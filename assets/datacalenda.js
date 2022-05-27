import {Datepicker} from "vanillajs-datepicker";

const start  = document.getElementById('booking_form_startDate');
const end = document.getElementById('booking_form_endTime');
const allElem = document.getElementById('allDayUsed');

// get price
const priceAdd = document.getElementById('priceDay').innerHTML;

end.addEventListener("focusout", function (e){

    let elemToDisplay = document.getElementById('amount')
    let date1 = new Date(start.value.replace(/(\d+)\/(\d+)\/(\d{4})/, '$3-$2-$1'));
    let date2 = new Date(end.value.replace(/(\d+)\/(\d+)\/(\d{4})/, '$3-$2-$1'));
    if(date1 && date2 && date1 < date2){
        const DAY_TIME = 24 * 60 * 60 * 1000;

        const interval  = date2.getTime() - date1.getTime();
        const days      = interval / DAY_TIME
        const amount    = days * priceAdd;

        elemToDisplay.innerHTML = amount.toLocaleString('fr-FR');
    }
    // const diffTime = Math.abs(date2 - date1);
    // const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    // console.log(date1, date2)
    // console.log(diffTime + " milliseconds");
    // console.log(diffDays + " days");
})


// display calandar
let allElmStr = allElem.innerHTML;
let allelmWitouhWithspace = allElmStr.replace(/ /g, "")
let allelmWitouhback = allelmWitouhWithspace.replace( /[\r\n]+/gm, ",")
let arrayOfAllDate = allelmWitouhback.split(',')

function constCalan(target){
    let calan = new Datepicker(target,{
        // ...options
        format: 'dd/mm/yyyy',
        datesDisabled: arrayOfAllDate,
        startDate: new Date()
    })

    return calan;
}
const datepickerstart =  constCalan(start)
const datepickerend   =  constCalan(end)


