English Document
======



PHP wrapper class for Btcturk API 
-------------

This class is a wrapper for the Btcturk trader platform API (https://www.btcturk.com/api).
You can use it to check market values, do tradings with your wallet,  write your own trading bot, etc

Requirements
-------------
* You obviously need a btcturk account.
* You need to create an API key on your account settings

Usage 
-------------

	$key = 'PUBLIC_KEY'; // use your key and secret
	$secret = 'PRIVATE_KEY';

	$b = new Client ($key, $secret);
	
	$list = $b->getBalances();




Türkçe Döküman
======


PHP Btcturk API Class
-------------

Bu Class Btcturk Api Sistemi İçin yapılmıştır (https://www.btcturk.com/api).
Piyasa değerlerini kontrol etmek, bakiyeniz ile ticaret yapmak, kendi ticaret botunuzu yazmak vs. için kullanabilirsiniz.


Gereksinimler
-------------

* Bir Btcturk Hesabı olması.
* Hesap > Api Erişimi kısmından bir Api hesabı oluşturmak

Kullanımı
-------------


	$key = 'PUBLIC_KEY'; // public ve private keylerini kullan
	$secret = 'PRIVATE_KEY';

	$b = new Client ($key, $secret);
	
	$list = $b->getBalances();

[Issue](https://github.com/falconsw/btcturk/issues)

[Source](https://github.com/BTCTrader/broker-api-docs)

[coinfono.com](https://coinfono.com)

License
-----

BtcTurk-Api is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

Donations/Support
-----

If you find this library to your liking and enjoy using it, please consider a donation to one of the following addresses:
* BTC: 3BCC4zNHhEyS38kEBVSDcj4MDZpnanwEUD
* ETH: 0x0d57c1535b90cebaa8b2c6aa0cff5d7f20e7a75d
