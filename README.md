# symfony-test-n1
for demonstorating n1 problem in symfony


# mysqlはlocalで用意が必要
接続情報等検証時は下記の通り。
https://tektektech.com/wp-admin/post.php?post=3842


# 検証

mysqlの接続が終わり、migrationを終えていれば、下記コマンドでテスト用データが10000ユーザーごとにコメント10件分入る。
```
php bin/console doctrine:fixtures:load --append
```

データを入れたら下記URLにアクセスすると、出力が表示される。
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
