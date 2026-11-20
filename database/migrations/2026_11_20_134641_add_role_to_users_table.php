<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // add_role_to_users_table
public function up(): void {
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['super_admin', 'admin', 'production'])
              ->default('production')->after('email');
    });
}
public function down(): void {
    Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
}
};
