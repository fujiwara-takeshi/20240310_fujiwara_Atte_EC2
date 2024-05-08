# Atte（勤怠管理用Webアプリケーション）

![stamp](https://github.com/fujiwara-takeshi/20240310_fujiwara_Atte_EC2/assets/151005520/ede64ce3-3716-4222-b2f0-1a09f5776557)

## 作成目的
　社員の勤怠管理・人事評価のため

## 利用手順
　１．下記URLにアクセス</br>
　　http://54.248.30.178</br>
　２．ユーザー新規登録ページにて登録処理</br>
　３．登録メールアドレス宛に確認メールが届くので、認証する</br>
　４．自動的に打刻ページに遷移し、ご利用いただけます</br>

　以降はログインページよりログインしご利用ください</br>

#### ※ご注意事項</br>
　現在上記URLにはWeb上のどこからでもアクセスできる状態になっております。</br>
　セキュリティ上のリスクが伴いますので、社内ネットワーク等に組み込んでの運用を推奨いたします。</br>

## その他のリポジトリ等
　特になし

## 機能一覧
　・ユーザーログイン機能</br>
　・ユーザー新規登録機能</br>
　・ユーザーログアウト機能</br>
　・打刻機能（勤務・休憩の開始終了時間登録）</br>
　・年月日別勤怠記録の表示</br>
　・ユーザー一覧の表示、及び検索機能</br>
　・ユーザー別勤怠記録の表示</br>

## 使用技術（実行環境）
　フレームワーク：Laravel 8.83</br>
　プログラミング言語：PHP 7.4.9</br>
　Webサーバーソフト：Nginx 1.21.1</br>
　データベースエンジン：MySQL 8.0.26</br>
　コンテナサービス：Docker 20.10.25</br>
　　　　　　　　　　Docker Compose 2.4.1</br>
　アプリケーションサーバー：AWS EC2</br>
　データベースサーバー：AWS RDS</br>

## データベーステーブル設計書
![スクリーンショット 2024-05-08 171845](https://github.com/fujiwara-takeshi/20240310_fujiwara_Atte_EC2/assets/151005520/b26220b1-e4c4-4145-a87a-1a5785f86668)

## ER図
![スクリーンショット 2024-05-08 172017](https://github.com/fujiwara-takeshi/20240310_fujiwara_Atte_EC2/assets/151005520/802c7692-b862-4dc0-a087-d744a5e929af)

## 環境構築
#### 認証メールサーバー設定
　１．AWSのEC2サーバーログイン用のペアキー[Atte_keypair.pem]を任意のフォルダに配置</br>
　２．ターミナルからペアキーのあるディレクトリに移動</br>
　３．`chmod 400 "Atte_keypair.pem"`実行</br>
　４．`ssh -i "Atte_keypair.pem" ec2-user@ec2-54-248-30-178.ap-northeast-1.compute.amazonaws.com`実行し、EC2サーバーログイン</br>
　５．
　
