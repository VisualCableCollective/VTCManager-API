<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->string('description')->default('');
            $table->decimal('amount', 9, 2, true);
            $table->foreignIdFor(\App\Models\User::class, 'sender_id');
            $table->foreignIdFor(\App\Models\User::class, 'receiver_id');
            $table->enum('type', ['Common', 'JobPayout', 'Salary']);
            $table->enum('status', ['Pending', 'PendingSystem', 'Transferred'])->default('Pending');
            $table->string('system_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('money_transactions');
    }
};
