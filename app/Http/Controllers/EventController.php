<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\EventService;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ★本日の日付を取得★
        $today = Carbon::today();

        $events = DB::table('events')
        // ★本日以降の開始日を抽出★
        ->whereDate('start_date','>=',$today)
        ->orderBy('start_date','asc')
        ->paginate(10);

        return view('manager.events.index',
        compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.events.create');        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        // dd($request);

        // $check=DB::table('events')
        // ->whereDate('start_date',$request['event_date'])
        // ->whereTime('end_date', '>', $request['start_time'])
        // ->whereTime('start_date', '<', $request['end_time'])
        // ->exists();

        // dd($check);

        $check = EventService::checkEventDuplication(
            $request['event_date'],$request['start_time'],$request['end_time']);        

        if($check){
            session()->flash('status', 'この時間帯は既に他の予約が存在します。');
            return view('manager.events.create');
        }

        // 日付と開始時刻を連結させ、DBに保存
        // $start = $request['event_date'] . " " . $request['start_time']; 
        // $startDate = Carbon::createFromFormat( 'Y-m-d H:i', $start );
        
        // $end = $request['event_date'] . " " . $request['end_time']; 
        // $endDate = Carbon::createFromFormat( 'Y-m-d H:i', $end );

        $startDate = EventService::joinDateAndTime($request['event_date'],$request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'],$request['end_time']);           

        Event::create([
            'name' => $request['event_name'],
            'information' => $request['information'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $request['max_people'],
            'is_visible' => $request['is_visible']
        ]);

        session()->flash('status', '登録okです');

        return to_route('events.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event = Event::findOrFail($event->id);

        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        

        return view('manager.events.show',
        compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $eventDate = $event->editEventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.edit',
        compact('event', 'eventDate', 'startTime', 'endTime'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {

        // countEventDuplication:重複の数を数えられる
        $check = EventService::countEventDuplication(
            $request['event_date'],$request['start_time'],$request['end_time']);

        // 重複の数が1より大きかったらリダイレクトをかける
        if($check > 1){
            $event = Event::findOrFail($event->id);
            $eventDate = $event->editEventDate;
            $startTime = $event->startTime;
            $endTime = $event->endTime;
            session()->flash('status', 'この時間帯は既に他の予約が存在します。');
            return view('manager.events.edit', 
            compact('event', 'eventDate', 'startTime', 'endTime'));
        }

        $startDate = EventService::joinDateAndTime($request['event_date'],$request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'],$request['end_time']);         
        
        $event = Event::findOrFail($event->id);
        $event->name = $request['event_name'];
        $event->information = $request['information'];
        $event->start_date =  $startDate;
        $event->end_date = $endDate;
        $event->max_people = $request['max_people'];
        $event->is_visible = $request['is_visible'];
        $event->save();
        
        session()->flash('status', '更新しました。');

        return to_route('events.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */

     public function past()
     {
        // 本日の日付取得
        $today = Carbon::today();
        $events=DB::table('events')
        
        // 開始日から今日の日付までのイベントを抽出
        ->whereDate('start_date','<',$today)

        // 開始時刻で降順で並び替える
        ->orderBy('start_date','desc')

        // ページネートは10件ずつ表示
        ->paginate(10);

        return view('manager.events.past',compact('events'));

     }

    public function destroy(Event $event)
    {
        //
    }


}
