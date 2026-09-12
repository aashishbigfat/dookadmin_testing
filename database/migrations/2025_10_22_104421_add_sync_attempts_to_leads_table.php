<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSyncAttemptsToLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Add sync_attempts if it doesn't exist
            if (!Schema::hasColumn('leads', 'sync_attempts')) {
                $table->integer('sync_attempts')->default(0)->after('sync_error');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'sync_attempts')) {
                $table->dropColumn('sync_attempts');
            }
        });
    }
}