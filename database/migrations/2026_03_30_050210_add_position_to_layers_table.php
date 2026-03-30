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
        Schema::table('layers', function (Blueprint $table) {
            // parent_id এর পরে position কলামটি যোগ হবে
            $table->integer('position')->default(0)->after('parent_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('layers', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
