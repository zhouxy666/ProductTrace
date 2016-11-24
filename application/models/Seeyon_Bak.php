<?php
class Seeyon_Bak extends CI_Model{

	/*
	static $userNameOrPassWordNotMatched = '-1';
	static $getTokenFailed = '-2';
	static $exportFlow2Failed = '-3';
	static $launchCollaborationFailed = '-4';
	*/

	private $restUrl;
	private $userName;
	private $password;

	private $token;

	/**
	* 设置 restUrl
	*/
	public function setRestUrl($restUrl){
		$this->restUrl = $restUrl;
	}

    /**
     * 设置 rest用户的userName
     * @param $userName
     */
	public function setUserName($userName){
		$this->userName = $userName;
	}

    /**
     * 设置 rest用户的password
     * @param $password
     */
	public function setPassword($password){
		$this->password = $password;
	}

    /**
     * 检查输出内容是否为401
     * @param $output
     * @return array
     */
    public function check401($output){
        if(strpos($output,'HTTP Status 401')){
            show_error("没有使用该接口的权限",401,"401 error occurred");
        }
    }

    /**
     * 检查错误
     * @param $result
     */
	public function checkError($result){
		if(!isset($result['status'])){
			show_error("返回结果没有包含status 参数");
		}else{
			if($result['status']==0){
				if(isset($result['message'])){
                    show_error($result['message'],401,"发生系统错误，原因如下:");
				}else{
                    show_error("未知原因错误发生",401,"发生系统错误，原因如下:");
				}
			}
		}
	}

    /**
     * 	获取结果参数 从结果中获取参数
     * @param $result
     * @param $param
     * @return null
     */
	public function getResultParam($result,$param){
		if(isset($result['status']) && $result['status']==1 && isset($result[$param])){
			return $result[$param];
		}else{
			return null;
		}
	}


	/**
	* 获取token API
	*/
	public function getToken(){
		try{
			$url = $this->restUrl.'token';
			$data = array('userName'=>$this->userName,'password'=>$this->password);
			$output = postData($url,$data);
            $this->check401($output);

			if(!empty($output)){
				$obj = json_decode($output);
				if(isset($obj->id) && $obj->id!='-1'){
					$this->token = $obj->id;
					return array(
						'token'=>$obj->id,
						'status'=>1
					);
				}else{
					return array(
						'message'=>'用户名和密码不匹配',
						'status'=>0
					);
				}
			}else{
				return array(
					'message'=>'call 获取token API 失败',
					'status'=>0
				);
			}
		}catch(Exception $e){
			return array(
				'message'=>$e->getMessage(),
				'status'=>0
			);
		}
	}

	/**
	* 导出流程正文数据 API
	*/
	public function exportFlow2($flowId){
		try{
			$url = $this->restUrl."flow/data/$flowId?token=$this->token";
			$output = getData($url);
            $this->check401($output);

			if(!empty($output)){
				return array(
					'flowXML'=>$output,
					'status'=>1
				);
			}else{
				return array(
					'message'=>'call 导出流程正文数据 API 失败',
					'status'=>0
				);
			}
		}catch(Exception $e){
			return array(
				'message'=>$e->getMessage(),
				'status'=>0
			);
		}
	}


    /**
     * 发起协同 API
     * @param $templateCode
     * @param $senderLoginName
     * @param $subject
     * @param $data
     * @return array
     */
	public function launchCollaboration($templateCode,$senderLoginName,$subject,$data){
		try{
			$url = $this->restUrl."flow/$templateCode?token=$this->token";
			$data = array(
				'templateCode'=>$templateCode,
				'senderLoginName'=>$senderLoginName,
				'subject'=>$subject,
				'data'=>$data,
			);
			$output = postData($url,$data);
            $this->check401($output);

			if(!empty($output)){
				if(is_numeric($output)){
					return array(
						'flowId'=>$output,
						'status'=>1
					);
				}else{
					return array(
						'message'=>'发起协同失败',
						'status'=>0
					);
				}
			}else{
				return array(
					'message'=>'call 发起协同 API 失败',
					'status'=>0
				);
			}

		}catch(Exception $e){
			return array(
				'message'=>$e->getMessage(),
				'status'=>0
			);
		}
	}


    /**
     * 获取已结束流程ID API
     * @param $templateCode
     * @param $startTime
     * @param $endTime
     * @return array
     */
	public function getFlowFromFinish($templateCode,$startTime,$endTime){
		try{
			$url = $this->restUrl."flow/FromFinish/$templateCode/$startTime/$endTime/?token=$this->token";
			$output = getData($url);
            $this->check401($output);

			if(!empty($output)){
				return array(
					'flows'=>$output,
					'status'=>1
				);
			}else{
				return array(
					'message'=>'call 获取已结束流程ID API 失败',
					'status'=>0
				);
			}
		}catch(Exception $e){
			return array(
				'message'=>$e->getMessage(),
				'status'=>0
			);
		}
	}

    /**
     * 导出无流程 业务生成器 表单数据 API
     * @param $templateCode
     * @param $beginDateTime
     * @param $endDateTime
     * @return array
     */
	public function exportForm($templateCode,$beginDateTime,$endDateTime){
		try{
			$url = $this->restUrl."form/export/$templateCode?token=$this->token&beginDateTime=$beginDateTime&endDateTime=$endDateTime";
			$output = getData($url);
            $this->check401($output);

			if(!empty($output)){
				return array(
					'data'=>$output,
					'status'=>1
				);
			}else{
				return array(
					'message'=>'call 导出无流程表单数据 API 失败',
					'status'=>0
				);
			}
		}catch(Exception $e){
			return array(
				'message'=>$e->getMessage(),
				'status'=>0
			);
		}
	}



    /**
     * 创建部门 API 所属单位ID 部门名称 上级部门ID
     * @param $orgAccountId
     * @param $name
     * @param $superior
     * @return array
     */
    public function createOrgDepartment($orgAccountId,$name,$superior){
        try{
            //$id         = time();   //部门ID
            //$enabled    = true;     //是否启用
            //$sortId     = time() ;  //排序
            //$isGroup    = false;    //是否集团
            //$data = "{\"orgAccountId\":$orgAccountId,\"name\":\"$name\",\"enabled\":true,\"sortId\":\"$sortId\",\"isGroup\":false,\"superior\":$superior}";
            $data = "{\"orgAccountId\":$orgAccountId,\"name\":\"$name\",\"enabled\":true,\"isGroup\":false,\"superior\":$superior}";

            $url = $this->restUrl."orgDepartment?token=$this->token";
            $output = postData($url,$data,true);
            $this->check401($output);

            if(!empty($output)){
                $obj = json_decode($output,null,512,JSON_BIGINT_AS_STRING);
                if(isset($obj->success) && $obj->success==true) {
                    //if(isset($obj->successMsgs) && is_array($obj->successMsgs) && count($obj->successMsgs)>0 &&
                    //    isset($obj->successMsgs[0]->ent) && isset($obj->successMsgs[0]->ent->id)){
                    if(!empty($obj->successMsgs[0]->ent)){
                        return array(
                            'id'=>$obj->successMsgs[0]->ent->id,
                            'status'=>1
                        );
                    }else{

                        return array(
                            'message'=>$output,
                            'status'=>0
                        );
                    }

                }else{
                    return array(
                        'message'=>$output,
                        'status'=>0
                    );
                }
            }else{
                show_error("无法获取返回结果",401,"调用创建部门API...发生错误，原因如下:");
            }
        }catch(Exception $e){
            show_error($e->getMessage(),401,"调用创建部门API...发生错误，原因如下:");
        }
    }


    /**
     * 创建人员 API 单位ID 部门ID 用户名 密码
     * @param $orgAccountId
     * @param $orgDepartmentId
     * @param $name
     * @param $password
     * @return array
     */
    public function createOrgMember($orgAccountId,$orgDepartmentId,$name,$password){
        try{
            $data = "{\"orgAccountId\":$orgAccountId,\"orgDepartmentId\":$orgDepartmentId,\"name\":\"$name\",\"password\":\"$password\"}";

            $url = $this->restUrl."orgMember?token=$this->token";
            $output = postData($url,$data,true);
            $this->check401($output);

            if(!empty($output)){
                $obj = json_decode($output,null,512,JSON_BIGINT_AS_STRING);
                if(isset($obj->success) && $obj->success==true && !empty($obj->successMsgs[0]->ent)) {
                    return array(
                        'id'=>$obj->successMsgs[0]->ent->id,
                        'status'=>1
                    );
                }else{
                    show_error($output,401,"创建人员失败，原因如下:");
                }
            }else{
                show_error("无法获取返回结果",401,"调用创建人员API...发生错误，原因如下:");
            }
        }catch(Exception $e){
            show_error($e->getMessage(),401,"调用创建人员API...发生错误，原因如下:");
        }
    }



}


/**
* post数据
*/
function postData($url,$data,$isJson=false){
	//$header = array('Content-Type: application/json;charset=UTF-8');
    if(!$isJson){
        $json = json_encode($data);
    }else{
        $json = $data;
    }
	$ch = curl_init();//初始化curl
	curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
	curl_setopt($ch, CURLOPT_TIMEOUT, 60); //设置超时
	curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json',
	    'Content-Length: ' . strlen($json))
	);
	curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	$output = curl_exec($ch);//运行curl
	curl_close($ch);
	return $output;
}

/**
* get数据
*/
function getData($url){
	$ch = curl_init();//初始化curl
	curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
	curl_setopt($ch, CURLOPT_TIMEOUT, 60); //设置超时
	curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	$output = curl_exec($ch);//运行curl
	curl_close($ch);
	return $output;
}
?>