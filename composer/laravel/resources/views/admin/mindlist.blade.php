<!-- 指定繼承 layout.master 母模板 -->
@extends('layout.master')

<!-- 傳送資料到母模板，並指定變數為title -->
@section('title', $title)

<!-- 傳送資料到母模板，並指定變數為content -->
@section('content')

<div class="normal_form">
    <div class="form_title">心情隨筆列表</div>
    <div class="btn_group">
        <button type="button" class="btn btn-primary btn_form" onclick="AddData()">新增</button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover form_label">
            <thead>
                <tr>
                    <th>日期</th>
                    <th>內容</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($mindPaginate as $LPdata)
                <tr>
                    <td>{{ $LPdata->created_at }}</td>
                    <td>{{ $LPdata->sContent }}</td>
                    <td class="right">
                    <button type="button" class="btn btn-success btn_form" onclick="EditData({{ $LPdata->nId }})">修改</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- 分頁頁數按鈕 --}}
        {{ $mindPaginate->links() }}
    </div>
<div>

<link href="/gino/composer/laravel/public/css/iao-alert.css" rel="stylesheet" type="text/css" />
<script src="/gino/composer/laravel/public/js/iao-alert.jquery.js"></script>

<script>
    $( document ).ready(function() {
    <?PHP 
        if($result == "success")
        {
            echo('Success("修改資料成功!")');
        }
    ?>
    });

    //顯示吐司訊息
    function Success(message)
    {
        $.iaoAlert({
            type: "success",
            mode: "dark",
            msg: message,
        })
    }

    //新增心情隨筆
    function AddData()
    {
        location.href = "/gino/composer/laravel/public/admin/mind/add";
    }
    //編輯心情隨筆
    function EditData($id)
    {
        location.href = "/gino/composer/laravel/public/admin/mind/" + $id + "/edit";
    }
</script>
@endsection