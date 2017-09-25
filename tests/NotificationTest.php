<?php

use xmpush\Sender;
use xmpush\Constants;
use xmpush\Builder;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    /**
     * @var string $secret APP秘钥
     */
    protected $secret;
    /**
     * @var string $package APP包名
     */
    protected $package;

    /**
     * 初始化APP秘钥和包名
     */
    public function setUp()
    {
        $this->secret  = "";
        $this->package = "";
        Constants::setPackage($this->package);
        Constants::setSecret($this->secret);
    }

    /**
     * 单播消息测试
     */
    public function testUnitCast()
    {
        $title   = '你好世界';
        $desc    = '这是一条mipush推送消息';
        $payload = '{"test":1,"ok":"It\'s a string"}';
        $regId   = 'QiL3TdA2Z1m6eqY8T4cNTonLGGSErXD+RJZL5TV0TAIU='; // 推送设备ID

        $message = new Builder();
        $message->title($title);
        $message->description($desc);
        $message->passThrough(0);
        $message->payload($payload); // 对于预定义点击行为，payload会通过点击进入的界面的intent中的extra字段获取，而不会调用到onReceiveMessage方法。
        $message->extra(Builder::notifyEffect, 1); // 此处设置预定义点击行为，1为打开app
        $message->extra(Builder::notifyForeground, 1);
        $message->notifyId(0);
        $message->build();

        $sender = new Sender();
        $res    = $sender->send($message, $regId)->getRaw();
        $this->assertEquals(0, $res['code']);
    }

    /**
     * 广播消息测试
     */
    public function testBroadCast()
    {
        $title   = '你好世界广播测试';
        $desc    = '这是一条mipush推送消息';
        $payload = '{"test":1,"ok":"It\'s a string"}';

        $message = new Builder();
        $message->title($title);
        $message->description($desc);
        $message->passThrough(0);
        $message->payload($payload); // 对于预定义点击行为，payload会通过点击进入的界面的intent中的extra字段获取，而不会调用到onReceiveMessage方法。
        $message->extra(Builder::notifyEffect, 1); // 此处设置预定义点击行为，1为打开app
        $message->extra(Builder::notifyForeground, 1);
        $message->notifyId(0);
        $message->build();

        $sender = new Sender();
        $res    = $sender->broadcastAll($message)->getRaw();
        $this->assertEquals(0, $res['code']);
    }
}