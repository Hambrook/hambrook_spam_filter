<?php
/**
 * Copyright 2012 Hambrook Web Design <rick@hambrook.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Rick Hambrook <rick@hambrook.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.hambrook.co.nz Hambrook Web Design
 * @package hambrook_spam_filter
 * @usage   This plugin was created for JojoCMS http://www.jojocms.org/
 */

Jojo::addFilter('jojo_comment:allow_new', 'filter_comment_allow_new', 'hambrook_spam_filter');
Jojo::addFilter('jojo_banned_ip_list', 'filter_jojo_banned_ip_list', 'hambrook_admin_tweaks');
//Jojo::addHook('jojo_before_parsepage', 'hook_before_parsepage', 'hambrook_spam_filter');

/* Options */
$_options[] = array(
  'id'          => 'hambrook_spam_filter-block-ie6-comments',
  'category'    => 'Spam Protection',
  'label'       => 'Block IE6 Comments',
  'description' => 'Block IE6 users from leaving comments.',
  'type'        => 'radio',
  'default'     => 'No',
  'options'     => 'Yes,No',
  'plugin'      => 'hambrook_spam_filter'
);
/* Disable logging

$referrer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';

if (Jojo::getbrowser() == "Internet Explorer 6.0")  {
	$path = _MYSITEDIR.'/downloads/ie6-log.txt';
	$logline  = "[".date("Y-m-d H:i:s")."]\t";
	$logline .= $_SERVER['REQUEST_URI']."\t";
	$logline .= $referrer."\r\n";
	file_put_contents($path, $logline, FILE_APPEND);
}
if ($_POST) {
	$path = _MYSITEDIR.'/downloads/post-log.txt';
	$logline  = "[".date("Y-m-d H:i:s")."]\t";
	$logline .= $_SERVER['REQUEST_URI']."\t";
	$logline .= $referrer."\t";
	$logline .= Jojo::getbrowser()."\r\n";
	file_put_contents($path, $logline, FILE_APPEND);
}
if (true) {
	$path = _MYSITEDIR.'/downloads/full-log.txt';
	$logline  = "[".date("Y-m-d H:i:s")."]\t";
	$logline .= $_SERVER['REQUEST_URI']."\t";
	$logline .= $referrer."\t";
	$logline .= Jojo::getbrowser()."\r\n";
	file_put_contents($path, $logline, FILE_APPEND);
}
*/
