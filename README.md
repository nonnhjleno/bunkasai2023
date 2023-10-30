<h1 align="center">パックマンランキング用APIサーバー</h1>

# このアプリについて

- 利用者登録されたデータとパックマンアプリを接続するRESTful APIを提供するLaravelで作られたサーバーです。

# 起動のための初期設定と起動方法

0. Gitでクローンした場合には `composer install` で必要なライブラリを一括でインストールする。
1. .envファイルを .env.exampleから複製後、必要事項を書き込む。
    - DB_HOST
    - DB_PORT
    - DB_DATABASE
    - DB_USERNAME
    - DB_PASSWORD
2. `php artisan serve` で起動する（他のPCと接続する場合には `php artisan serve --host=0.0.0.0` ）。
3. `php artisan migrate` で必要なデータベースとテーブルを作成する。

## エンドポイント

### 1. ユーザー一覧取得
- URL: `/api/test`
- メソッド: GET
- 説明: ユーザーの一覧を得点の降順で取得します。[ランキングアプリ](https://github.com/nonnhjleno/bunkasai2023ranking)ではこれが使われています。
- リクエスト: `/api/test`
- レスポンス: Status: 200 OK
```json
[
  {
      "id": 1,
      "username": "ユーザー1",
      "score": 100
  },
  {
      "id": 2,
      "username": "ユーザー2",
      "score": 75
  },
  ...
]
```

### 2. ユーザー検索
- URL: `/api/searchUser`
- メソッド: GET
- 説明: ユーザーを検索するためのエンドポイントです。ユーザー名に基づいて検索を行い、結果を取得します。複数のキーワードを指定でき、部分一致検索を行います。
- リクエスト: `/api/searchUser?username=キーワード`
- クエリパラメータ: `username`（必須）: 検索キーワード。複数のキーワードをスペースで区切って指定できます。
- レスポンス: Status: 200 OK
```json
[
  {
      "id": 1,
      "username": "ユーザー1",
      "score": 100
  },
  {
      "id": 2,
      "username": "ユーザー2",
      "score": 75
  },
  ...
]
```

### 3. ユーザー得点更新
- URL: `/api/createUser`
- メソッド: POST
- 説明: 新しいユーザーを作成します。ユーザー名は一意である必要があります。
- リクエスト: `/api/createUser?username=キーワード`
- クエリパラメータ: `username`（必須）: 作成するユーザーの名前。
- レスポンス: Status: 201 Created
```json
{
    "id": 3,
    "username": "新しいユーザー名",
    "score": 0
}
```

### 4. ユーザー作成
- URL: `/api/updateScore/{id}`
- メソッド: PUT
- 説明: ユーザーの得点を更新します。新しい得点が現在の得点よりも低い場合は更新されません。
- リクエスト: `/api/updateScore/1`
- クエリパラメータ: `score`（必須）: 更新するユーザーの点数。
- レスポンス: Status: 200 OK
```json
{
    "id": 1,
    "username": "ユーザー1",
    "score": 120
}
```

### 4. ユーザー削除
- URL: `/api/deleteUser/{id}`
- メソッド: DELETE
- 説明: ユーザーを削除します。削除対象はオプションで指定できます。
    1. 点数を0点にする（ユーザー得点更新で0点に更新しても挙動は同じです）。
    2. ユーザーをテーブルから完全に削除。
- クエリパラメータ: `option`（必須）: `score`の場合には点数が0点に更新されます（ランキングに表示されなくなります）。`all`の場合にはテーブルからユーザーが削除されます。
- レスポンス:
1. Status: 200 OK
```json
{
    "message": "id = 1の点数が0点になりました。"
}
```
2. Status: 200 OK
```json
{
    "message": "id = 1のデータが削除されました。"
}
```

# Security Vulnerabilities

部活外のインターネットから接続されることを想定していません。

# Author

- 作成者: 宮城県工業高等学校 情報技術科3年16番 金野太誓
- 所属: 情報研究部 Webチーム
- E-mail: taaaisei999@gmail.com
- GitHub: [Nonnhjleno](https://github.com/nonnhjleno/)

# License
The source code is licensed MIT. The website content is licensed CC BY 4.0,see LICENSE.
