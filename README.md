<a href="https://github.com/wubinworks/home/raw/master/images/Wubinworks/InjectHead/grid.png" target="_blank"><img src="https://github.com/wubinworks/home/raw/master/images/Wubinworks/InjectHead/grid.png" alt="Wubinworks_InjectHead_Grid" title="Wubinworks_InjectHead_Grid" width="49%"/></a>
<a href="https://github.com/wubinworks/home/raw/master/images/Wubinworks/InjectHead/code_editor.png" target="_blank"><img src="https://github.com/wubinworks/home/raw/master/images/Wubinworks/InjectHead/code_editor.png" alt="Wubinworks_InjectHead_Code_Editor" title="Wubinworks_InjectHead_Code_Editor" width="49%"/></a>
**Head Content Manager(Left)**

**Code Editor(Right)**

_Click image to enlarge_

(日本語)
## 何のエクステンション？
簡単に言うと、Magentoの任意のページの<head>要素に、任意の内容(コード)を注入できるエクステンションです。
ルールの一致条件を指定することで、例えば、正規表現のURIパターンで、注入したいページを指定することもできます。
本エクステンションはMagentoキャッシュ機能とブラウザのローカルストレージを利用することで、サーバの負荷を軽減します。

## 背景
多くの非Magentoプログラマーとサイト管理者の要望により、本エクステンションを開発しました。あんまりMagento経験がない人でも、日常管理やキャンペーンで、サイトを一時変更することも手軽にできます。

## 機能ハイライト
**Full Page Cache, Fastly, Varnish対応**
 - Full Page Cache／Fastly／Varnishを使用しても**顧客グループ**指定可。オリジナルprivate content方法で実現
 - サーバからダウンロードした内容をできるだけ長くブラウザにキャッシュさせます(Magento既定のキャッシュ機能より長くキャッシュできる)
 - ローカルストレージをフル活用
 - サーバへのリクエストを軽減
 - バックエンドで細かく設定可能。貴社ビジネスモデルとサーバ環境に一番合う設定を模索しよう
 - 何かを間違えても、顧客ブラウザのキャッシュを強制的にアップデートさせることが可能(バックエンド設定:バージョンナンバー)

**ルール開始時刻と終了時刻が指定でき、期間限定キャンペーンなどに活用しやすい**

**ルールに顧客グループ指定可能(複数選択可)**

**ルールにストアビュー指定可能(複数選択可)**

**高度なルール検索機能。複数選択は完全一致と部分一致両方対応**
 - たくさんのルールが一元管理できる

**ルール複製: 類似なルールを作るのに便利**
 - 複製回数指定可能
 - 注意点: 複製されたルールは既定で無効

**高度な一括操作: Magento既定の一括操作と完全に違い、全ての項目を便利に変更可能(開発中)**

**URIパターンとFull Action Nameでマッチ**
 - URIパターン正規表現のデリミタは`#`で、デリミタを入力する必要はない
 - 大小文字を区別
 - Full Action Name一致は開発中

**Rewriteルール逆引き**
 - SEOリンクと正規リンクに同一ルールを適用
 - 例:`/seo-friendly-url-product.html`と`/category/product/view/id/5678`、開発中

**404などのページを除外できる**
 - バックエンドで設定可能。既定で`cms/noroute/index`(404)は既に除外している。他にも追加可能
 - ワイルドカード使用可
 - todo: FAQセクションで詳しく説明

**HTMLエディタ with 構文強調**
 - トップ部分の画像をご参照ください。`CodeMirror`開発チームに感謝します。

**日本語翻訳(>95%翻訳済み)**

**無料、オープンソース、データを収集しない**
 - プライバシーポリシーセクションをご参照

## 他の機能
 - 設定セクションをご参照

## <a name="faq_ja"></a>FAQ

##### <a name="partial_match_ja"></a>`部分一致`は`すべて`にマッチしません
TODO: Add description

##### <a name="uri_pattern_ja"></a>URIパターン(正規表現)
TODO: Add description

##### <a name="code_editor_ja"></a>コードエディタ
TODO: Add description

##### <a name="cache_clear_ja"></a>キャッシュクリア
TODO: Add description

##### <a name="excluded_full_action_name_ja"></a>除外されるFull Action Name
TODO: Add description

## 使用事例
 - キャンペーン用商品: `^/awesome-product-sku102[1-9]\.html`
 - メンバーシップ判定: `\?is_member=1`
 - チェックアウトページを特定: `^/checkout(/index)?(/index)?((\?.*)?$|/)`

## 今後の開発計画
 - `開発中`をご参照

## <a name="system_requirements_ja"></a>動作環境
 - Magento 2.4

## インストール

### _下記のインストール方法を1つだけ選んでください！_

#### Composerで安定版をインストール(推奨)
`cd <magento root>`

`composer require wubinworks/module-injecthead 1.0.0`

#### Composerで最新Githubバージョンをインストール
`cd <magento root>`

`composer config repositories.wubinworks-headcontent git https://github.com/wubinworks/HeadContent.git`

`composer require wubinworks/module-injecthead:dev-master`

もしくは、Magentoルート`composer.json`に以下のコードを追加
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/wubinworks/HeadContent.git"
        }
    ],
    "require": {
        "wubinworks/module-injecthead": "dev-master"
    }
}
```
次に、
`composer install`
#### 手動インストール
 - エクステンションコードパスを作成(例: `mkdir -p <magento root>/app/code/Wubinworks/InjectHead`)
 - インストールしたいブランチをダウンロードし、`<magento root>/app/code/Wubinworks/InjectHead/`に解凍(unzip)

### 共通ラストステップ
`cd <magento root>`

`php bin/magento setup:upgrade`

`php bin/magento setup:di:compile`

`php bin/magento cache:flush`

#### バックエンドでWubinworksのメニューを確認

## 設定
 - エクステンション全体有効／無効
 - 除外されたFull Action Name
 - ルール複製回数
 - Block HTML Cache Lifetime
 - クライアントデータCache Lifetime最大値
 - バージョンナンバー
 - Rewriteルール逆引き
 - デバッグモード

## プライバシーポリシー
[Wubinworksプライバシーポリシー](https://github.com/wubinworks/home/tree/master/privacy-policy)

(English)
## What is this Extension?
To be simple, this extension can inject scripts and css to HTML <head> element.
Users can choose which page they want to inject by specifying rules including URI pattern.
This module makes use of magento cache and browser local storage to achieve best performance.

## Background
As per many common requests from Non-Magento Programmers and Website Admins, this extension provides an all-in-one solution to make their job much easier.

## Feature Highlights
**Full Page Cache, Fastly, Varnish support**
 - Full Page Cache, Fastly, Varnish tested for different **Customer Groups**. Achieved by using a modified private content way
 - caches correct contents downloaded from the source server as long as possible(cache time in general is much longer than Magento default)
 - ultilizes the local storage
 - less frequent request to server
 - there are many settings for this feature at backend. Find the most suitable settings for your business case and server
 - can force browser update(check backend setting: version number) in case you made mistake and want to fix it immediately

**Can set rule start & end date time, easy management for campaigns**

**Can set rules for different custmer groups(multiple selection possible)**

**Can set rules for different stores(multiple selection possible)**

**Enhanced rules filter. For multi-select, full match filter and partial match filter are supported**
 - this feature becomes very handy when you got like more than 100 rules

**Rule Duplication: Create similar rules much easier**
 - can specify duplication times
 - note: duplicated rules will be automatically set to Disabled

**Advanced Mass Action: A very different design compared to the Magento default mass action. Almost everything can be bulky modified(under development)**

**Match pages with URI pattern or Full Action Name**
 - URI regex pattern delimiter is `#`, you do NOT need to add the delimiter
 - case sensitive
 - Full Action Name way is under development

**Rewrite rule reverse lookup**
 - you don't need to struggle the SEO friendly URL and the canonical one
 - eg:`/seo-friendly-url-product.html` and `/category/product/view/id/5678`, under development

**Exclude certain pages, such as 404 page**
 - can config it in backend settings. By default, `cms/noroute/index`(404) is excluded and you can add more
 - wildcard supported
 - todo: add more details to FAQ Section

**A Cute HTML editor with syntax hightlighting**
 - see image above, thanks to `CodeMirror`

**Janpanese translation(>95% done)**

**Free, Open Source and No Data Collection**
 - see Privacy Policy Section

## Other Features
 - see Configuration Section

## <a name="faq"></a>FAQ

##### <a name="partial_match"></a>`partial match` cannot match `ALL`
TODO: Add description

##### <a name="uri_pattern"></a>URI Pattern(Regular Expression)
TODO: Add description

##### <a name="code_editor"></a>Code Editor
TODO: Add description

##### <a name="cache_clear"></a>Cache Clear
TODO: Add description

##### <a name="excluded_full_action_name"></a>Excluded Full Action Names
TODO: Add description

## Use Case
 - campaign products: `^/awesome-product-sku102[1-9]\.html`
 - check membership: `\?is_member=1`
 - match checkout page: `^/checkout(/index)?(/index)?((\?.*)?$|/)`

## Future Plans
 - see `under development`

## <a name="system_requirements"></a>System Requirements
 - Magento 2.4

## Installation

### _You can only choose one method!_

#### Composer install stable version(Recommended)
`cd <magento root>`

`composer require wubinworks/module-injecthead 1.0.0`

#### Composer install latest Github version(For advanced user)
`cd <magento root>`

`composer config repositories.wubinworks-headcontent git https://github.com/wubinworks/HeadContent.git`

`composer require wubinworks/module-injecthead:dev-master`

Alternatively, add below to Magento root `composer.json`
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/wubinworks/HeadContent.git"
        }
    ],
    "require": {
        "wubinworks/module-injecthead": "dev-master"
    }
}
```
then,
`composer install`
#### Install Manually(For advanced user)
 - Create extension code path(eg: `mkdir -p <magento root>/app/code/Wubinworks/InjectHead`)
 - Download your prefered branch and unzip it to `<magento root>/app/code/Wubinworks/InjectHead/`

### Common Last Step - Don't forget to run
`cd <magento root>`

`php bin/magento setup:upgrade`

`php bin/magento setup:di:compile`

`php bin/magento cache:flush`

#### Go to backend and check Wubinworks menu

## Configuration
 - Enable/Disable the entire extension
 - Excluded Full Action Names
 - Duplication Multiplier
 - Block HTML Cache Lifetime
 - Maximum Client Data Cache Lifetime
 - Version Number
 - Reverse Rewrite Rule Lookup
 - Debug Mode

## Privacy Policy
[Wubinworks Privacy Policy](https://github.com/wubinworks/home/tree/master/privacy-policy)
