English Document
======



PHP wrapper class for Btcturk API 
======

This class is a wrapper for the Btcturk trader platform API (https://www.btcturk.com/api).
You can use it to check market values, do tradings with your wallet,  write your own trading bot, etc

Requirements
======
* You obviously need a btcturk account.
* You need to create an API key on your account settings

Usage 
======

	$key = 'PUBLIC_KEY'; // use your key and secret
	$secret = 'PRIVATE_KEY';

	$b = new Client ($key, $secret);
	
	$list = $b->getBalances();




Türkçe Döküman
======


PHP Btcturk API Class
======

Bu Class Btcturk Api Sistemi İçin yapılmıştır (https://www.btcturk.com/api).
Piyasa değerlerini kontrol etmek, bakiyeniz ile ticaret yapmak, kendi ticaret botunuzu yazmak vs. için kullanabilirsiniz.


Gereksinimler
======

* Bir Btcturk Hesabı olması.
* Hesap > Api Erişimi kısmından bir Api hesabı oluşturmak

Kullanımı
======


	$key = 'PUBLIC_KEY'; // public ve private keylerini kullan
	$secret = 'PRIVATE_KEY';

	$b = new Client ($key, $secret);
	
	$list = $b->getBalances();


Source 
===
Btcturk  https://github.com/BTCTrader/broker-api-docs


Company
=====
https://coinfono.com