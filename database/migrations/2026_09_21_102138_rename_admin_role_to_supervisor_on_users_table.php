<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Renames the 'admin' role to 'supervisor' so the system's RBAC
     * matches Snap Momen's actual staff structure (counter staff vs.
     * supervisor), rather than a generic 'admin' label.
     */
    public function up(): void
    {
        // Widen the enum to allow both old and new values during the swap.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('staff', 'admin', 'supervisor') NOT NULL DEFAULT 'staff'");

        // Migrate existing data.
        DB::table('users')->where('role', 'admin')->update(['role' => 'supervisor']);

        // Narrow the enum to the final two values.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('staff', 'supervisor') NOT NULL DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('staff', 'admin', 'supervisor') NOT NULL DEFAULT 'staff'");
        DB::table('users')->where('role', 'supervisor')->update(['role' => 'admin']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('staff', 'admin') NOT NULL DEFAULT 'staff'");
    }
};
