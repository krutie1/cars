<?php

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'roles' => [UserRolesEnum::ADMIN->value, \App\Enums\UserRolesEnum::MANAGER->value],
            'phone_number' => '87471337514',
            'password' => Hash::make('test')
        ]);

        \App\Models\User::create([
            'name' => 'User',
            'roles' => [UserRolesEnum::MANAGER->value],
            'phone_number' => '87713664077',
            'password' => Hash::make('test')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        User::query()->delete();
    }
};
