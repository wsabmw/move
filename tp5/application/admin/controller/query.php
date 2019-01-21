<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use QL\QueryList;
use app\admin\model\move;
use app\admin\model\tv;
use app\admin\model\tvnum;


class query extends Controller
{
    // 获取电影
  public function index() {
    
    for($i=0;$i<1;$i++) {
        $z = $i*30;
        $data = QueryList::get('http://v.qq.com/x/list/movie?iarea=-1&subtype=15&offset='.$z)
        ->rules([
          'title'=>array('strong>a','text'),
          'link'=>array('strong>a','href'),
          'img'=>array('ul>li>a>img','r-lazyload'),
          // 'actor'=>array('.figure_desc>a','title')
        ])->queryData();

       for($j=0;$j<30;$j++) {
          $data_z[] = $data[$j];
       }

    }

    $data_f = [];
    for($z=0;$z<count($data_z);$z++) {
        $jj = QueryList::get($data_z[$z]['link'])
              ->rules([
                  'title2'=>array('.director','text'),
                  'title3'=>array('.summary','text')
              ])->encoding('UTF-8')->removeHead()->queryData();
        
        $data_f[] = $jj;

        $move = new Move; 
        if($data_f) {
          $move->save([
            'name'=>$data_z[$z]['title'],
            'introduce'=>$data_f[$z][0]['title3'],
            'actor'=>$data_f[$z][0]['title2'],
            'move_img'=>$data_z[$z]['img'],
            'move_url'=>$data_z[$z]['link'],
            'type_id'=>'13'
        ]);
        }else {
          echo '错误';
        }
        
    }
    dd($data_f);
  }



  // 获取电视剧
  //  获取图片 名称 链接 
              //  ->获取每个链接后的集数的url 并相连 上级url 
  public function getTv() {
    //   进一步 集数 and 地址 
    for($i=1;$i<3;$i++){
      $data = QueryList::get('https://www.360kan.com/dianshi/list.php?year=all&area=all&act=all&cat=101&pageno='.$i)
              ->rules([
                'tv_img'=>array('.g-playicon>img','src'),
                'name'=>array('.detail>p>span','text'),
                'href'=>array('.js-tongjic','href')
              ])->queryData();
          // dd($data);

      for($j=0;$j<28;$j++) {
        $data_z[] = $data[$j];    //获取所有页数总的数据
      }
    }
    // dd($data_z[0]['name']);
    for($k=0;$k<count($data_z);$k++) {
       $tv_data = tv::create([
         'name'=>$data_z[$k]['name'],
         'tv_img'=>$data_z[$k]['tv_img'],
         'type_id'=>'1'
       ]);
       $data_id[] =  $tv_data['id'];
    }
    // dd($data_id[0]);
   
    for($f = 0;$f<count($data_z);$f++) {
      $tvj = QueryList::get('https://www.360kan.com'.$data_z[$f]['href'])
             ->rules([
               'x_link'=>array('.num-tab-main>a','href'),
               'num'=>array('.num-tab-main>a','text'),
             ])->queryData();

      $tvj = array_unique($tvj, SORT_REGULAR);   //对数组进行去重，把重复的集数去掉
      $tvj = array_values($tvj);        //对这个数组重新排序，使索引下标 正常

      for($d = 0;$d<count( $tvj ); $d++) {
          if($tvj[$d]['x_link'] == '#') {
            unset($tvj[$d]); 
          }
      }

      $tvj = array_values($tvj);    //再次重组 准备正常输出
      unset($tvj[$d-1]);
      // dd($tvj[0]['x_link']);
      // dd($data_f[0][0]['x_link']);
     $data_f[] = $tvj;                
     

      
    }
      // $data_f[] = $tvj;
      dd($data_f);            // ——————————明天要点 注重查看 第一个参数 第二个参数 之间的关系 
                                          // 注意不要用 data_id 肯能多次插入会出现问题 明天来了再想想

    // dd($data_f);
    // dd($data_id);  //每一部插入数据库返回的 ID
    // dd($tvj); //每一部有多少集

    for($b=0;$b<count($data_id);$b++) {
      echo $b;
      echo "<br>";
        for($x=0;$x<count($tvj);$x++) {
          echo "1";
          // dd()
          tvnum::create([
            // 'url'=>$data_f[$data_id[$b]][$x]['x_link'],
            // 'tv_js'=>$data_f[$data_id[$b]][$x]['num'],
            'url'=>$tvj[$x]['x_link'],
            'tv_js'=>$tvj[$x]['num'],
            'type_id'=>$data_id[$b]
          ]);
      }
    }





  }
  
  























}
