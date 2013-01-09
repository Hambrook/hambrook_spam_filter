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

/*
    TODO
    [ ] Add keyword filtering. Something like "viagra|100\nwork from home|80" where if each term is found and the score is above a set threshold then bounce.
    [ ] Link filtering, by number of links
    [ ] Do both of above "per 20 words" or something. So long comments can contain more links without being spam
    [ ] Create an API so that the contact and comment plugins can query this plugin directly, rather than just monitoring the POST data
*/

class Jojo_Plugin_hambrook_spam_filter extends Jojo_Plugin {

    function filter_jojo_banned_ip_list($list) {
        // Comment spammers
        $list[] = "91.121.169.209";
        $list[] = "96.31.86.184";
        $list[] = "195.42.112.244";

        if ($_SERVER['HTTP_USER_AGENT'] == "Mozilla/5.0 (Windows NT 5.1; rv:6.0.2) Gecko/20100101 Firefox/6.0.2" && preg_match("/^180\.76\..*/", $_SERVER['REMOTE_ADDR'])) {
            $list[] = $_SERVER['REMOTE_ADDR'];
        }
        return $list;
    }

    function filter_comment_allow_new($allowcomments) {
        // Block IE6 users from adding comments (most spammers appear to be using this useragent
        if (Jojo::getOption('hambrook_spam_filter-block-ie6-comments', 'yes')) {
            if (Jojo::getbrowser() == "Internet Explorer 6.0") {
                $allowcomments = false;
            }
        }
        return $allowcomments;
    }

    function bounce_bad_post_requests() {
        $referrer = self::get_referrer();
        if (!in_array($referrer, $_SESSION['spamfilter']['prev-uris'])) {
            file_put_contents(_MYSITEDIR."/downloads/blocked-posts.txt", var_export($_POST, true)."\n".var_export($_SERVER, true)."\n".var_export($_SESSION['spamfilter']['prev-uris'], true)."\n\n", FILE_APPEND);
        }
    }

    function global_pageload() {
        self::init_session();
        if ($_POST) {
            self::bounce_bad_post_requests();
        } else {
            $referrer = self::get_referrer();
            $_SESSION['spamfilter']['prev-uris'][time()] = $_SERVER['REQUEST_URI'];
        }
    }

    function init_session() {
        if (!isset($_SESSION['spamfilter'])) {
            $_SESSION['spamfilter'] = array(
                'prev-uris' => array()
            );
        }
        //die($_SERVER['HTTP_REFERER']);
    }

    function get_referrer() {
        $referrer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
        $referrer = str_replace(_PROTOCOL . $_SERVER['HTTP_HOST'], '', $referrer);
        return $referrer;
    }

    // Replace punctuation and odd characters to make the text simply words. Use this list for keyword filtering
    function normalise($text) {
        // Replace whitespace
        $find = array(
            "\n",
            "\r",
            "\t",
            "\0"
        );
        $text = str_replace($find, ' ', $text);

        // Replace word separaters
        $find = array(
            ',',
            '.',
            '-',
            '?',
            '!',
            '"',
            '\'',
            '\\',
            '/',
            '_',
            ':',
            '[',
            ']',
            '#',
            '@',
            ')',
            '('
        );
        $text = str_replace($find, ' ', $text);

        // Replace alternate characters
        $find = array(
            'ç',
            'á', 'ã', 'â', 'à', 'ä',
            'é', 'ê', 'è', 'ë',
            'í', 'î', 'ì', 'ï',
            'ó', 'õ', 'ô', 'ò', 'ö',
            'ú', 'û', 'ù', 'ü'
        );
        $replace = array(
            'c',
            'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u'
        );
        $text = str_replace($find, $replace, $text);
        $text = preg_replace('/ +/', ' ', $text);

        return $text;
    }
}
