<?php
session_start();
class XinwenxinxiController extends CommonController {

    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Headers:Origin,Content-Type,Accept,token,X-Requested-With,device');
    }
    public $columData = [
		'id','addtime'
                ,'xinwenbiaoti'
                ,'xinwenleibie'
                ,'xinwentupian'
                ,'xinwenshipin'
                ,'xinwenxiangqing'
                ,'faburiqi'
                ,'thumbsupnum'
                ,'crazilynum'
                ,'clicktime'
                ,'clicknum'
            ];
        /**
     * 分页，列表
     * get
     */
    public function page(){
        $token = $this->token();
        $tokens = json_decode(base64_decode($token),true);
        if (!isset($tokens['id']) || empty($tokens['id'])) exit(json_encode(['code'=>403,'msg'=>"你还没有登录。"]));
        $userid = $tokens['id'];
		$where = " where 1 ";//查询条件
				
        $page = isset($_REQUEST['page'])?$_REQUEST['page']:"1";
        $limt = isset($_REQUEST['limit'])?$_REQUEST['limit']:"10";
        $sort = isset($_REQUEST['sort'])?$_REQUEST['sort']:"id";
        $order = isset($_REQUEST['order'])?$_REQUEST['order']:"asc";
        foreach ($_REQUEST as $k => $val){
			if(in_array($k, $this->columData)){
                if ($val != ''){
                    $where.= " and ".$k." like '".$val."'";
                }
			}
        }
                                                                                                                                                                                        		        
                                                $sql = "select * from `xinwenxinxi` ".$where;
        $count = table_sql($sql);
        if ($count->num_rows < 1){
            $numberCount = 0;
        }else{
            $numberCount = $count->num_rows;
        }
        $page_count = ceil($numberCount/$limt);//页数
        $startCount = ($page-1)*$limt;
                $lists = "select * from `xinwenxinxi` ".$where." order by ".$sort." ".$order." limit ".$startCount.",".$limt;
                $result = table_sql($lists);
        $arrayData = array();
        if ($result->num_rows > 0) {
            while ($datas = $result->fetch_assoc()){
                array_push($arrayData,$datas);
            }
        }
        exit(json_encode([
            'code'=>0,
            'data' => [
                "total" => $numberCount,
                "pageSize" => $limt,
                "totalPage" => $page_count,
                "currPage" => $page,
                "list" => $arrayData
            ]
        ]));
		    }
    /**
     * 分页，列表list
     * get
     */
    public function lists(){
                $page = isset($_REQUEST['page'])?$_REQUEST['page']:"1";
        $limt = isset($_REQUEST['limit'])?$_REQUEST['limit']:"10";
        $sort = isset($_REQUEST['sort'])?$_REQUEST['sort']:"id";
        $order = isset($_REQUEST['order'])?$_REQUEST['order']:"asc";
        $refid = isset($_REQUEST['refid']) ? $_REQUEST['refid'] : "0";
		$where = " where 1 ";//查询条件
				foreach ($_REQUEST as $k => $val){
			if(in_array($k, $this->columData)){
				$where.= " and ".$k." like '".$val."'";
			}
        }
		                                                                                                                                                                                        		                                        $sql = "select * from `xinwenxinxi`".$where;
        $count = table_sql($sql);
        if ($count->num_rows < 1){
            $numberCount = 0;
        }else{
            $numberCount = $count->num_rows;
        }
        $page_count = ceil($numberCount/$limt);//页数
        $startCount = ($page-1)*$limt;
        $lists = "select * from `xinwenxinxi` ".$where." order by ".$sort." ".$order." limit ".$startCount.",".$limt;
        $result = table_sql($lists);
        $arrayData = array();
        if ($result->num_rows > 0) {
            while ($datas = $result->fetch_assoc()){
                array_push($arrayData,$datas);
            }
        }
        exit(json_encode([
            'code'=>0,
            'data' => [
                "total" => $numberCount,
                "pageSize" => $limt,
                "totalPage" => $page_count,
                "currPage" => $page,
                "list" => $arrayData
            ]
        ]));
		    }
    
    

    /**
     * 新增数据接口
     * post
     */
    public function save(){
        $token = $this->token();
        $tokens = json_decode(base64_decode($token),true);
        if (!isset($tokens['id']) || empty($tokens['id'])) exit(json_encode(['code'=>403,'msg'=>"你还没有登录。"]));
        $uid = $tokens['id'];
                $keyArr = $valArr = array();
        $tmpData = strval(file_get_contents("php://input"));//Content-Type: application/json 需要用到php://input 处理输入流
        if (!empty($tmpData)&& isset($tmpData)){
            $postData = json_decode($tmpData,true);
                        foreach ($postData as $key => $value){
                if (in_array($key, $this->columData)){
                    if(!empty($value) || $value === 0) {
                        array_push($keyArr,"`".$key."`");
                        array_push($valArr,"'".$value."'");
                    }
                }
            }
        }
        $k = implode(',',$keyArr);
        $v = implode(',',$valArr);
                        $sql = "INSERT INTO `xinwenxinxi` (".$k.") VALUES (".$v.")";
                $result = table_sql($sql);
		        exit(json_encode(['code'=>0]));
    }
    /**
     * 新增数据接口 add
     * post
     */
    public function add(){
        $keyArr = $valArr = array();
		                        $tmpData = strval(file_get_contents("php://input"));
        if (!empty($tmpData)&& isset($tmpData)){
            $postData = json_decode($tmpData,true);
			            foreach ($postData as $key => $value){
                if (in_array($key, $this->columData)){
                    if(!empty($value) || $value === 0) {
                        array_push($keyArr,"`".$key."`");
                        array_push($valArr,"'".$value."'");
                    }
                }
            }
        }
        $k = implode(',',$keyArr);
        $v = implode(',',$valArr);
                $sql = "INSERT INTO `xinwenxinxi` (".$k.") VALUES (".$v.")";
        $result = table_sql($sql);
		        exit(json_encode(['code'=>0]));
    }
    /**
     * 更新接口
     * post
     */
    public function update(){
        $tmpData = strval(file_get_contents("php://input"));
        $postData = json_decode($tmpData,true);
        $v = array();
        foreach ($postData as $key => $value){
            if (in_array($key, $this->columData)){
                if ($key == "id"){
                    $id = $value;
                }
                if(!empty($value) || $value === 0) {
                    array_push($v,$key." = '".$value."'");
                }
            }
        }
        $value = implode(',',$v);
         $sql = "UPDATE xinwenxinxi SET ".$value." where id = ".$id;
        $result = table_sql($sql);
        exit(json_encode(['code'=>0]));
    }
    /**
     * 删除
     * post
     */
    public function delete(){
        $ids = strval(file_get_contents("php://input"));//发现接收的是字符串
        preg_match_all('/\d+/',$ids,$arr);
        $str = implode(',',$arr[0]);//拼接字符，
        $sql = "delete from xinwenxinxi WHERE id in({$str})";
        $result = table_sql($sql);
        exit(json_encode(['code'=>0]));
    }
    /**
     * 查询一条数据
     * get
     */
    public function info($id=false){

        $token = $this->token();
        $tokens = json_decode(base64_decode($token),true);
        if (!isset($tokens['id']) || empty($tokens['id'])) exit(json_encode(['code'=>403,'msg'=>"你还没有登录。"]));
        $userid = $tokens['id'];
        $name = isset($_REQUEST['name'])? $_REQUEST['name']:"";
        if (!empty($id)){
            $where = "`id` = ".$id;
        }else{
            $where = "`name` = ".$name;
        }
                $this->andone('xinwenxinxi',$id,$column='clicknum',$num=1);
                        $clicktime = "update xinwenxinxi set clicktime = ".date('Y-m-d H:i:s',time())." where ".$where;
        table_sql($clicktime);
                $sql = "select * from `xinwenxinxi` where ".$where;
        $result = table_sql($sql);
        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $lists = $row;
            }
        }
        exit(json_encode([
            'code'=>0,
            'data'=> $lists
        ]));
    }
    /**
     * 查询一条数据
     * get
     */
    public function detail($id=false){
                $name = isset($_REQUEST['name'])? $_REQUEST['name']:"";
        if ($id){
            $where = "`id` = ".$id;
        }else{
            $where = "`name` = ".$name;
        }
                $this->andone('xinwenxinxi',$id,$column='clicknum',$num=1);
                        $clicktime = "update xinwenxinxi set clicktime = ".date('Y-m-d H:i:s',time())." where ".$where;
        table_sql($clicktime);
                $sql = "select * from `xinwenxinxi` where ".$where;
        $result = table_sql($sql);
        if (!$result) exit(json_encode(['code'=>500,'msg'=>"查询数据发生错误。"]));
        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $lists = $row;
            }
        }
        exit(json_encode([
            'code'=>0,
            'data'=> $lists
        ]));
    }
            /**
     * 赞和踩
     **/
    public function thumbsup($id=false){
        $type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
        if ($type==1){
            $result = $this->andone('xinwenxinxi',$id,$column = 'thumbsupnum',$num = 1);
        }else{
            $result = $this->andone('xinwenxinxi',$id,$column = 'crazilynum',$num = 1);
        }
        exit(json_encode(['code'=>0,'msg'=>"投票成功"]));
    }
        
        public function autoSort(){
		$limt = isset($_REQUEST['limit'])?$_REQUEST['limit']:"5";
				$sort = 'clicknum';
				$lists = "select * from `xinwenxinxi` order by ".$sort." desc limit 0,".$limt;
		$result = table_sql($lists);
		$arrayData = array();
		if ($result->num_rows > 0) {
			while ($datas = $result->fetch_assoc()){
				array_push($arrayData,$datas);
			}
		}
		exit(json_encode([
			'code'=>0,
			'data' => [
				"total" => 0,
				"pageSize" => $limt,
				"totalPage" => 0,
				"currPage" => 0,
				"list" => $arrayData
			]
		]));
	}
    
    /**
     * 获取需要提醒的记录数接口
     * get
     */
    public function remind($columnName,$type){
        $remindStart = isset($_GET['remindStart'])?$_GET['remindStart']:"";
        $remindEnd = isset($_GET['remindEnd'])?$_GET['remindEnd']:"";
        if ($type == 1){//数字
            $sql = "select * from `xinwenxinxi` where ".$columnName."<='".$remindEnd."' and ".$columnName.">='".$remindStart."'";
        }else{
            $sql = "select * from `xinwenxinxi` where ".$columnName."<='".date("Y-m-d",strtotime("+".$remindStart." day"))."' and ".$columnName.">='".date("Y-m-d",strtotime("+".$remindStart." day"))."'";
        }
        $result = table_sql($sql);
        exit(json_encode(['code'=> 0 ,'count' => $result->num_rows]));
    }
}

