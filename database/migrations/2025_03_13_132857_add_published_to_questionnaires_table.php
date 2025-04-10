<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishedToQuestionnairesTable extends Migration
{
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            if (!Schema::hasColumn('questionnaires', 'published')) {
                $table->tinyInteger('published')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('published');
        });
    }
}


