<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 管理者
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => '管理者ユーザー',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'memo' => 'システム管理者',
        ]);

        /*
        |--------------------------------------------------------------------------
        | フリーランスユーザー1
        | 請求元情報・振込先設定
        |--------------------------------------------------------------------------
        */

        $user1 = User::create([
            'name' => '一般ユーザー1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'memo' => 'フリーランスユーザー1',
            'registration_number' => 'T1234567890123',
            'postal_code' => '100-0001',
            'address' => '東京都千代田区1-1-1',
            'phone_number' => '03-1234-5678',
            'bank_name' => 'サンプル銀行',
            'bank_branch' => '本店',
            'bank_account_type' => '普通',
            'bank_account_number' => '1234567',
            'bank_account_holder' => 'サンプル ターロウ',
        ]);

        /*
        |--------------------------------------------------------------------------
        | フリーランスユーザー2
        | 請求元情報・振込先設定
        |--------------------------------------------------------------------------
        */

        $user2 = User::create([
            'name' => '一般ユーザー2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'memo' => 'フリーランスユーザー2',
            'registration_number' => 'T9876543210987',
            'postal_code' => '150-0001',
            'address' => '東京都渋谷区2-2-2',
            'phone_number' => '03-9876-5432',
            'bank_name' => 'テスト銀行',
            'bank_branch' => '支店',
            'bank_account_type' => '当座',
            'bank_account_number' => '7654321',
            'bank_account_holder' => 'テスト ハナコ',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ユーザー1：勤怠
        |--------------------------------------------------------------------------
        */

        Attendance::create([
            'user_id' => $user1->id,
            'in' => now()->setTime(9, 0, 0),
            'out' => null,
            'work_content' => '本日の作業中',
        ]);

        Attendance::create([
            'user_id' => $user1->id,
            'in' => now()->subDays(2)->setTime(9, 0, 0),
            'out' => now()->subDays(2)->setTime(19, 30, 0),
            'working_hours' => 9.5,
            'break_time' => 1.0,
            'work_content' => 'システム開発・打ち合わせ',
        ]);

        Attendance::create([
            'user_id' => $user1->id,
            'in' => now()->subDays(3)->setTime(10, 0, 0),
            'out' => now()->subDays(3)->setTime(18, 0, 0),
            'working_hours' => 7.0,
            'break_time' => 1.0,
            'work_content' => 'Webサイト制作',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ユーザー2：勤怠
        |--------------------------------------------------------------------------
        */

        Attendance::create([
            'user_id' => $user2->id,
            'in' => now()->subDay()->setTime(9, 0, 0),
            'out' => now()->subDay()->setTime(18, 0, 0),
            'working_hours' => 8.0,
            'break_time' => 1.0,
            'work_content' => 'Webサイト制作・修正作業',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ユーザー1：顧客（税抜・時給制）
        |--------------------------------------------------------------------------
        */

        $customer1 = Customer::create([
            'user_id' => $user1->id,
            'name' => '株式会社サンプルA',
            'postal_code' => '100-0001',
            'address' => '東京都千代田区サンプル1-1-1',
            'registration_number' => 'T1111111111111',
            'billing_type' => 'hourly',
            'hourly_rate' => 5000,
            'price_tax_type' => 'exclusive',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ユーザー1：顧客2（税込・月額制）
        |--------------------------------------------------------------------------
        */

        $customer2 = Customer::create([
            'user_id' => $user1->id,
            'name' => '株式会社サンプルB',
            'postal_code' => '150-0001',
            'address' => '東京都渋谷区サンプル2-2-2',
            'registration_number' => 'T2222222222222',
            'billing_type' => 'monthly',
            'monthly_rate' => 500000,
            'settlement_lower_hours' => 140,
            'settlement_upper_hours' => 180,
            'overtime_unit_price' => 3000,
            'deduction_unit_price' => 3000,
            'price_tax_type' => 'inclusive',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ユーザー2：顧客
        |--------------------------------------------------------------------------
        */

        $customer3 = Customer::create([
            'user_id' => $user2->id,
            'name' => '株式会社テストC',
            'postal_code' => '160-0001',
            'address' => '東京都新宿区テスト3-3-3',
            'registration_number' => 'T3333333333333',
            'billing_type' => 'monthly',
            'monthly_rate' => 500000,
            'settlement_lower_hours' => 140,
            'settlement_upper_hours' => 180,
            'overtime_unit_price' => 3000,
            'deduction_unit_price' => 3000,
            'price_tax_type' => 'inclusive',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ユーザー1：請求書1
        |--------------------------------------------------------------------------
        */

        $invoice1 = Invoice::create([
            'customer_id' => $customer1->id,
            'invoice_number' => 'INV-2026-001',
            'invoice_date' => now()->startOfMonth(),
            'due_date' => now()->startOfMonth()->addMonth()->subDay(),
            'billing_month' => now()->format('Y-m'),
            'settlement_method' => 'upper_lower',
            'subtotal' => 82500,
            'tax' => 8250,
            'total' => 90750,
            'status' => 'draft',
            'summary_contract' => 'サイトデザインおよび保守',
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice1->id,
            'description' => 'システム開発業務',
            'quantity' => 15,
            'unit_price' => 5000,
            'amount' => 75000,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice1->id,
            'description' => '打ち合わせ・サポート業務',
            'quantity' => 1.5,
            'unit_price' => 5000,
            'amount' => 7500,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ユーザー1：請求書2
        |--------------------------------------------------------------------------
        */

        $invoice2 = Invoice::create([
            'customer_id' => $customer2->id,
            'invoice_number' => 'INV-2026-002',
            'invoice_date' => now()->startOfMonth(),
            'due_date' => now()->startOfMonth()->addMonth()->subDay(),
            'billing_month' => now()->format('Y-m'),
            'settlement_method' => 'middle',
            'subtotal' => 100000,
            'tax' => 10000,
            'total' => 110000,
            'status' => 'draft',
            'summary_contract' => 'Webサイト制作および運用保守',
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'description' => 'Webサイト制作',
            'quantity' => 20,
            'unit_price' => 5000,
            'amount' => 100000,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ユーザー2：請求書3
        |--------------------------------------------------------------------------
        */

        $invoice3 = Invoice::create([
            'customer_id' => $customer3->id,
            'invoice_number' => 'INV-2026-003',
            'invoice_date' => now()->startOfMonth(),
            'due_date' => now()->startOfMonth()->addMonth()->subDay(),
            'billing_month' => now()->format('Y-m'),
            'settlement_method' => 'upper_lower',
            'subtotal' => 454545,
            'tax' => 45455,
            'total' => 500000,
            'status' => 'draft',
            'summary_contract' => '月額システム保守',
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice3->id,
            'description' => '月額業務委託料',
            'quantity' => 1,
            'unit_price' => 500000,
            'amount' => 500000,
        ]);
    }
}