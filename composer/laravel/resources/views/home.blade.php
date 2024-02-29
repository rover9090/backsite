<!-- 指定繼承 layout.master 母模板 -->
@extends('layout.master')

<!-- 傳送資料到母模板，並指定變數為title -->
@section('title', $title)

<!-- 傳送資料到母模板，並指定變數為content -->
@section('content')

<div class="main_region">
@foreach($userList as $user)
<div class="col-10">
    <img class="circle_img" alt="{{ $user->sName }}" title="{{ $user->sName }}" onclick="ChangeUser({{ $user->nId }})"
    @if($user->sPicture == "")
      src="/gino/composer/laravel/public/images/nopic.png" 
    @else
      src="/gino/composer/laravel/public/{{ $user->sPicture }}" 
    @endif
    />
</div>
@endforeach
</div>

<script>
function ChangeUser(id)
{
    location.href = "/gino/composer/laravel/public/" + id + "/user";
}
</script>
@endsection