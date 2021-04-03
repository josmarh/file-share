<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fs_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_hash');
            $table->unsignedBigInteger('data_source_id');
            $table->decimal('file_size',  $precision = 17, $scale = 2);
            $table->string('file_type');
            $table->unsignedBigInteger('user_id');
            $table->string('direct_user_mail')->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fs_transactions');
    }
}
