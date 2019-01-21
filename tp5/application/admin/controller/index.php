<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use QL\QueryList;

class index extends Controller
{
    //首页
    public function index(){
       return view('/index');
    }
    //首页-欢迎页
    public function welcome() {
        return view('/welcome');
    }
    //轮播页
    public function banner() {
        return view('/banner_list');
    }
    //分类
    public function cate() {
        return view('/cate/cate');
    }
    //ys--电影
    public function move() {
        return view('/ys/move');
    }
    //ys--电视
    public function tv() {
        return view('/ys/tv');
    }
    //ys--动漫
    public function anime() {
        return view('/ys/anime');
    }
    //ys--综艺
    public function variety() {
        return view('/ys/variety');
    }

}
