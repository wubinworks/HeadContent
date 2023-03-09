# Wubinworks URIパターンで&lt;head&gt;に任意のscriptとcssを追加

## 動作環境
 - Magento 2.4

## 主な機能
バックエンド設定から、URIパターン(正規表現)指定によって、&lt;head&gt;に任意のscriptとcssが追加可能

## インストール
### 手順
 - master branchをダウンロードし、`<magento root>/app/code/Wubinworks/InjectHead/`に解凍
   - 存在しないフォルダは作成してください
 - `php bin/magento setup:upgrade`
 - `php bin/magento setup:di:compile`
 - `php bin/magento c:f`
 - バックエンドのWubinworksのメニューから使用開始

## 設定
 - ルール有効／無効の選択
 - URIパターン(正規表現)を入力。
  - /か空のままにすると、すべてのページにマッチする

## 特徴／使用例
 - 日本語サポート
 - XX Tag追加に最適
 - 特定の商品ページのcss変更

# Wubinworks Inject scripts & css to &lt;head&gt; by URI pattern

 - [Main Functionalities](#main-functionalities)
 - [System Requirements](#system-requirements)
 - [Installation](#installation)
 - [Configuration](#configuration)
 - [Specifications & Use case](#specifications--use-case)

## Main Functionalities
Inject scripts and css to HTML &lt;head&gt; based on URI pattern(regular expression) via backend settings.

## System Requirements
 - Magento 2.4

## Installation
### Procedure
 - Download the master branch and unzip the zip file in `<magento root>/app/code/Wubinworks/InjectHead/`
  - Create any folder if not exists
 - `php bin/magento setup:upgrade`
 - `php bin/magento setup:di:compile`
 - `php bin/magento c:f`
 - Go to backend Wubinworks menu

## Configuration
 - Set Enabled to Yes
 - Specify the URI pattern in regular expression
  - / or leave it empty means matching all pages

## Specifications & Use case
 - Japanese language support
 - Useful for deploy XX Tag
 - Useful for changing some product page css
