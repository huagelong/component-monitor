<?php
/**
 *  监控接收服务器处理
 *
 * Trensy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      trensy, Inc.
 * @package         trensy/framework
 * @version         1.0.7
 */

namespace Trensy\Component\Monitor;


class MonitorClient
{
    //服务器主机
    private $host = null;

    //服务器端口
    private $port = null;

    //超时时间
    private $timeOut = 0.01;

    private $serialization = null;


    /**
     * 构造函数
     *
     * @param $host 主机
     * @param $port 端口
     * @param float $timeOut 超时时间,单位是s
     * @param obj $serialization 压缩解压缩工具
     */
    function __construct($host, $port, $serialization = null, $timeOut = 0.01)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeOut = $timeOut;
        $this->serialization = $serialization;
    }

    /**
     * 异步数据发送
     *
     * @param $msg
     * @return mixed
     */
    public function send($msg)
    {
        if ($this->serialization) $msg = $this->serialization->format($msg);

        $client = new \swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_SYNC);


        $result = $client->connect($this->host, $this->port, $this->timeOut, false);
        if($result){
            $client->send($msg);
            $client->close(true);
        }

        return $result;
    }

}