<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::updateOrCreate(
            ['email' => 'branlyn@invitaplus.test'],
            [
                'name' => 'Branlyn',
                'display_name' => 'Branlyn',
                'password' => 'InvitaPlus123!',
                'email_verified_at' => now(),
            ],
        );

        $superadmin->syncRoles(['superadmin']);

        $customer = User::updateOrCreate(
            ['email' => 'anahi@invitaplus.test'],
            [
                'name' => 'Anahi',
                'display_name' => 'Anahi',
                'password' => 'InvitaPlus123!',
                'email_verified_at' => now(),
            ],
        );

        $customer->syncRoles(['customer']);
    }
}
