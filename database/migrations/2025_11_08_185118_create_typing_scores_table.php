<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('typing_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('score');
            $table->integer('wpm');
            $table->integer('time_taken')->default(60);
            $table->timestamps();
            
            $table->index(['user_id', 'wpm']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('typing_scores');
    }
};