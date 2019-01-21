<?php
namespace app\index\controller;
use think\Request;
use app\index\model\move;
use app\index\model\cates;

class Index
{
    //首页
   public function index() {
       $move = move::where('type_id','1')->limit(12)->select();
       $move1 = move::where('type_id','2')->limit(9)->select();
    //    echo "<pre>";
    //    var_dump($move);
       
       return view('',[
           'move'=>$move,
           'move1'=>$move1
       ]);
}
    //电影
   public function move(Request $req) {
          if($req->id) {
            $fl = cates::where('pid','1')->select();
            $move = move::where('type_id',$req->id)->paginate(30,false,['query'=>request()->param()]);
          }else {
            $fl = cates::where('pid','1')->select();
             $move = move::where('type_id','1')->paginate(30,false,['query'=>request()->param()]);
          }
          
       return view('',[
           'type'=>$fl,
           'move'=>$move
       ]);
   }

   public function move_details(Request $req) {
       $mid = $req->mid;
       $data = move::where('id',$mid)->select();
       $move_tj = move::where('type_id',$data[0]['type_id'])->limit(12)->select();
       $move_ph = move::order('id','desc')->where('type_id',$data[0]['type_id'])->limit(9)->select();
    //    dd($data[0]['move_url']);
       return view('/index/move_details',[
           'data'=>$data,
           'move_tj'=>$move_tj,
           'move_ph'=>$move_ph
       ]);
   }



}
 