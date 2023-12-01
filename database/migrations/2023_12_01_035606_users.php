<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('admin_username');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->enum('role', ['accounting', 'ovpf']);
        });
        
        DB::table('users')->insert([
            [
                'admin_username' => 'mara@accounting',
                'first_name' => 'Mara',
                'last_name' => 'Calinao',
                'password' => Hash::make('admin123'),
                'role' => 'accounting'
            ],
            [
                'admin_username' => 'luzviminda@ovpf',
                'first_name' => 'Luzviminda',
                'last_name' => 'Landicho',
                'password' => Hash::make('admin123'),
                'role' => 'ovpf'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
