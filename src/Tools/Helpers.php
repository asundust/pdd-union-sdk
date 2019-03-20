<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 10:10
 */

/**
 * @api 发送get请求
 * @param $url
 * @param $post_data
 * @param array $header
 * @return bool|string
 */
function curl_post($url, $post_data, $header = [])
{
    if (!extension_loaded('swoole') || PHP_SAPI != 'cli') {
        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // https请求 不验证证书和hosts
        \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        \curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        \curl_setopt($ch, CURLOPT_URL, $url);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// 要求结果为字符串且输出到屏幕上
        if (!empty($header)) {
            \curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        } else {
            \curl_setopt($ch, CURLOPT_HEADER, 0); // 不要http header 加快效率
        }
        \curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $output = curl_exec($ch);
        curl_close($ch);
    } else {
        $urlsInfo = \parse_url($url);
        $queryUrl = $urlsInfo['path'];
        $domain = $urlsInfo['host'];
        $port = $urlsInfo['scheme'] = 'https' ? 443 : 80;
        $chan = new \Chan(1);
        go(function () use ($chan, $domain, $queryUrl, $header, $port, $post_data) {
            $cli = new \Swoole\Coroutine\Http\Client($domain, $port, $port == 443 ? true : false);
            $cli->setHeaders($header);
            $cli->set(['timeout' => 15]);
            $cli->post($queryUrl, $post_data);
            $output = $cli->body;
            $chan->push($output);
            $cli->close();
        });
        $output = $chan->pop();
    }
    return $output;
}