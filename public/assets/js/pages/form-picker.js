"use strict";
flatpickr("#basic-datepicker"), flatpickr("#datetime-datepicker", {
    enableTime: !0,
    dateFormat: "Y-m-d H:i"
}), flatpickr("#minmax-datepicker", {
    altInput: !0,
    altFormat: "F j, Y",
    dateFormat: "Y-m-d"
}), flatpickr("#mindate-datepicker", {
    minDate: "2020-01"
}), flatpickr("#maxdate-datepicker", {
    dateFormat: "d.m.Y",
    maxDate: "15.12.2017"
}), flatpickr("#today-datepicker", {
    minDate: "today"
}), flatpickr("#todaymax-datepicker", {
    minDate: "today",
    maxDate: (new Date).fp_incr(14)
}), flatpickr("#disable-datepicker", {
    onReady: function() {
        this.jumpToDate("2025-01")
    },
    disable: ["2025-01-30", "2025-02-21", "2025-03-08", new Date(2025, 4, 9)],
    dateFormat: "Y-m-d"
}), flatpickr("#disablefunction-datepicker", {
    disable: [function(a) {
        return 0 === a.getDay() || 6 === a.getDay()
    }],
    locale: {
        firstDayOfWeek: 1
    }
}), flatpickr("#multipledates-datepicker", {
    mode: "multiple",
    dateFormat: "Y-m-d"
}), flatpickr("#multipleconjunction-datepicker", {
    mode: "multiple",
    dateFormat: "Y-m-d",
    conjunction: " :: "
}), flatpickr("#rangecalendar-datepicker", {
    mode: "range"
}), flatpickr("#inline-datepicker", {
    inline: !0
}), $("#basic-timepicker").flatpickr({
    enableTime: !0,
    noCalendar: !0,
    dateFormat: "H:i"
}), $("#24hours-timepicker").flatpickr({
    enableTime: !0,
    noCalendar: !0,
    dateFormat: "H:i",
    time_24hr: !0
}), $("#minmax-timepicker").flatpickr({
    enableTime: !0,
    noCalendar: !0,
    dateFormat: "H:i",
    minDate: "16:00",
    maxDate: "22:30"
}), $("#preloading-timepicker").flatpickr({
    enableTime: !0,
    noCalendar: !0,
    dateFormat: "H:i",
    defaultDate: "01:45"
}), $(document).off(".datepicker.data-api");
