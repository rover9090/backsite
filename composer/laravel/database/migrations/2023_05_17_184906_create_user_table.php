<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        #Schema::create('user', function (Blueprint $table) {
        #    $table->id();
        #    $table->timestamps();
        #});
//    	Schema::create('client_user_data2', function (Blueprint $table) {
            //主鍵
//            $table->increments('id');
            //姓名
  //          $table->string('sName', 50);
            //帳號
//            $table->string('sAccount', 50)->unique();
            //密碼
//            $table->string('sPassword', 60);
            // //類型
            // $table->integer('type')->default(0);
            // //性別
            // $table->tinyInteger('sex')->default(0);
            // //身高
            // $table->decimal('height')->default(0);
            // //體重
            // $table->decimal('weight')->default(0);
            // //興趣
            // $table->string('interest', 100)->default('');
            // //介紹
            // $table->string('introduce', 500)->default('');
            // //圖片
            // $table->string('picture', 50)->default('');
            //啟用
//            $table->tinyInteger('nOnline')->default(1);
            //創建及修改日期
//            $table->timestamps();
#        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_user_data2');
    }
};
