# KinSei

KinSeiは、フリーランス向けの勤怠管理&請求書発行を行うWebアプリケーションです。

## 主な機能（実装済）

- 権限
    - admin
        - ユーザー管理など
    - user
        - 出勤・退勤記録＆勤怠PDF発行と請求書PDF発行

## 構成

- Laravel 13.17+
- PHP 8.5
- Filament 5.7+
- MySQL 8.4
- Laravel Sail 1.67+
- Docker

## wip

- userの請求書発行機能

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
