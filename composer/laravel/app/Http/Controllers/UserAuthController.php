<?php

namespace App\Http\Controllers;

use Mail;
use Hash;
use Validator;
// use App\Http\Controllers\Controller;#new
use App\Module\ShareData;
use App\Models\User;
use App\Enum\ESexType;#自己加ㄉ資料夾
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;#LOG::
use Illuminate\Support\Facades\DB;#DB::

class UserAuthController extends Controller
{
	public $page = "";	
	//註冊畫面
	public function signUpPage()
	{
		$name = 'sign_up';
		$binding = [
			'title' => ShareData::TITLE,
			'page' => $this->page,
			'name' => $name,
			'user' => $this->GetUserData(),
		];
		return view('user.sign-up', $binding);
	}

	//處理註冊資料
	public function signUpProcess()
	{
		//接收輸入資料
		$input = request()->all();

		//驗證規則
		$rules = [
			//暱稱
			'name' => [
				'required',
				'max:50',
			],
			//帳號(E-mail)
			'account' => [
				'required',
				'max:50',
				'email',
			],
			//密碼
			'password' => [
				'required',
				'min:5',
			],
			//密碼驗證
			'password_confirm' => [
				'required',
				'same:password',
				'min:5'
			],
		];
		//驗證資料
		$validator = Validator::make($input, $rules);

		if($validator->fails())
		{
			//資料驗證錯誤
			return redirect('/user/auth/sign-up')
				->withErrors($validator)
				->withInput();
		}
		$input['password'] = Hash::make($input['password']);
		$aInput = array(
			'sName' => $input['name'],
	  		'sAccount' => $input['account'],
	  		'sPassword' => $input['password'],
			'nOnline' => 1,
		);

		Log::notice(print_r($aInput, true));

    		//啟用紀錄SQL語法
    		DB::enableQueryLog();

		# 沒有內建begin transaction
		# 沒有錯誤檢查
		User::create($aInput);

    		//取得目前使用過的SQL語法
    		Log::notice(print_r(DB::getQueryLog(), true));
		// exit;
		//寄送註冊通知信
		$mail_binding = [
			'name' => $input['name']
		];
	
		// Mail::send('email.signUpEmailNotification', $mail_binding,
		// function($mail) use ($input){
		// 	//收件人
		// 	$mail->to($input['account']);
		// 	//寄件人
		// 	$mail->from('henrychang0202@gmail.com');
		// 	//郵件主旨
		// 	$mail->subject('恭喜註冊Laravel部落格成功!');
		// });
	
		//重新導向到登入頁
		return redirect('/user/auth/sign-in');
	}

	//使用者登入畫面
	public function signInPage()
	{
		$name = 'sign_in';
		$binding = [
			'title' => ShareData::TITLE,
			'page' => $this->page,
			'name' => $name,
			'user' => $this->GetUserData(),
		  ];
		return view('user.sign-in', $binding);
	}

	//處理登入資料
	public function signInProcess()
	{
		//接收輸入資料
		$input = request()->all();

		//驗證規則
		$rules = [
		    //帳號(E-mail)
		    'account' => [
			  'required',
			  'max:50',
			  'email',
		    ],
		    //密碼
		    'password' => [
			  'required',
			  'min:5',
		    ],
		];
	  
		//驗證資料
		$validator = Validator::make($input, $rules);
	  
		if($validator->fails())
		{
		    //資料驗證錯誤
			return redirect('/user/auth/sign-in')
			  ->withErrors($validator)
			  ->withInput();
		}

		//取得使用者資料
		$User = User::where('sAccount', $input['account'])->first();

		if(!$User)
		{
		//帳號錯誤回傳錯誤訊息
			$error_message = [
				'msg' => [
				'帳號輸入錯誤',
				],
			];

			return redirect('/user/auth/sign-in')
				->withErrors($error_message)
				->withInput();
		}

		
		//檢查密碼是否正確
		$is_password_correct = Hash::check($input['password'], $User->sPassword);

		if(!$is_password_correct)
		{
			//密碼錯誤回傳錯誤訊息
			$error_message = [
				'msg' => [
				'密碼輸入錯誤',
				],
			];

			return redirect('/user/auth/sign-in')
				->withErrors($error_message)
				->withInput();
		}

		//session紀錄會員編號
		session()->put('user_id', $User->nId);

		//重新導向到原先使用者造訪頁面，沒有嘗試造訪頁則重新導向回自我介紹頁
		return redirect()->intended('/admin/user');
	}

	//登出
	public function signOut()
	{
		//清除Session
		session()->forget('user_id');

		//重新導向回首頁
		return redirect('/');
	}
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(User $user)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(User $user)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, User $user)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(User $user)
	{
		//
	}
}
