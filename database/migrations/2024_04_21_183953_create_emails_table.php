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
        Schema::create('emails', function (Blueprint $table) {
            // protected $fillable = [
            //     'subject',
            //     'body',
            //     'sender_email',
            //     'recipient_email',
            //     'metadata',
            // ];
            $table->id();
            $table->timestamps();
            $table->string('subject');
            $table->string('body');
            $table->string('sender_email');
            $table->string('recipient_email');
            $table->string('format');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
