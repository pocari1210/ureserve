import flatpickr from "flatpickr";
// flatpickr日本語対応
import { Japanese } from "flatpickr/dist/l10n/ja.js"

// minDateで今日以降の日付でないと入力できないようにする
flatpickr("#event_date", {
    "locale": Japanese,
    minDate: "today",
    maxDate: new Date().fp_incr(30)
});

flatpickr("#calendar", {
    "locale": Japanese,
    // minDate: "today",
    maxDate: new Date().fp_incr(30)
});

const setting = {
    "locale": Japanese,
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    minTime: "10:00",
    maxTime: "20:00",
    minuteIncrement: 30
}

flatpickr("#start_time", setting);
flatpickr("#end_time", setting);