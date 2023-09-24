# symfony-test-n1
for demonstorating n1 problem in symfony

# 初回

```
$ pwd
../symfony-test-n1

$ composer install

$ symfony server:start
```

以下へアクセス。
http://127.0.0.1:8000/lucky/number

# mysqlはlocalで用意が必要
接続情報等検証時は下記の通り。
https://tektektech.com/wp-admin/post.php?post=3842

接続情報は.envにあるとおりを想定。

```
DATABASE_URL="mysql://newuser:password@127.0.0.1:3306/symfony_test?serverVersion=8.0.33&charset=utf8mb4"
```

# N+1の検証について

mysqlの接続が終わり、migrationを終えていれば、下記コマンドでテスト用データが10000ユーザーごとにコメント10件分入る。
（"--append"をつけることでpurgeされなくなるので、もう一度実行すればさらに追加でデータを入れることができる）
```
php bin/console doctrine:fixtures:load --append
```

データをDBへ入れたら下記URLにアクセスすると、出力が表示される。
（件数が多くなるほど時間がかかるので注意, 目安として30000ユーザーで1000秒近く）

http://127.0.0.1:8000/lucky/number

出力イメージ
```
初回分(30000User取得)の処理時間(秒):
0.058187961578369

Comment300000件取得分の処理時間(秒) - N+1有り:
1003.2022941113

Comment300000件取得分の処理時間(秒) - N+1無し:
0.36501312255859
```

また、詳細のクエリは"config/packages/framework.yaml"にて下記をtrueで開発者ツール的なものが出てくるので、そこで確認すると良い。(件数が増えるとメモリ食いそうだったのでfalseにしている）
```
  profiler:
    collect: true
```
