
02roleとGateの設定

●Role

★ユーザーテーブルにRoleの追加★
C:\xampp\htdocs\ureserve\cms\database\migrations\2014_10_12_000000_create_users_table.php

★権限付与★
ユーザー登録をすると自動で9が割り振られるようにする
C:\xampp\htdocs\ureserve\cms\app\Actions\Fortify\CreateNewUser.php

★fillable追加★
C:\xampp\htdocs\ureserve\cms\app\Models\User.php

★UserSeeder作成★
php artisan make:seeder UserSeeder

★DatabaseSeeder編集★
C:\xampp\htdocs\ureserve\cms\database\seeders\DatabaseSeeder.php

下記コマンドを入力した際、データを入れなおせるようにする
php artisan migrate:refresh --seed


●Gate


C:\xampp\htdocs\ureserve\cms\app\Providers\AuthServiceProvider.php

★ルートにGate設定★
C:\xampp\htdocs\ureserve\cms\routes\web.php

◆サンプルコード◆

Route::prefix('manager')
->middleware('can:manager-higher')
->group(function(){
    Route::get('index', function () {
        dd('manager');
    });    
});

Route::middleware('can:user-higher')
->group(function(){
    Route::get('index', function () {
        dd('user');
    });
});
