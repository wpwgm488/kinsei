@extends('layouts.app')
@section('title', __('messages.app_name'))
@section('content')

<div style="min-height: calc(100vh - 120px); display: flex; justify-content: center; align-items: flex-start; width: 100%;">

    <div style="width: 100%; max-width: 800px; padding: 40px 24px; box-sizing: border-box;">

        <h1 style="font-size: 2.5rem; font-weight: bold; color: #1f2937; margin-bottom: 24px;">
            KinSei
        </h1>

        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <p style="color: #374151; line-height: 1.8; margin: 0;">
                KinSeiは、SES向けの勤怠管理&請求書発行を行うWebアプリケーションです。
            </p>
        </div>

        {{-- 主な機能（実装済） --}}
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #374151; margin-bottom: 16px;">
                主な機能（実装済）
            </h2>

            <pre style="background: #f3f4f6; padding: 16px; border-radius: 8px; overflow-x: auto; line-height: 1.6; color: #374151;">権限
├── admin
│   ├── ユーザー管理
│   └── グループ管理
│
├── manager
│   └── ユーザー管理
│
└── user
    ├── 出勤・退勤記録
    ├── 休憩時間記録
    ├── 月間の稼働時間・休憩時間集計
    └── 作業内容の記録</pre>
        </div>

        {{-- 主な機能（未実装） --}}
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #374151; margin-bottom: 16px;">
                主な機能（未実装）
            </h2>

            <ul style="color: #4b5563; line-height: 2; padding-left: 24px; margin: 0;">
                <li>ユーザーによる勤怠(作業報告書)の申請・キャンセル</li>
                <li>G管理者による勤怠(作業報告書)の承認・却下</li>
                <li>勤怠データのPDF出力</li>
                <li>請求書のPDF出力</li>
                <li>(通知機能?)</li>
                <li>時間計算の丸め処理&切上切捨時間保存処理</li>
            </ul>
        </div>

        {{-- 構成 --}}
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #374151; margin-bottom: 16px;">
                構成
            </h2>

            <ul style="color: #4b5563; line-height: 2; padding-left: 24px; margin: 0;">
                <li>Laravel 13.17+</li>
                <li>PHP 8.5</li>
                <li>Filament 5.7+</li>
                <li>MySQL 8.4</li>
                <li>Laravel Sail 1.67+</li>
                <li>Docker</li>
            </ul>
        </div>

        {{-- Docker --}}
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #374151; margin-bottom: 16px;">
                Docker
            </h2>

            <pre style="background: #f3f4f6; padding: 16px; border-radius: 8px; overflow-x: auto; line-height: 1.6; color: #374151;">kinsei/
│
├── Laravel
│   └── laravel.test コンテナ
│
└── MySQL
    └── mysql コンテナ</pre>
        </div>

        {{-- コンテナ構成 --}}
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #374151; margin-bottom: 16px;">
                コンテナ構成
            </h2>

            <pre style="background: #f3f4f6; padding: 16px; border-radius: 8px; overflow-x: auto; line-height: 1.6; color: #374151;">ブラウザ
│
│ http://localhost
↓
Laravel
│
├── Filament
├── 勤怠管理
│
└── MySQL
    └── mysql コンテナ</pre>
        </div>

        {{-- Docker構成 --}}
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #374151; margin-bottom: 16px;">
                Docker構成
            </h2>

            <pre style="background: #f3f4f6; padding: 16px; border-radius: 8px; overflow-x: auto; line-height: 1.6; color: #374151;">Docker
├── laravel.test
│   └── Laravel / PHP / Laravel Sail
│
└── mysql
    └── MySQL 8.4</pre>
        </div>

        {{-- テスト用ユーザー --}}
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #374151; margin-bottom: 16px;">
                テスト用ユーザー
            </h2>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; color: #374151;">
                    <thead>
                        <tr style="background: #f3f4f6;">
                            <th style="padding: 10px; border: 1px solid #d1d5db; text-align: left;">名前</th>
                            <th style="padding: 10px; border: 1px solid #d1d5db; text-align: left;">メール</th>
                            <th style="padding: 10px; border: 1px solid #d1d5db; text-align: left;">権限</th>
                            <th style="padding: 10px; border: 1px solid #d1d5db; text-align: left;">グループ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">管理者ユーザー</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">admin@example.com</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">admin</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">なし</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">開発部マネージャー</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">manager@example.com</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">manager</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">開発部</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">開発部一般ユーザー</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">user1@example.com</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">user</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">開発部</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">営業部一般ユーザー</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">user2@example.com</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">user</td>
                            <td style="padding: 10px; border: 1px solid #d1d5db;">営業部</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p style="color: #4b5563; margin: 16px 0 0 0;">
                ※ テスト用ユーザーのパスワードは <strong>password</strong> です。
            </p>
        </div>

        {{-- トップへ --}}
        <div style="text-align: center; margin-top: 32px;">
            <a href="/" style="display: inline-block; padding: 10px 20px; background-color: #4b5563; color: #ffffff; border-radius: 8px; text-decoration: none;">
                トップへ戻る
            </a>
        </div>

    </div>
</div>
@endsection