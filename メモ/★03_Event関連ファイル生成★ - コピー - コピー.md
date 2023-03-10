
03_Event関連

★Event情報を作成★

php artisan make:model Event -a
※-aはallの略

下記ファイルが作成される

Models/Event.php 
Controllers/EventController.php (各メソッドつき) 
database/migrations/event_table.php 
database/seeders/EventSeeder.php 
database/factories/EventFactory.php 
Requests/StoreEventRequest.php 
Requests/UpdateEventRequest.php 
Policies/EventPolicy.php

★web.phpにコントローラー（EventController）を読み込む★
C:\xampp\htdocs\ureserve\cms\routes\web.php

◆コード解説◆
Route::resource

Route::resourceを使用すると、
「一覧表示」「個別表示」「登録」「登録画面の表示」
「更新」「更新画面の表示」「削除」
のルーティングを作ることができる。

resourceの第二引数は
コントローラー名::class
とする必要がある