<h1>mixi Check for Sharedaddy の使い方</h1>
<hr />
<h2>インストール</h2>
<p>
	Wordpress.orgのプラグインディレクトリからダウンロード・インストールしてください。<br />
	インストール後、プラグインリストより有効化してください。
</p>
<p>
	<strong>※標準テンプレート「TwentyTen」以外のテンプレートをお使いの場合</strong><br />
	mixiチェックの使用する上で、テーマの<code>&lt;html&gt;</code>タグへ属性を以下の様に追加する必要があります。<br />
	<code>&lt;html xmlns:og="http://ogp.me/ns#" xmlns:mixi="http://mixi-platform.com/ns#"&gt;</code><br />
	TwentyTenや対応しているテーマの場合は自動で追加しますが、古いテーマなどでは対応していない場合があります。<br />
	以下の修正をすることで自動で属性を追加することが可能です。<br />
	テーマの header.php の<code>&lt;html&gt;</code>タグの<code>html</code>と<code>&gt;</code>の間に<code>&lt;?php language_attributes(); ?&gt;</code>を追加してください。
</p>
<hr />
<h2>mixiチェックのサービス登録とチェックキー</h2>
<p>
	mixiチェックの利用には、mixi Plugin へのサービス登録とチェックキーが必要です。<br />
	なお、サービスの登録には mixi の「Developer登録」が必要です。<br />
	サービス登録が完了するとチェックキーが発行されます。チェックキーは Sharedaddy の設定画面にて入力してください。
</p>
<p>
	※ 詳しくは <a href="http://developer.mixi.co.jp/" target="_blank">mixi Developer Center</a> を確認してください。
</p>
<hr />
<h2>テンプレートタグについて</h2>
<p>
	※次回バージョンアップで実装予定
</p>
<p style="text-decoration: line-through;">
	このプラグインは、テンプレートファイルに直接ボタンを設置できるテンプレートタグを提供しています。<br />
	Sharedaddyを使用できない場合や、使用したくない場合などは、こちらのテンプレートタグを使用してください。
</p>
<p style="text-decoration: line-through;">
	<code>&lt;?php xxxxxx( 'check-key', 'button-5' ); ?&gt;</code>
</p>
<hr />
<p style="text-align: right;">用法・用量を守って正しくお使い下さい</p>