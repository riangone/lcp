<?php
header("Content-Type: text/javascript; charset=utf-8");
/**
 * @var Cake\View\View $this
 */
// 適用レイアウトファイルのmetaブロックに置き換える内容を設定
$this->start('meta');
$this->end();

// 適用レイアウトファイルのcssブロックに置き換える内容を設定
$this->start('css');
$this->end();

// 適用レイアウトファイルのscriptブロックに置き換える内容を設定
$this->start('script');
$this->end();