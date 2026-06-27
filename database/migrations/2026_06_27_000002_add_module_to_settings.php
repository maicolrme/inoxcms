<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inox_settings', function (Blueprint $table) {
            $table->string('module')->nullable()->after('autoload');
            $table->index('module');
        });
    }

    public function down(): void
    {
        Schema::table('inox_settings', function (Blueprint $table) {
            $table->dropIndex(['module']);
            $table->dropColumn('module');
        });
    }
};
