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
        Schema::table('user_logins', function (Blueprint $table) {
            if (!Schema::hasColumn('user_logins', 'click_id')) {
                $table->string('click_id', 100)->nullable();
            }
        });

        Schema::table('user_logins', function (Blueprint $table) {
            if (!Schema::hasColumn('user_logins', 'sub_id')) {
                $table->string('sub_id', 100)->nullable();
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
        Schema::table('user_logins', function (Blueprint $table) {
            if (Schema::hasColumn('user_logins', 'click_id')) {
                $table->dropColumn('click_id');
            }
            if (Schema::hasColumn('user_logins', 'sub_id')) {
                $table->dropColumn('sub_id');
            }
        });
    }
};
