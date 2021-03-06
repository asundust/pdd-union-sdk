<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 11:19
 */

namespace pddUnionSdk\Api;


use pddUnionSdk\pddUnionGateWay;

/**
 * 拼多多链接处理
 * Class Link
 *
 * @package pddUnionSdk\Api
 */
class Link extends pddUnionGateWay
{


    /**
     * @param string $p_id
     * @param string $goods_id
     * @param bool $short
     * @param array $ext
     * @return mixed|string
     * @throws \Exception
     * @api 多多进宝推广链接生成
     */
    public function createCpsUrl($p_id = '', string $goods_id, $short = false, $ext = [])
    {
        $params = [
            'p_id' => $p_id,
            'goods_id_list' => [$goods_id],
            'generate_short_url' => $short,
            'generate_weapp_webview' => true,
            'generate_we_app' => true,
            'generate_schema_url' => true,
            'generate_qq_app' => true,
            'generate_weiboapp_webview' => true
        ];
        if (empty($p_id)) {
            $params['p_id'] = $this->pid;
        }
        foreach (array_filter($ext) as $k => $v) {
            $params[$k] = $v;
        }
        $result = $this->send('pdd.ddk.goods.promotion.url.generate', $params);
        if (!$result) {
            return $result;
        }
        return \current(\current($result));
    }

    /**
     * 生成红包推广链接
     *
     * @param string $p_id
     * @param bool $short
     * @param array $ext
     * @return mixed|string
     * @throws \Exception
     */
    public function createRedbaoUrl($p_id = '', $short = false, $ext = [])
    {
        $params = [
            'p_id_list' => [$p_id],
            'generate_short_url' => $short,
            'generate_weapp_webview' => true,
            'generate_we_app' => true,
            'generate_schema_url' => true,
            'generate_qq_app' => true,
            'generate_weiboapp_webview' => true
        ];
        if (empty($p_id)) {
            $params['p_id_list'] = [$this->pid];
        }
        foreach (array_filter($ext) as $k => $v) {
            $params[$k] = $v;
        }
        $result = $this->send('pdd.ddk.rp.prom.url.generate', $params);
        if (!$result) {
            return $result;
        }
        return \current(\current($result));
    }

    /**
     * @param string $pid
     * @param $theme_id_list
     * @param bool $short
     * @param array $ext
     * @return mixed|string
     * @throws \Exception
     * @api 多多进宝主题推广链接生成
     */
    public function createThemeUrl($pid = '', $theme_id_list, $short = false, $ext = [])
    {
        $params = [
            'pid' => $pid,
            'theme_id_list' => is_array($theme_id_list) ? $theme_id_list : [$theme_id_list],
            'generate_short_url' => $short,
            'generate_weapp_webview' => true,
            'generate_we_app' => true,
            'we_app_web_view_short_url' => true
        ];
        if (empty($pid)) {
            $params['pid'] = $this->pid;
        }
        foreach (array_filter($ext) as $k => $v) {
            $params[$k] = $v;
        }
        $result = $this->send('pdd.ddk.theme.prom.url.generate', $params);
        if (!$result) {
            return $result;
        }
        return \current($result);
    }

    /**
     * @param string $pid
     * @param $source_url
     * @param array $ext
     * @return mixed|string
     * @throws \Exception
     * @api 本功能适用于采集群等场景。将其他推广者的推广链接转换成自己的；通过此api，可以将他人的招商推广链接，转换成自己的招商推广链接。
     */
    public function covertOtherToMyPidUrl($pid = '', $source_url, $ext = [])
    {
        $params = [
            'pid' => $pid,
            'source_url' => $source_url
        ];
        if (empty($pid)) {
            $params['pid'] = $this->pid;
        }
        foreach (array_filter($ext) as $k => $v) {
            $params[$k] = $v;
        }
        return $this->send('pdd.ddk.goods.zs.unit.url.gen', $params);
    }

}