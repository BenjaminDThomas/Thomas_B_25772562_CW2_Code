<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Check if the column exists
        if (Schema::hasColumn('responses', 'guest_id')) {
            Schema::table('responses', function (Blueprint $table) {
                $table->string('guest_id')->nullable()->change();
            });
        } else {
            Schema::table('responses', function (Blueprint $table) {
                $table->string('guest_id')->nullable()->after('user_id');
            });
        }
    }    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('responses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropColumn('guest_id');
        });
    }
};

