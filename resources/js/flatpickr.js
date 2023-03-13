import flatpickr from "flatpickr";
// flatpickr日本語対応
import { Japanese } from "flatpickr/dist/l10n/ja.js"

// minDateで今日以降の日付でないと入力できないようにする
flatpickr("#event_date", {
    "locale": Japanese,
    minDate: "today",
    maxDate: new Date().fp_incr(30)
});

// カレンダー用のフラットピッカー追記
flatpickr("#calendar", {
    "locale": Japanese,
    // minDate: "today",
    maxDate: new Date().fp_incr(30)
});



// flatpickrの時間入力の設定を行う
const setting = {
    "locale": Japanese,
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    minTime: "10:00",
    maxTime: "20:00",
    // 30分単位で時間を増やすしようにする
    minuteIncrement: 30
}

// 第二引数を上で作ったsettingの変数を記述すれば
// 使用することができる
flatpickr("#start_time", setting);
flatpickr("#end_time", setting);