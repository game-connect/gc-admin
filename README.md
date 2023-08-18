# gc-admin
Game ConnectのAdminサーバー。

## URL  
ローカル：[http://localhost:8006](http://localhost:8006)  
## 環境構築
1.コンテナを起動
```
docker compose -f docker-compose.local.yml up -d --build
```
2.モジュールをインストール
```
docker compose -f docker-compose.local.yml exec swoole composer install
```
3.Octaneをスタート
```
docker compose -f docker-compose.local.yml exec swoole php artisan octane:start --host=0.0.0.0 --port=8000
```
