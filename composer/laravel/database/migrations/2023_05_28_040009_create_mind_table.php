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
    	Schema::create('mind', function (Blueprint $table) {
            //主鍵
            $table->increments('nId');
            //使用者代號
            $table->integer('nUid');
            //內容
            $table->string('sContent', 500)->default('');
            //啟用
            $table->integer('nOnline')->default(1);
            //創建及修改日期
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mind');
    }
};
