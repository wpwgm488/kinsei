# KinSei

KinSeiは、SES向けの勤怠管理&請求書発行を行うWebアプリケーションです。
## 画面イメージ
<img width="20%" alt="sample1" src="https://github.com/user-attachments/assets/2f77c6fe-b21d-4775-b437-94f371936e12" />
<img width="30%" alt="sample2" src="https://github.com/user-attachments/assets/2d6cf2c6-b852-4a31-a100-89880f082ff8" />
<img width="30%" alt="sample3" src="https://github.com/user-attachments/assets/8a68ecb7-1fd0-4bbf-a44e-e901e4de5210" />


## 主な機能（実装済）

- 権限
    - admin
        - ユーザー管理
        - グループ管理
    - manager
        - ユーザー管理
    - user
        - 出勤・退勤記録
        - 休憩時間記録
        - 月間の稼働時間・休憩時間集計
        - 作業内容の記録

## 主な機能（未実装）

- ユーザーによる勤怠(作業報告書)の申請・キャンセル
- G管理者による勤怠(作業報告書)の承認・却下
- 勤怠データのPDF出力
- 請求書のPDF出力
- (通知機能?)

## 構成

- Laravel 13.17+
- PHP 8.5
- Filament 5.7+
- MySQL 8.4
- Laravel Sail 1.67+
- Docker

## Docker

```text
kinsei/

│
├── Laravel
│   └── laravel.test コンテナ
│
└── MySQL
    └── mysql コンテナ
```

### コンテナ構成

```text
ブラウザ
│
│ http://localhost
↓
Laravel
│
├── Filament
├── 勤怠管理
│
└── MySQL
    └── mysql コンテナ
```

### Docker構成

```text
Docker

├── laravel.test
│   └── Laravel / PHP / Laravel Sail
│
└── mysql
    └── MySQL 8.4
```

## テスト用ユーザー

| 名前               | メール              | 権限    | グループ |
| ------------------ | ------------------- | ------- | -------- |
| 管理者ユーザー     | admin@example.com   | admin   | なし     |
| 開発部マネージャー | manager@example.com | manager | 開発部   |
| 開発部一般ユーザー | user1@example.com   | user    | 開発部   |
| 営業部一般ユーザー | user2@example.com   | user    | 営業部   |

※ テスト用ユーザーのパスワードは `password` です。

## 起動

Laravel Sailを使用して起動します。

```bash
./vendor/bin/sail up -d
```

アプリケーション:

```text
http://localhost
```

## データベース

MySQL 8.4を使用しています。

```text
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=kinsei
DB_USERNAME=sail
```

ホスト側からMySQLへ接続する場合:

```text
localhost:3309
```
