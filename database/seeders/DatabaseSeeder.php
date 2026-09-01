<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // テスト用グループを作成
        $devGroup = Group::create(['name' => '開発部']);
        $salesGroup = Group::create(['name' => '営業部']);

        // 1. システム管理者 (Admin)
        User::create([
            'name' => '管理者ユーザー',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'group_id' => null, // 管理者は特定グループに属さなくてもOK
        ]);

        // 2. マネージャー (Manager) - 開発部
        $manager = User::create([
            'name' => '開発部マネージャー',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'group_id' => $devGroup->id,
        ]);

        // 3. 一般ユーザー (User) - 開発部（マネージャーと同じグループ）
        User::create([
            'name' => '開発部一般ユーザー',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'group_id' => $devGroup->id,
        ]);

        // 4. 一般ユーザー (User) - 営業部（別グループ）
        User::create([
            'name' => '営業部一般ユーザー',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'group_id' => $salesGroup->id,
        ]);
        Attendance::create([
            'user_id' => $manager->id,
            'in' => now()->subDay()->setTime(22, 0, 0),
        ]);
        // 6. 退勤済み・休憩あり・残業あり
        foreach ($devGroup->users as $user) {
            Attendance::create([
                'user_id' => $user->id,
                'in' => now()->subDays(2)->setTime(9, 0, 0),
                'out' => now()->subDays(2)->setTime(19, 30, 0),
                'break_start' => now()->subDays(2)->setTime(12, 0, 0),
                'break_end' => now()->subDays(2)->setTime(13, 0, 0),
                'break_time' => 1.00,
                'working_hours' => 9.50,
            ]);
        }
    }
}