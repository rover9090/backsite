<!DOCTYPE html>
<html>
    <head>
	  <meta charset="utf-8">
	  <title>@yield('title')</title>
	  <script src="/gino/composer/laravel/public/js/app.js"></script>
	  <script src="http://cdn.bootcss.com/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
	  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	  <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script>
	  <link rel="stylesheet" href="/gino/composer/laravel/public/css/app.css?<?php echo date("mjs", time())?>">
    </head>
    <boby>
	  <div class="toolbar_section">
		<span class="toolbar_title">@yield('title')</span>
		<span class="toolbar_title2"></span>
		
		<div class="toolbar_right">
		    <span class="toolbar_text">
			  {{ $user != null ? $user->sName."，您好！" : "未登入" }}
		    </span>
		</div>
	  </div>

	  <div class="container">
		<div class="col-sm-1 form background_white">
		    <ul class="nav nav-pills nav-stacked">
		    @if($page == "admin" && session()->has('user_id'))
			  <!-- 自我介紹 -->
			  <li 
			  @if($name == "user")
				class="active"
			  @endif
			  >
				<a href="/gino/composer/laravel/public/admin/user">自我介紹</a>
			  </li>
			  <!-- 心情隨筆 -->
			  <li 
			  @if($name == "mind")
				class="active"
			  @endif
			  >
				<a href="/gino/composer/laravel/public/admin/mind">心情隨筆</a>
			  </li>
			  <!-- 回到前台 -->
			  <li>
				<a href="/gino/composer/laravel/public/">部落格前台</a>
			  </li>
		    @elseif($page == "user")
			  <!-- 首頁 -->
			  <li>
				<a href="/gino/composer/laravel/public/">部落格</a>
			  </li>
			  <!-- 自我介紹 -->
			  <li 
			  @if($name == "user")
				class="active"
			  @endif
			  >
				<a href="/gino/composer/laravel/public/{{ $userData->nId }}/user">自我介紹</a>
			  </li>
			  <!-- 心情隨筆 -->
			  <li 
			  @if($name == "mind")
				class="active"
			  @endif
			  >
				<a href="/gino/composer/laravel/public/{{ $userData->nId }}/mind">心情隨筆</a>
			  </li>
			  <!-- 留言板 -->
			  <li 
			  @if($name == "board")
				class="active"
			  @endif
			  >
				<a href="/gino/composer/laravel/public/{{ $userData->nId }}/board">留言板</a>
			  </li>
		    @else
			  <!-- 首頁 -->
			  <li 
			  @if($name == "home")
				class="active"
			  @endif
			  >
				<a href="/gino/composer/laravel/public/">部落格</a>
			  </li>
			  @if(session()->has('user_id'))
				<!-- 自我介紹 -->
				<li>
				    <a href="/gino/composer/laravel/public/admin/user">進入後台</a>
				</li>
			  @endif
		    @endif
		    @if(session()->has('user_id'))
			  <!-- 登出 -->
			  <li>
				<a href="/gino/composer/laravel/public/user/auth/sign-out">登出</a>
			  </li>
		    @else
			  <!-- 註冊 -->
			  <li 
			  @if($name == "sign_up")
				class="active"
			  @endif
			  >
				<a href="/gino/composer/laravel/public/user/auth/sign-up">註冊</a>
			  </li>
			  <!-- 登入 -->
			  <li 
			  @if($name == "sign_in")
				class="active"
			  @endif
			  >
				<a href="/gino/composer/laravel/public/user/auth/sign-in">登入</a>
			  </li>
		    @endif
		    </ul>
		</div>
		<div class="col-sm-11 background_white2">
		    @yield('content')
		</div>
	  </div>
    </body>
</html>