<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomersCountFieldsOnAgencyAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency_accounts', function (Blueprint $table) {
            $table->integer('member_customers_count')->default(0);
            $table->integer('max_member_customers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agency_accounts', function (Blueprint $table) {
            $table->dropColumn('member_customers_count');
            $table->dropColumn('max_member_customers');
        });
    }
}
