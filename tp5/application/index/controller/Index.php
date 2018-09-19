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
//               customer_id int ,
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
        return $this->fetch("index");
    }

    function loginPro(){
        $b = Db::table("tb_admin")->where('admin_user',input("username"))->where("admin_pass",input('password'))->find();
        if(!empty($b)){
           return $this->success("登录成功","Index/gongneng");
        }else{
            return $this->error("登录失败,重新登录","Index/index");
        }
    }
    function gongneng(){
        return $this->fetch();
    }

//    类结束
}