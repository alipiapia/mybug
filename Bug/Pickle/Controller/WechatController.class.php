<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Pickle\Controller;

use Think\Controller;
use Wechat\Wechat;
use Wechat\WechatAuth;

class WechatController extends Controller{
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    Public function index($id = ''){
        
        //调试
        try{
            $appid = 'wxa45e19adc240df07'; //AppID(应用ID)
        	$appsecret = 'd1eb30c3ecf46d29a21026e5379c4347';
            $token = 'xR89daRF0fQW3rSvpp'; //微信后台填写的TOKEN
            $crypt = '7DucQF0eTEZ9S9DNSGLpgXIGOBQsC1E9RRzXCTWtAFI'; //消息加密KEY（EncodingAESKey）
            
            //菜单
            $data = '{
                         "button":[
                         {
                               "name":"我的到件",
                               "sub_button":[
                                {
                                   "type":"click",
                                   "name":"查询1",
                                   "key":"search1"
                                },
                                {
                                   "type":"click",
                                   "name":"查询2",
                                   "key":"search2"
                                }]
                          },
                          {
                               "name":"查询",
                               "sub_button":[
                                {
                                   "type":"click",
                                   "name":"已取件",
                                   "key":"yesget"
                                },
                                {
                                   "type":"click",
                                   "name":"未取件",
                                   "key":"noget"
                                }]
                           },
                           {
                               "name":"我的寄件",
                               "type":"click",
                               "key":"mybag"
                           }]
                        }';

            //$appid = 'wxa45e19adc240df07'; //AppID(应用ID)
        	//$appsecret = 'd1eb30c3ecf46d29a21026e5379c4347';
            $auth = new  WechatAuth($appid, $appsecret);
            $at = $auth->getAccessToken();
            $ACC_TOKEN = $at['access_token'];
            //var_dump($ACC_TOKEN);exit;
        
        
        
            //查询菜单
            $MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$ACC_TOKEN;
            $cu = curl_init();
            curl_setopt($cu, CURLOPT_URL, $MENU_URL);
            curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
            $menu_json = curl_exec($cu);
            $menu = json_decode($menu_json);
            curl_close($cu);
            //echo $menu_json;exit;
            
            
            
            //发送菜单
            $MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACC_TOKEN;
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $MENU_URL); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            $info = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Errno'.curl_error($ch);
            }
            curl_close($ch);
            //var_dump($info);exit;
            
            
            
            /* 加载微信SDK */
            $wechat = new Wechat($token, $appid, $crypt);
            
            /* 获取请求信息 */
            $data = $wechat->request();

            if($data && is_array($data)){
                /**
                 * 你可以在这里分析数据，决定要返回给用户什么样的信息
                 * 接受到的信息类型有10种，分别使用下面10个常量标识
                 * Wechat::MSG_TYPE_TEXT       //文本消息
                 * Wechat::MSG_TYPE_IMAGE      //图片消息
                 * Wechat::MSG_TYPE_VOICE      //音频消息
                 * Wechat::MSG_TYPE_VIDEO      //视频消息
                 * Wechat::MSG_TYPE_SHORTVIDEO //视频消息
                 * Wechat::MSG_TYPE_MUSIC      //音乐消息
                 * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
                 * Wechat::MSG_TYPE_LOCATION   //位置消息
                 * Wechat::MSG_TYPE_LINK       //连接消息
                 * Wechat::MSG_TYPE_EVENT      //事件消息
                 *
                 * 事件消息又分为下面五种
                 * Wechat::MSG_EVENT_SUBSCRIBE    //订阅
                 * Wechat::MSG_EVENT_UNSUBSCRIBE  //取消订阅
                 * Wechat::MSG_EVENT_SCAN         //二维码扫描
                 * Wechat::MSG_EVENT_LOCATION     //报告位置
                 * Wechat::MSG_EVENT_CLICK        //菜单点击
                 */

                //记录微信推送过来的数据
                file_put_contents('./data.json', json_encode($data));

                /* 响应当前请求(自动回复) */
                //$wechat->response($content, $type);

                /**
                 * 响应当前请求还有以下方法可以使用
                 * 具体参数格式说明请参考文档
                 * 
                 * $wechat->replyText($text); //回复文本消息
                 * $wechat->replyImage($media_id); //回复图片消息
                 * $wechat->replyVoice($media_id); //回复音频消息
                 * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
                 * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
                 * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
                 * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
                 * 
                 */
                
                //执行Demo
                $this->demo($wechat, $data);
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
        
    }

    /**
     * DEMO
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息
     */
    private function demo($wechat, $data){
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $wechat->replyText('欢迎您关注alipiapia公众平台！回复“文本”，“图片”，“语音”，“视频”，“音乐”，“图文”，“多图文”查看相应的信息！');
                        break;

                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;

                    default:
                        $wechat->replyText("欢迎访问alipiapia公众平台！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        break;
                }
                break;

            case Wechat::MSG_TYPE_TEXT:
                switch ($data['Content']) {
                    case '文本':
                        $wechat->replyText('欢迎访问alipiapia公众平台，这是文本回复的内容！');
                        break;

                    case '图片':
                        //$media_id = $this->upload('image');
                        $media_id = '1J03FqvqN_jWX6xe8F-VJr7QHVTQsJBS6x4uwKuzyLE';
                        $wechat->replyImage($media_id);
                        break;

                    case '语音':
                        //$media_id = $this->upload('voice');
                        $media_id = '1J03FqvqN_jWX6xe8F-VJgisW3vE28MpNljNnUeD3Pc';
                        $wechat->replyVoice($media_id);
                        break;

                    case '视频':
                        //$media_id = $this->upload('video');
                        $media_id = '1J03FqvqN_jWX6xe8F-VJn9Qv0O96rcQgITYPxEIXiQ';
                        $wechat->replyVideo($media_id, '视频标题', '视频描述信息。。。');
                        break;

                    case '音乐':
                        //$thumb_media_id = $this->upload('thumb');
                        $thumb_media_id = '1J03FqvqN_jWX6xe8F-VJrjYzcBAhhglm48EhwNoBLA';
                        $wechat->replyMusic(
                            '人来人往', 
                            'eason - 人来人往 - Your first R/Hiphop source', 
                            'http://1.alipiapia.sinaapp.com/rlrw.mp3', 
                            'http://1.alipiapia.sinaapp.com/rlrw.mp3', 
                            $thumb_media_id
                        ); //回复音乐消息
                        break;

                    case '图文':
                        $wechat->replyNewsOnce(
                            "成熟的解决方案帮您快速应用云计算",
                            "使用阿里多媒体云服务，您可以坐享阿里领先的海量存储集群、国内海外多节点部署的CDN网络、强大的转码、渲染、图片处理服务等。共享淘宝天猫一样专业及响应迅速的技术保障和运维能力。同时阿里云资深架构师和官方认证的上云服务提供商也为您提供专业的架构咨询和服务。", 
                            "http://www.topthink.com/topic/11991.html",
                            "http://1.alipiapia.sinaapp.com/alipiapia_20150424_110101.jpg"
                        ); //回复单条图文消息
                        break;

                    case '多图文':
                        $news = array(
                            "成熟的解决方案帮您快速应用云计算",
                            "使用阿里多媒体云服务，您可以坐享阿里领先的海量存储集群、国内海外多节点部署的CDN网络、强大的转码、渲染、图片处理服务等。共享淘宝天猫一样专业及响应迅速的技术保障和运维能力。同时阿里云资深架构师和官方认证的上云服务提供商也为您提供专业的架构咨询和服务。", 
                            "http://www.topthink.com/topic/11991.html",
                            "http://1.alipiapia.sinaapp.com/alipiapia_20150424_110101.jpg"
                        ); //回复单条图文消息

                        $wechat->replyNews($news, $news, $news, $news, $news);
                        break;
                    
                    default:
                        $wechat->replyText("欢迎访问alipiapia公众平台！您输入的内容是：{$data['Content']}");
                        break;
                }
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * 资源文件上传方法
     * @param  string $type 上传的资源类型
     * @return string       媒体资源ID
     */
    private function upload($type){
        $appid     = 'wxa45e19adc240df07';
        $appsecret = 'd1eb30c3ecf46d29a21026e5379c4347';

        $token = session("token");

        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }

        switch ($type) {
            case 'image':
                $filename = './Public/wechat/image.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'voice':
                $filename = './Public/wechat/voice.mp3';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'video':
                $filename    = './Public/wechat/video.mp4';
                $discription = array('title' => '视频标题', 'introduction' => '视频描述');
                $media       = $auth->materialAddMaterial($filename, $type, $discription);
                break;

            case 'thumb':
                $filename = './Public/wechat/music.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;
            
            default:
                return '';
        }

        if($media["errcode"] == 42001){ //access_token expired
            session("token", null);
            $this->upload($type);
        }

        return $media['media_id'];
    }
}
