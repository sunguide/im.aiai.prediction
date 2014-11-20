<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: quick <onep2p@163.com> <http://www.onep2p.com>
// +----------------------------------------------------------------------
// | BoliyaSDK.class.php 2013-03-27
// +----------------------------------------------------------------------

class BoliyaSDK extends ThinkOauth{
	/**
	 * 获取requestCode的api接口
	 * @var string
	 */
	protected $GetRequestCodeURL = 'http://www.boliya168.com/Oauth/getRedirectUri.shtml';

	/**
	 * 获取access_token的api接口
	 * @var string
	 */
	protected $GetAccessTokenURL = 'http://www.boliya168.com/Oauth/getAccessToken.shtml';

	/**
	 * API根路径
	 * @var string
	 */
	protected $ApiBase = 'http://www.boliya168.com/';
	
	/**
	 * 组装接口调用参数 并调用接口
	 * @param  string $api    铂利亚API
	 * @param  string $param  调用API的额外参数
	 * @param  string $method HTTP请求方法 默认为GET
	 * @return json
	 */
	public function call($api, $param = '', $method = 'GET', $multi = false){		
		/* 调用公共参数 */
		$params = array(
			'access_token' =>$this->Token['access_token'],
		);
		$data = $this->http($this->url($api), $this->param($params, $param), $method);
		return json_decode($data, true);
	}
	
	/**
	 * 解析access_token方法请求后的返回值
	 * @param string $result 获取access_token的方法的返回值
	 */
	protected function parseToken($result, $extend){
		$data = json_decode($result, true);
		if($data['access_token'] && $data['expires_in'] && $data['refresh_token']){
			$this->Token    = $data;
			$data['openid'] = $this->openid();
			return $data;
		} else
			throw new Exception("获取铂利亚ACCESS_TOKEN出错：{$data['error']}");
	}
	
	/**
	 * 获取当前授权应用的openid
	 * @return string
	 */
	public function openid(){
		if(isset($this->Token['openid']))
			return $this->Token['openid'];
		
		$data = $this->call('Oauth/getLoggedInUser.shtml');
		if(!empty($data['uid']))
			return $data['uid'];
		else
			throw new Exception('没有获取到铂利亚用户ID！');
	}
	
}