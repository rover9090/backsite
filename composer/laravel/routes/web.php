<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

# 不使用use寫法
// Route::get('/', 'App\Http\Controllers\HomeController@indexPage');


# 使用use寫法
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\AdminController;


// Route::get('/sign-up', [HomeController::class, 'indexPage']);

/*
寫法一
Route::get('/user/auth/sign-up', 'UserAuthController@signUpPage');
Route::post('/user/auth/sign-up', 'UserAuthController@signUpProcess');
Route::get('/user/auth/sign-in', 'UserAuthController@signInPage');
Route::post('/user/auth/sign-in', 'UserAuthController@signInProcess');
Route::get('/user/auth/sign-out', 'UserAuthController@signOut');
下面group是寫法2
*/
Route::group(['prefix' => '/user'], function(){
	//使用者驗證
	# 舊版laravel
	// Route::group(['prefix' => 'auth'], function(){
	//     Route::get('/sign-up', 'UserAuthController@signUpPage');
	//     Route::post('/sign-up', 'UserAuthController@signUpProcess');
	//     Route::get('/sign-in', 'UserAuthController@signInPage');
	//     Route::post('/sign-in', 'UserAuthController@signInProcess');
	//     Route::get('/sign-out', 'UserAuthController@signOut');
	// });
	# 新版laravel
	Route::group(['prefix' => 'auth'], function(){
		// Route::get('/sign-up', [UserAuthController::class, 'index']);
		//使用者註冊畫面
		Route::get('/sign-up', [UserAuthController::class, 'signUpPage']);
		//處理註冊資料
		Route::post('/sign-up', [UserAuthController::class, 'signUpProcess']);
		//使用者登入畫面
		Route::get('/sign-in', [UserAuthController::class, 'signInPage']);
		//處理登入資料
		Route::post('/sign-in', [UserAuthController::class, 'signInProcess']);
		//處理登出資料
		Route::get('/sign-out', [UserAuthController::class, 'signOut']);
	});
});

Route::group(['middleware'=>['auth.admin']], function(){
	Route::group(['prefix' => '/admin'], function(){
		//自我介紹相關
		Route::group(['prefix' => 'user'], function(){
			//自我介紹頁面
			Route::get('/', [AdminController::class, 'editUserPage']);
			//處理自我介紹資料
			Route::post('/', [AdminController::class, 'editUserProcess']);
		});
		Route::group(['prefix' => 'mind'], function(){
			//心情隨筆列表頁面
			Route::get('/', [AdminController::class, 'mindListPage']);
			//新增心情隨筆資料
			Route::get('/add', [AdminController::class, 'addMindPage']);
			//處理心情隨筆資料
			Route::post('/edit', [AdminController::class, 'editMindProcess']);
			//單一資料
			Route::group(['prefix' => '{mind_id}'], function(){
				//編輯心情隨筆資料
				Route::get('/edit', [AdminController::class, 'editMindPage']);
				//刪除心情隨筆資料
				Route::get('/delete', [AdminController::class, 'deleteMindProcess']);
			});
		});
	});
});

// Route::get('/', [HomeController::class, 'indexPage']);
Route::group(['prefix' => '/'], function(){
	//首頁
	Route::get('/', [HomeController::class, 'indexPage']);
	//單一使用者資料
	Route::group(['prefix' => '{user_id}'], function(){
	    //自我介紹
	    Route::get('/user', [HomeController::class, 'userPage']);
	    //心情隨筆
	    Route::get('/mind', [HomeController::class, 'mindPage']);
	    //留言板
	    Route::get('/board', [HomeController::class, 'boardPage']);
	    //編輯留言板
	    Route::post('/board', [HomeController::class, 'boardProcess']);
	});
  });
?>