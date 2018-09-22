<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17 0017
 * Time: 21:34
 */

namespace app\index\controller;

use think\Db;
use think\Controller;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Index extends Controller
{
    function Index(){
//        $sql = "create table if not exists tb_admin(
//                id int primary key auto_increment not null,
//                admin_user varchar(50) not null,
//                admin_pass varchar(50)not null
//              )engine=myisam default charset=utf8";
//        Db::query($sql);
//        $sql = "create table if not exists tb_car(
//                id int primary key auto_increment not null,
//                username varchar(50)not  null ,
//                user_number char(50)not null,
//                car_number varchar(50)not null,
//                tel char (11)not null,
//                address varchar (50)not null,
//                car_road mediumtext not  null,
//                car_content mediumtext not null
//              )engine=myisam default charset=utf8";
//        Db::query($sql);
//        $sql = "create table if not exists tb_car_log(
//                log_id int primary key auto_increment not null,
//               car_number varchar(50) not null,
//               car_log varchar(50) not null,
//               log_date datetime not null,
//               fahuo_id varchar(50) not null
//              )engine=myisam default charset=utf8";
//        Db::query($sql);
//        $sql = "create table if not exists tb_customer(
//               customer_id int primary key auto_increment ,
//               customer_user varchar (50) not null,
//               customer_tel varchar (50) not null,
//               customer_address varchar (80) not null
//              )engine=myisam default charset=utf8";
//        Db::query($sql);
//        $sql = "create table if not exists tb_shopping(
//             id int auto_increment primary  key ,
//             car_number varchar (50),
//             fahuo_content mediumtext,
//             fahuo_id varchar (50),
//             fahuo_user varchar (50),
//             fahuo_time datetime,
//             fahuo_ys varchar(20),
//             fahuo_fk varchar(20),
//             car_tel char(11),
//             shouhuo_user varchar (50),
//             shouhuo_address varchar (50),
//             fahuo_address varchar (50),
//             fahuo_tel char(11),
//             shouhuo_tel char(11)
//              )engine=myisam default charset=utf8";
//        Db::query($sql);
         $b = session("admin_user");
        if(!empty($b)){
            $this->assign("flag","xinxi");
            return $this->fetch("index/gongneng");
        }
        return $this->fetch("index");
    }

    function loginPro(){
        $b = Db::table("tb_admin")->where('admin_user', input("username"))->where("admin_pass", input('password'))->find();
        if(!empty($b)){
            session("admin_user",$b['admin_user']);
            session("admin_pass",$b['admin_pass']);
            $this->success("登录成功","Index/gongneng");
        }else{
            $this->error("登录失败,重新登录","Index/index");
        }
    }
    function gongneng(){
        $flag = input("flag");
        $this->assign("flag",$flag);
        switch ($flag){
            case "cyxxcx":
                $data = input("post.");
                $res = null;
              if(!empty($data)){
                  $res =  $this->selectCYXX($data);
              }
              $this->assign("res",$res);
                break;
            case "fhd":
                $data = input("post.");
                $this->assign("fahuo",null);
                if(!empty($data)){
                    $b = Db::table("tb_shopping")->insert($data);
                    $this->assign("fahuo",$b);
                }
                break;
            case "fhdcx":
                $vo = null;
                $data = input("post.");
                $da = input("get.");
                if(!empty($data)){
                    if($data['select'] == 'fahuo_user'){
                        $vo =  Db::table("tb_shopping")->where("fahuo_user",$data['key'])->find();
                    }else{
                        $vo =  Db::table("tb_shopping")->where("fahuo_id",$data['key'])->find();
                    }
                    $this->assign("vo",$vo);
                }else if(!empty($da)){
                    Db::table("tb_shopping")->where("fahuo_id",$da['fahuo_id'])->delete();
                }
                break;
            case "hzfhdqr":
                $vo = null;
                $data = input("post.");
                $da = input("get.");
                if(!empty($data)){
                    $vo =  Db::table("tb_shopping")->where("fahuo_id",$data['key'])->find();
                }else if(!empty($da)){
                    $b  = Db::table("tb_shopping")->where("fahuo_id",$da['fahuo_id'])->update(["fahuo_ys"=>1]);
                    $vo =  Db::table("tb_shopping")->where("fahuo_id",$da['fahuo_id'])->find();
                }
                $this->assign("vo",$vo);
                break;
            case "khxxgl":
                $res = Db::table("tb_customer")->select();
                $this->assign("res",$res);
                $data = input("post.");
                $da = input("get.");
                if(!empty($data)){
                    Db::table("tb_customer")->insert($data);
                    $res = Db::table("tb_customer")->select();
                    $this->assign("res",$res);
                }else if(!empty($da)){
                    Db::table("tb_customer")->where("customer_id",$da['customer_id'])->delete();
                    $res = Db::table("tb_customer")->select();
                    $this->assign("res",$res);
                }
                break;
            case "cyxxgl":
                $data = DB::table("tb_car")->select();
                $this->assign("res",$data);
                break;
        }
        return $this->fetch();
    }

    function  loginout(){
        session("admin_user",null);
        session("admin_pass",null);
        $this->success("注销成功","index/index");
    }
    function updatePass(){
        $b = Db::table("tb_admin")->where("admin_user",input("admin_user"))->update(["admin_pass"=>input("pass")]);
        if(!empty($b)){
            $this->success("修改成功","Index/gongneng");
        }else{
            $this->error("修改失败","Index/gongneng");
        }
    }
    //确认用户名和密码匹配
    function querenUser(){
        $data = input("post.");
        $b = Db::table("tb_admin")->where("admin_user",$data['admin_user'])->where("admin_pass",$data['admin_pass'])->find();
        if(!empty($b)){
//            密码正确
            return 1;
        }else{
            return 0;
        }
    }
    //查询车源信息
    function selectCYXX($data){
        $go = $data['go'];
        $to = $data['to'];
        $res = Db::table("tb_car")->where("car_road like '%$go%' ")->where("car_road like '%$to%' ")->select();
        return $res;
    }

    function selectCar(){
        $data = input("d");
        $res = Db::table("tb_car")->where("car_number",$data)->find();
        return $res;
    }

    function del_car(){
        $b = Db::table("tb_car")->where("car_number",input('d'))->delete();
        return $b;
    }

    function add_car(){
        $data = input("post.");
        $b = Db::table("tb_car")->insert($data);
        return $b;
    }

    function update_car(){
        $data = input("post.");
        $b = Db::table("tb_car")->where("car_number",$data['car_number'])->update($data);
        return $b;
    }







//    类结束
}