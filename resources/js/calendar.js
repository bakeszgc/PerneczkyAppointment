export function renderDivs(appStartTime, appEndTime, calendar, appointments, barberId, divData) {
    // CHECKING THE DAY DIFFERENCE OF THE SELECTED TIME OFF
    const startDateString = appStartTime.toLocaleDateString('en-CA');
    const endDateString = appEndTime.toLocaleDateString('en-CA');    

    const dayDifference = (new Date(endDateString) - new Date(startDateString)) / 1000 / 60 / 60 / 24;
    for (let index = 0; index < dayDifference + 1; index++) {

        // DECLARING VARIABLES FOR EACH DIV ELEMENTS
        const startBase = new Date(appStartTime).setHours(10,0);
        const start = (index == 0)
            ? appStartTime
            : addDays(new Date(startBase),index);
        
        const endBase = new Date(appStartTime).setHours(20,0);
        const end = (index == dayDifference)
            ? appEndTime
            : addDays(new Date(endBase),index);
        const duration = (end - start) / 1000 / 60;
        const startHour = start.getHours();
        const startMinute = (start.getMinutes() == 0) ? '00' : start.getMinutes();
        let clashCount = 0;        

        // ONLY RENDER DIV ELEMENTS IF THEY'RE ON THE DISPLAYED WEEK
        if (start >= getFirstDayOfWeek(appStartTime) && start < addDays(getFirstDayOfWeek(appStartTime),7)) {

            // CHECKING IF THERE ARE CLASHES WITH OTHER APPOINTMENTS
            if (divData.state == 'current') {                        

                if (barberId != 'empty') {
                    let startingDuring = appointments.filter(app => {
                        const appStart = new Date(app.app_start_time.replace(' ', 'T'));
                        return (
                            app.barber_id == barberId &&
                            appStart >= start &&
                            appStart < end &&
                            app.id != divData.appId
                        );
                    });

                    let endingDuring = appointments.filter(app => {
                        const appEnd = new Date(app.app_end_time.replace(' ', 'T'));
                        return (
                            app.barber_id == barberId &&
                            appEnd > start &&
                            appEnd <= end &&
                            app.id != divData.appId
                        );
                    });

                    let startingBeforeEndingAfter = appointments.filter(app => {
                        const appStart = new Date(app.app_start_time.replace(' ', 'T'));
                        const appEnd = new Date(app.app_end_time.replace(' ', 'T'));
                        return (
                            app.barber_id == barberId &&
                            appStart < start &&
                            appEnd > end &&
                            app.id != divData.appId
                        );
                    });

                    clashCount = startingDuring.length + endingDuring.length + startingBeforeEndingAfter.length;
                }
            }                    

            // CREATING NEW DIV ELEMENT
            const div = document.createElement('div');
            div.classList.add('absolute','w-1/8','p-0.5');

            if (divData.state == 'current') {
                div.classList.add('currentApp','z-10');
            } else {
                div.classList.add('existingApp');
            }
            
            div.style.top = 53/60 * ((startHour-10) * 60 + parseInt(startMinute)) + 44 + 'px';
            div.style.left = (start.getDay() == 0 ? 7 : start.getDay()) * 12.5 + '%';
            div.style.height = duration / 60 * 53 + 'px';

            // CREATING THE A ELEMENT FOR EXISTING APPOINTMENTS
            const link = document.createElement('a');
            if (divData.state == 'existing') {
                link.href = ((divData.access == 'admin') ? '/admin' : '') + ((divData.type == 'timeoff') ? "/time-offs/" : "/bookings/") + divData.appId;

                if (divData.access == 'admin') {
                    link.href = '/admin';
                    if (divData.type == 'timeoff') link.href = link.href + '/time-offs/';
                    if (divData.type == 'appointment') link.href = link.href + '/bookings/';
                } else {
                    if (divData.type == 'timeoff') link.href = '/time-offs/';
                    if (divData.type == 'appointment') link.href = '/appointments/';                    
                }
                link.href = link.href + divData.appId;
            }

            // CREATING THE INNER DIV ELEMENT
            const innerDiv = document.createElement('div');
            innerDiv.classList.add('transition-all','h-full','px-1','max-sm:px-0.5','rounded-md','max-lg:translate-y-6','overflow-hidden','text-xs');

            if (divData.state == 'current') {
                innerDiv.classList.add('pl-2');

                if (clashCount != 0 || start < new Date()) {
                    innerDiv.classList.add('bg-red-700','hover:bg-red-800','border-red-800','text-red-50');
                } else {
                    if (divData.type == 'timeoff') {
                        innerDiv.classList.add('bg-green-700','hover:bg-green-800','border-green-800','text-green-100');
                    } else {
                        innerDiv.classList.add('bg-blue-700','hover:bg-blue-800','border-blue-800','text-blue-50');
                    }
                }
            } else {
                innerDiv.classList.add('border','border-l-4');

                if (start >= new Date()) {
                    if (divData.type == 'timeoff') {
                        innerDiv.classList.add('bg-green-100','hover:bg-green-200','border-green-400','text-green-600');
                    } else {
                        innerDiv.classList.add('bg-blue-100','hover:bg-blue-200','border-blue-300','text-blue-600');
                    }
                } else {
                    innerDiv.classList.add('bg-slate-100','hover:bg-slate-200','text-slate-600','border-slate-300');
                }
            }

            // CREATING SOME SPAN ELEMENTS
            if (duration >= 30) {
                const spanTime = document.createElement('span');
                spanTime.classList.add('font-bold');
                spanTime.innerHTML = startHour + ':' + startMinute + ' ';

                const spanName = document.createElement('span');
                spanName.classList.add('font-normal','max-sm:hidden');

                if (divData.state == 'current') {
                    if (clashCount != 0) {
                        spanName.innerHTML = 'OVERLAPPING';
                    } else {
                        if(start < new Date()) {
                            spanName.innerHTML = 'IN PAST';
                        } else {                                
                            spanName.innerHTML = (divData.type == 'timeoff') ? 'TIME OFF' : divData.customerName;
                        }
                    }
                } else {
                    spanName.innerHTML = (divData.type == 'timeoff') ? 'TIME OFF' : divData.customerName;
                }
                        
                innerDiv.appendChild(spanTime);
                innerDiv.appendChild(spanName);
            }

            // ADDING THE HIERARCHY OF ELEMENTS 
            if (divData.state == 'existing') {
                link.appendChild(innerDiv);
                div.appendChild(link);
            } else {
                div.appendChild(innerDiv);
            }                    
            calendar.appendChild(div);
        }
    }
}

window.renderExisting = function (appointments, barberId, appId, access, date, calendar) {
    const ws = new Date(getFirstDayOfWeek(date).toLocaleDateString('en-CA'));
    const weekStart = new Date(ws.setHours(0,0));
    
    const weekEnd = addDays(weekStart,7);
    const filtered = appointments.filter(app => {
        const appStart = new Date(app.app_start_time.replace(' ', 'T'));
        return (
            app.barber_id == barberId &&
            appStart >= weekStart &&
            appStart < weekEnd &&
            app.id != appId
        );
    });
    
    // REMOVING EXISTING APPOINTMENT DIV ELEMENT
    document.querySelectorAll('.existingApp').forEach(el => el.remove());

    // RENDERING EXISTING APPOINTMENT DIV ELEMENTS
    filtered.forEach(app => {
        const appStartTime = new Date(app.app_start_time.replace(' ', 'T'));
        const appEndTime = new Date(app.app_end_time.replace(' ', 'T'));
        const divData = {
            type: (app.service_id == 1) ? 'timeoff' : 'appointment',
            access: access,
            state: 'existing',
            appId: app.id,
            customerName: app.user.first_name
        };
        renderDivs(appStartTime, appEndTime, calendar, appointments, barberId, divData);
    });
};

window.renderCurrent = function (calendar, appStartTime, appEndTime, barberId, appId, customerName, action, type, appointments) {
    // REMOVING EXISTING CURRENT DIV ELEMENT
    document.querySelectorAll('.currentApp').forEach(el => el.remove());
    
    // RENDERING CURRENT DIV ELEMENT
    const divData = {
        state: 'current',
        action: action,
        type: type,
        appId: appId,
        customerName: customerName
    }
    
    renderDivs(appStartTime, appEndTime, calendar, appointments, barberId, divData);
};

window.renderDayNumbers = function (date, ...dayElements) {
    let day = date.getDay();
    if (day === 0) day = 7;
    let mondayDate = getFirstDayOfWeek(date);

    dayElements.forEach((el, i) => {
        let d = new Date(mondayDate);
        d.setDate(mondayDate.getDate() + i);
        el.innerHTML = d.getDate();
        el.classList = '';
        el.classList.add('font-bold','rounded-full','p-1','transition-all');
        (sameDay(d,new Date())) ? el.classList.add('bg-blue-600','text-white','hover:bg-blue-800') : el.classList.add('hover:bg-slate-300');
    });
};

window.getFirstDayOfWeek = function (date) {
    let mondayDate = new Date(date);
    let day = date.getDay();
    if (day === 0) day = 7;
    mondayDate.setDate(date.getDate() - day + 1);
    return mondayDate;
};

export function sameDay(d1, d2) {
    return d1.getFullYear() === d2.getFullYear() &&
           d1.getMonth() === d2.getMonth() &&
           d1.getDate() === d2.getDate();
}

window.addDays = function (date, days) {
    return new Date(new Date(date).setDate(date.getDate() + days));
};

window.getDateTime = function (date, hour, minute) {
    const d = new Date(date.value);
    d.setHours(hour.value,minute.value);
    return d;
}

window.getTimeDifference = function (appStartDate, appStartHour, appStartMinute, appEndDate, appEndHour, appEndMinute) {
    const startDateTime = getDateTime(appStartDate,appStartHour,appStartMinute);
    const endDateTime = getDateTime(appEndDate,appEndHour,appEndMinute);
    return (endDateTime - startDateTime) / 1000 / 60;
};

window.renderDates = function(fromDate, toDate, date) {
    fromDate.innerHTML = getFirstDayOfWeek(date).toLocaleDateString('en-CA');
    toDate.innerHTML = addDays(getFirstDayOfWeek(date),6).toLocaleDateString('en-CA');
};