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
namespace Think;

class Page{
    public $firstRow; // 起始行数
    public $listRows; // 列表每页显示行数
    public $parameter; // 分页跳转时要带的参数
    public $totalRows; // 总行数
    public $totalPages; // 分页总页面数
    public $rollPage   = 11;// 分页栏每页显示的页数
    public $lastSuffix = true; // 最后一页是否显示总页数
    public $page_url = '';   //分页连接的地址
    private $p       = 'p'; //分页参数名
    private $url     = ''; //当前链接URL
    private $nowPage = 1;

    // 分页显示定制
    private $config  = array(
        'indexpage' => '首页',
        'header' => '<span class="rows">共 %TOTAL_ROW% 条记录</span>',
        'prev'   => '<<',
        'next'   => '>>',
        'first'  => '1...',
        'last'   => '...%TOTAL_PAGE%',
        'endpage' => '末页',
        'theme'  => '%INDEXPAGE% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %ENDPAGE%',
    );

    /**
     * 架构函数
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows, $listRows=20, $parameter = array()) {
        C('VAR_PAGE') && $this->p = C('VAR_PAGE'); //设置分页参数名称
        /* 基础设置 */
        $this->totalRows  = $totalRows; //设置总记录数
        $this->listRows   = $listRows;  //设置每页显示行数
        $this->parameter  = empty($parameter) ? $_GET : $parameter;
        $this->nowPage    = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->nowPage    = $this->nowPage>0 ? $this->nowPage : 1;
        $this->firstRow   = $this->listRows * ($this->nowPage - 1);
    }

    /**
     * 定制分页链接设置
     * @param string $name  设置名称
     * @param string $value 设置值
     */
    public function setConfig($name,$value) {
//        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
//        }
    }

    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    private function url($page){
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }

    /**
     * 生成上一页的链接URL
     * @param  
     * @return string
     */
    public function prev_url(){
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 生成URL */
        if(empty($this->parameter)){
            unset($_GET[C('VAR_URL_PARAMS')]);
            $var =  !empty($_POST)?$_POST:$_GET;
            if(empty($var)) {
                $parameter  =   array();
            }else{
                $parameter  =   $var;
            }
            $parameter[$this->p]  =   '[PAGE]';
            $this->url            =   U(ACTION_NAME,$parameter);            
        }else{
            $this->parameter[$this->p] = '[PAGE]';
            if(!empty($this->page_url)){
                unset($this->parameter['page_url']);
                $this->url = U($this->page_url,$this->parameter);
            }else{
                $this->url = U(ACTION_NAME, $this->parameter);
            }
        }     

        $up_row  = $this->nowPage - 1;
        if($up_row > 0){
            return str_replace(urlencode('[PAGE]'), $this->nowPage - 1, $this->url);
        }else{
            return 'javascript:void(0)';
        }
        
    }

    /**
     * 生成下一页的链接URL
     * @param  
     * @return string
     */
    public function next_url(){
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 生成URL */
        if(empty($this->parameter)){
            unset($_GET[C('VAR_URL_PARAMS')]);
            $var =  !empty($_POST)?$_POST:$_GET;
            if(empty($var)) {
                $parameter  =   array();
            }else{
                $parameter  =   $var;
            }
            $parameter[$this->p]  =   '[PAGE]';
            $this->url            =   U(ACTION_NAME,$parameter);            
        }else{
            $this->parameter[$this->p] = '[PAGE]';
            if(!empty($this->page_url)){
                unset($this->parameter['page_url']);
                $this->url = U($this->page_url,$this->parameter);
            }else{
                $this->url = U(ACTION_NAME, $this->parameter);
            }
        }     

        $down_row  = $this->nowPage + 1;
        if($down_row <= $this->totalPages){
            return str_replace(urlencode('[PAGE]'), $down_row, $this->url);
        }else{
            return 'javascript:void(0)';
        }
    }

    /**
     * 组装分页链接
     * @return string
     */
    public function show() {
        if(0 == $this->totalRows) return '';

        /* 生成URL */
        if(empty($this->parameter)){
            unset($_GET[C('VAR_URL_PARAMS')]);
            $var =  !empty($_POST)?$_POST:$_GET;
            if(empty($var)) {
                $parameter  =   array();
            }else{
                $parameter  =   $var;
            }
            $parameter[$this->p]  =   '[PAGE]';
            $this->url            =   U(ACTION_NAME,$parameter);            
        }else{
            $this->parameter[$this->p] = '[PAGE]';
            if(!empty($this->page_url)){
                unset($this->parameter['page_url']);
                $this->url = U($this->page_url,$this->parameter);
            }else{
                $this->url = U(ACTION_NAME, $this->parameter);
            }
        }        

        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页临时变量 */
        $now_cool_page      = $this->rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->lastSuffix && $this->config['last'] = $this->totalPages;
        //首页
        $indexrow = 1;
        $index_page = ($this->totalPages > 1)?'<a href='.$this->url(1).'>首页</a>':'';
        //末页
        $end_page = ($this->totalPages > 1)?'<a href="'.$this->url($this->totalPages).'">末页</a>':'';
        //上一页
        $up_row  = $this->nowPage - 1;
        $up_page = $up_row > 0 ? '<a class="prev" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>' :  '<a class="prev disable" href="javascript:void(0)">' . $this->config['prev'] . '</a>';

        //下一页
        $down_row  = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ? '<a class="next" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>' : '<a class="disable" href="javascript:void(0)">' . $this->config['next'] . '</a>' ;
        if($this->totalPages <= 1){
            $up_page = '';
            $down_page = '';
        }
        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
            $the_first = '<a class="first" href="' . $this->url(1) . '">' . $this->config['first'] . '</a>';
        }

        //最后一页
        $the_end = '';
//        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
//            $the_end = '<a class="end" href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a>';
//        }

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
            if(($this->nowPage - $now_cool_page) <= 0 ){
                $page = $i;
            }elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
                $page = $this->totalPages - $this->rollPage + $i;
            }else{
                $page = $this->nowPage - $now_cool_page_ceil + $i;
            }
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    $link_page .= '<a class="num" href="' . $this->url($page) . '">' . $page . '</a>';
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    $link_page .= '<span class="current">' . $page . '</span>';
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%INDEXPAGE%','%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%','%ENDPAGE%'),
            array($index_page,$this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages,$end_page),
            $this->config['theme']);
        return "{$page_str}";
    }

    /**
     * 组装分页链接，比较短
     * @return string
     */
    public function show_short() {
        if(0 == $this->totalRows) return '';

        /* 生成URL */
        if(empty($this->parameter)){
            unset($_GET[C('VAR_URL_PARAMS')]);
            $var =  !empty($_POST)?$_POST:$_GET;
            if(empty($var)) {
                $parameter  =   array();
            }else{
                $parameter  =   $var;
            }
            $parameter[$this->p]  =   '[PAGE]';
            $this->url            =   U(ACTION_NAME,$parameter);            
        }else{
            $this->parameter[$this->p] = '[PAGE]';
            if(!empty($this->page_url)){
                unset($this->parameter['page_url']);
                $this->url = U($this->page_url,$this->parameter);
            }else{
                $this->url = U(ACTION_NAME, $this->parameter);
            }
        }        

        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页临时变量 */
        $short_rollPage = 3;
        $now_cool_page      = $short_rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->lastSuffix && $this->config['last'] = $this->totalPages;
        //首页
        $indexrow = 1;
        $index_page = ($this->totalPages > 1)?'<a href='.$this->url(1).'>首页</a>':'';
        //末页
        $end_page = ($this->totalPages > 1)?'<a href="'.$this->url($this->totalPages).'">末页</a>':'';

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $short_rollPage; $i++){
            if(($this->nowPage - $now_cool_page) <= 0 ){
                $page = $i;
            }elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
                $page = $this->totalPages - $short_rollPage + $i;
            }else{
                $page = $this->nowPage - $now_cool_page_ceil + $i;
            }
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    $link_page .= '<a class="num" href="' . $this->url($page) . '">' . $page . '</a>';
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    $link_page .= '<span class="current">' . $page . '</span>';
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%INDEXPAGE%','%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%','%ENDPAGE%'),
            array($index_page,$this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages,$end_page),
            $this->config['theme']);
        return "{$page_str}";
    }    
}
