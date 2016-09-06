function isHolidays(holidays, dateStamp) {
    if(holidays.length > 0) {
        for(var i = 0; i < holidays.length; i++) {
            if(moment(dateStamp).isSame(holidays['Date_Stamp'])) {
                return true;
            }
        }
    }

    return false;
}

function isWeekend(dateStamp) {
    dateStamp = moment(dateStamp).format('dddd');

    if(dateStamp == 'Sunday') {
        return true;
    } else if(dateStamp == 'Saturday') {
        return true;
    } else {
        return false;
    }
}

function nextDay(dateStamp) {
    return moment(dateStamp).add(1, 'days').format('YYYY-MM-DD HH:mm:ss');
}

function computePenalty(dateLoaned, holidays, amountPerDay, loanPeriod) {
    var graceDate, graceDays, currentDate, dateReturned;

    amountPerDay = parseFloat(amountPerDay);
    loanPeriod = parseFloat(loanPeriod);

    dateLoaned = moment(dateLoaned).format('YYYY-MM-DD HH:mm:ss');
    graceDate = moment(dateLoaned).add(loanPeriod, 'days');
    graceDays = moment(graceDate).diff(dateLoaned, 'days');

    for(var i = 1; i <= graceDays; i++) {
        currentDate = moment(dateLoaned).add(i, 'days');

        if(isWeekend(currentDate)) {
            graceDays++;
            graceDate = nextDay(graceDate);
        } else {
            if(isHolidays(holidays, currentDate)) {
                graceDays++;
                graceDate = nextDay(graceDate);
            }
        }
    }

    dateReturned = moment().format('YYYY-MM-DD HH:mm:ss');
    graceDays = moment(dateReturned).diff(graceDate, 'days');

    for(var j = 1; j <= graceDays; j++) {
        currentDate = moment(graceDate).add(j, 'days');

        if(isWeekend(currentDate)) {
            graceDays++;
            graceDate = nextDay(graceDate);
        } else {
            if(isHolidays(holidays, currentDate)) {
                graceDays++;
                graceDate = nextDay(graceDate);
            }
        }
    }

    totalPenalty = moment(dateReturned).diff(graceDate, 'days') * parseFloat(amountPerDay);

    if(totalPenalty > 0) {
        return totalPenalty;
    } else {
        return 0;
    }
}