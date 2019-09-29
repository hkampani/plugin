<?php

function ccc_return_list_languages()
{
    return array(
        'en' => 'English', 
        'ru' => 'Русский', 
        'it' => 'Italiano', 
        'fr' => 'Français', 
        'es' => 'Español', 
        'cn' => '中国', 
        'de' => 'Deutsch',
        'hi' => 'हिन्दी',
        'id' => 'Bahasa Indonesia',
        'ja' => '日本語',
        'pt' => 'Português',
    );
}

function ccc_return_language_detected()
{
    $sl = substr(get_bloginfo('language'), 0, 2);

    return (in_array($sl, array_keys(ccc_return_list_languages()))) ? $sl : 'en';
}

function ccc_return_currency_list()
{
    $contents = file_get_contents(plugin_dir_path(__FILE__).'data/currencies_'.ccc_return_language_detected().'.json');

    return json_decode($contents, true);
}

function ccc_return_iframe($params, $width, $height, $signature = null, $text = null)
{
    $target_url = strtolower('https://'.$params['fm'].(('en' != $params['lg']) ? '.'.$params['lg'] : '').'.currencyrate.today'.DIRECTORY_SEPARATOR.$params['to']);

    $url = 'https://currencyrate.today/load-converter?'.http_build_query($params);
    $output = '<iframe src="'.$url.'" height="'.$height.'" width="'.$width.'" frameborder="0" scrolling="no" class="ccc-iframe" name="ccc-exchange-rates-widget"></iframe>';
    if ($signature) {
        $output .= '<p>'.(($text) ? $text.': ' : '').'<a href="'.$target_url.'" target="_blank" class="ccc-base-currency-link">'.$params['fm'].DIRECTORY_SEPARATOR.$params['to'].'</a></p>';
    }

    return $output;
}

function ccc_print_timezone_list($code, $arr)
{
    $output_string = '';
    foreach ($arr as $v) {
        $output_string .= '<option value="'.$v[0].'"'.(($code == $v[0]) ? ' selected' : '').'>'.$v[1].'</option>'.PHP_EOL;
    }

    echo $output_string;
}

function ccc_print_select_options($code, $arr, $o = false)
{
    $output_string = '';
    foreach ($arr as $k => $v) {
        $output_string .= '<option value="'.$k.'"'.(($code == $k) ? ' selected' : '').'>'.((true === $o) ? $k.' - '.$v : $v).'</option>'.PHP_EOL;
    }

    echo $output_string;
}
