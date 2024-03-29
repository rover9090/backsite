<?PHP use App\Enum\ESexType; ?>
<!-- 指定繼承 layout.master 母模板 -->
@extends('layout.master')

<!-- 傳送資料到母模板，並指定變數為title -->
@section('title', $title)

<!-- 傳送資料到母模板，並指定變數為content -->
@section('content')
<form id="form1" method="post" action="" enctype="multipart/form-data">
<!-- 自動產生 csrf_token 隱藏欄位-->
{!! csrf_field() !!}
<div class="normal_form">
    <div class="form_title">自我介紹</div>
    <div class="col-sm-6">
        <div class="form_label">帳號</div>
        <div class="form_textbox_region">
            <input name="account" class="form_textbox" type="text" value="{{ $user->sAccount }}" readonly="true" placeholder="請輸入帳號"/>
        </div>
    </div>
    <div class="div_clear"/>
    <div class="col-sm-2">
        <div class="form_label">性別</div>
        <div class="form_textbox_region">
            <select class="form_select" id="sex" name="sex" placeholder="請選擇性別">
                <option value="{{ ESexType::MALE }}" 
                @if($user->nSex == ESexType::MALE)
                    selected
                @endif
                >男性</option>
                <option value="{{ ESexType::FEMALE }}" 
                @if($user->nSex == ESexType::FEMALE)
                    selected
                @endif
                >女性</option>
            </select>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form_label">身高</div>
        <div class="form_textbox_region">
            <input name="height" class="form_textbox" type="number" value="{{ $user->nHeight }}" placeholder="請輸入身高"/>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form_label">體重</div>
        <div class="form_textbox_region">
            <input name="weight" class="form_textbox" type="number" value="{{ $user->nWeight }}" placeholder="請輸入體重"/>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form_label">興趣</div>
        <div class="form_textbox_region">
            <input name="interest" class="form_textbox" type="text" value="{{ $user->sInterest }}" placeholder="請輸入興趣"/>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form_label">
            圖片　
            <input type="file" name="file" id="file" class="inputfile" />
            <label for="file">上傳圖片</label>
        </div>
        <div class="form_textbox_region">
            <img id="file_review" class="upload_img" 
            @if($user->sPicture == "")
                src="/gino/composer/laravel/public/images/nopic.png" 
            @else
                src="/gino/composer/laravel/public/{{$user->sPicture}}" 
            @endif
            />
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form_label">自我介紹</div>
        <div class="form_textbox_region">
            <textarea name="introduce" class="form_textarea" placeholder="請輸入自我介紹">{{ $user->sIntroduce }}</textarea>
        </div>
    </div>
    <div class="div_clear"/>
    <div class="form_error">
        <!-- 錯誤訊息模板元件 -->
        @include('layout.ValidatorError')
    </div>
    <div class="btn_group">
        <button type="submit" class="btn btn-primary btn_form">儲存</button>
    </div>
<div>
</form>

<script>
//預覽圖片
$("#file").change(function(){
      //當檔案改變後，做一些事 
     readURL(this);   // this代表<input id="file">
});

function readURL(input){
  if(input.files && input.files[0]){
    var reader = new FileReader();
    reader.onload = function (e) {
       $("#file_review").attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}


</script>
@endsection