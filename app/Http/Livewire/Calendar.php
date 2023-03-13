<?php

namespace App\Http\Livewire;

use Livewire\Component;
// Carbonを普遍にする
use Carbon\CarbonImmutable;
use App\Services\EventService;

class Calendar extends Component
{
    // 初期値設定
    public $currentDate;
    public $currentWeek;
    public $day;
    public $checkDay;
    public $dayOfWeek;
    public $sevenDaysLater;
    public $events;


    // ★画面を表示しした際、初期値の値を出力する★
    public function mount()
    {
        // 今日の日付を取得
        $this->currentDate = CarbonImmutable::today();

        // 今日の日付から7日分のデータを出力する
        $this->sevenDaysLater = $this->currentDate->addDays(7);

        // 1周間文の日付を格納する配列を用意
        $this->currentWeek = [];

        // 本日から7日後までの日付を取得する
        $this->events = EventService::getWeekEvents(
            $this->currentDate->format('Y-m-d'),
            $this->sevenDaysLater->format('Y-m-d'),
        );

        // 1週間文の日付を取得
        for($i = 0; $i < 7; $i++){

            // 今日の日付から7日分のデータを取得
            $this->day =CarbonImmutable::today()->addDays($i)->format('m月d日');
            
            // array_pushで配列に追加をしている
            array_push($this->currentWeek,$this->day);
        }

    }
        // dd($this->currentWeek);

        public function getDate($date)
        {
            $this->currentDate=$date;
            $this->currentWeek=[];
            $this->sevenDaysLater = CarbonImmutable::parse($this->currentDate)->addDays(7); 
            
            $this->events = EventService::getWeekEvents(
                // ※文字列が入ってきているのでcurrentDateはformat不要
                $this->currentDate,
                $this->sevenDaysLater->format('Y-m-d'),
            );                    

            for($i=0;$i < 7; $i++){
                // parseでCarbonインスタンスに返還後日付を加算
                $this->day = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('m月d日');
                array_push($this->currentWeek,$this->day);
            }
        }

        public function render()
    {
        return view('livewire.calendar');
    }
}
