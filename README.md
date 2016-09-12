message-board
=============

ttlsa message-board

详情见web页面： http://www.ttlsa.com/php/thinkphp-message-board-front-ttlsa/

（一）ThinkPHP实践之留言板前台-TTLSA

这里是源码下载地址，随着项目深入，会不定时更改源码文件
https://github.com/tonyty163/message-board/archive/master.zip
在这里的大部分代码是通过thinkphp的官方视频教程来学习的。如果觉得文字太枯燥的话，也可以看视频学习。
视频链接：http://www.thinkphp.cn/document/313.html
以后我们知识点的讲解，会专注精力在项目讲解上，以结果为导向提供大家所需的知识讲解。
留言板是我们的第一个项目，那么我们就来认真分析下留言板的需求，以及由哪些功能模块来实现的。
1、前台功能
(1) 显示留言
需求：进入留言板，要先看到别人的留言
功能模块：展示系统内所有留言
(2) 签写留言
需求：写入自己的留言，可实名，可匿名
功能模块：插入留言，跳转回留言页面
2、后台功能
(1) 留言管理
需求：管理员需要对留言进行管理
功能模块：删除留言
在开始编写项目之前，需求分析是一个重点任务，好的需求分析，可以达到事半功倍的作用。
一、前台
1、需求分析
（1）显示内容：将数据库内所有留言信息显示，内容包括（留言ID，用户名，内容，留言时间）
（2）新增内容：用户名（可留空，默认为匿名用户），内容（必填内容）
2、建库
知识点：
1、数据库建库，建表，
2、数据类型了解
3、SQL语句了解
4、phpmyadmin工具熟悉
数据库需求
需要提交留言板内容
id（留言用户id） 类型为int，最大长度11位
username（用户名） 类型为char，最大长度16位，不能为空，默认值为“匿名用户”
content（内容） 类型为varchar，最大长度100位，不能为空
time（留言时间） 类型为timestamp，默认值为“当前时间”
由上所知，数据库结构为
库名message_broad
表名tb_broad
id int类型（11位长度），非负，非空，自增
username char类型（16位长度），非空
content varchar类型（100位长度），非空
time timestamp类型，非空，默认是当前时间戳
建库语句（如果不熟悉，可以采用一些工具来协助实现，如phpmyadmin），建好库board以后，直接复制以下SQL语句，即可完成建表操作

; html-script: false ]CREATE TABLE IF NOT EXISTS `think_board` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言id',
  `username` char(16) NOT NULL COMMENT '留言用户名',
  `content` varchar(100) NOT NULL COMMENT '留言内容',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '留言时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表' AUTO_INCREMENT=1 ;
1
2
3
4
5
6
7
; html-script: false ]CREATE TABLE IF NOT EXISTS `think_board` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言id',
  `username` char(16) NOT NULL COMMENT '留言用户名',
  `content` varchar(100) NOT NULL COMMENT '留言内容',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '留言时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表' AUTO_INCREMENT=1 ;
3、修改相关配置
知识点：
1、thinkphp运行机制，目录结构
2、index.php入口文件，App配置，调试模式，引用框架
3、config.php配置文件，配置数据库相关连接参数
数据库配置好后，可在项目目录的入口文件定义项目名称，文件创建位置在网站根目录下(我的本地环境中为C:/wamp/www/)message(自建项目目录)/index.php
在开始配置之前，建议把下载好的thinkphp框架文件拷贝到message目录下，在这里我下载的是3.1.3版本，目录名注意大小写（ThinkPHP），这样在你调用的时候可避免因为环境不同而产生的错误
接下来就要在index.php入口文件中定义相关项目，具体内容如下：

; html-script: false ]<?php 

	define('APP_NAME', 'board');		//项目名，可自定义
	define('APP_PATH', './board/');		//项目路径，访问入口文件，即可自动生成，无需手动创建，注意最后的'/'，如果不加，会将项目文件散落在message根目录下
	define('APP_DEBUG', TRUE);		//调试模式，如果在开发阶段，建议在开发阶段开启
	require './ThinkPHP/ThinkPHP.php';		//重点*，加了这条，框架才能生效
	
?>
1
2
3
4
5
6
7
8
; html-script: false ]<?php 
 
 define('APP_NAME', 'board'); //项目名，可自定义
 define('APP_PATH', './board/'); //项目路径，访问入口文件，即可自动生成，无需手动创建，注意最后的'/'，如果不加，会将项目文件散落在message根目录下
 define('APP_DEBUG', TRUE); //调试模式，如果在开发阶段，建议在开发阶段开启
 require './ThinkPHP/ThinkPHP.php'; //重点*，加了这条，框架才能生效
 
?>
老规矩，配置完这条，看见笑脸，就说明你配置框架成功了。同时可在message目录下，生成了board目录。
接下来我们需要完成相关的数据库配置，即可在控制器中完成相关数据库操作
文件路径为message/board/Conf/config.php
添加内容如下：

; html-script: false ]<?php
return array(

	//数据库相关配置
	'DB_TYPE' => 'mysql',		//数据库类型
	'DB_NAME' => 'board',		//数据库名
	'DB_USER' => 'root',		//连接数据库帐号
	'DB_PWD' => '',		//连接数据库密码
	'DB_PREFIX' => 'tb_'		//数据库表前缀，这样在后面实例化的时候就不需要再填写同样的表前缀了
);
?>
1
2
3
4
5
6
7
8
9
10
11
; html-script: false ]<?php
return array(
 
 //数据库相关配置
 'DB_TYPE' => 'mysql', //数据库类型
 'DB_NAME' => 'board', //数据库名
 'DB_USER' => 'root', //连接数据库帐号
 'DB_PWD' => '', //连接数据库密码
 'DB_PREFIX' => 'tb_' //数据库表前缀，这样在后面实例化的时候就不需要再填写同样的表前缀了
);
?>
4、建立控制器 Controller
知识点：
1、控制器目录结构
2、控制器运行流程
3、控制器IndexAction定义
4、默认方法index()定义，数据库实例化M方法，查询数据，插入数据，赋值变量，调用模板，并将变量传入模板
5、自定义表单提交方法handle，提交表单验证，异常处理，提取表单数据方法I，将表单数据插入数据库，成功和失败方法反馈，跳转方法U
配置全部完成，接下来就是我们开始写控制器的时候了，入口文件进来，都会找默认控制器，路径为message/board/Lib/action/IndexAction.class.php
将原有内容删除，拷贝以下内容替代：

; html-script: false ]<?php
//留言板首页控制器
class IndexAction extends Action {
//IndexAction为控制器名，需要跟文件名字IndexAction.class.php相对应。
//必须要集成Action类才能使用一些默认的函数
    public function index(){
	//index方法，显示内容方法
	//如果直接访问单入口http://localhost/message等效于访问http://localhost/message/index.php[/Index][/index]
	//index.php为入口文件，Index为控制器IndexAction,index为方法名index()。
    	$board = M('board')->select();
		//M方法等效于new Model('board')
		//上面这条语句等效于select * from tb_board(tb_在config.php文件中定义'DB_PREFIX' => 'tb_')
		$this->assign('board',$board)->display();
		//assign方法的两个参数，第一个为传进模板的变量名，第二个为传进变量的值。
		//display()方法会找对应的模板，在本例中是message/Tpl/Index/index.html,Index目录和index.html需要自建
    }
	//本例中index()方法，提取了tb_board表的全部数据，传入board变量，供index.html模板调用
    
	public function handle(){
	//handle()方法，发布内容方法
	//访问url为http://localhost/message/index.php/Index/handle，由于是提交表单方法，一般不建议直接访问
		if (!IS_POST) _404('页面不存在！', U('Index'));
		//如果不是通过post方法提交的内容，就会提示“页面不存在”（debug模式下可见），或者通过U方法跳转到Index首页(非debug模式)
    	$data = array(
    		'username' => I('username'),
    		'content' => I('content'),
    	);
		//获取表单提交数据
    	
    	if (M('board')->data($data)->add()) {
			$this->success('发布成功', U('index'));
    	} else {
			$this->error('发布失败，请重试');
    	}
		//实例化tb_board表，将$data通过data方法生成数据对象并添加进数据库，如果发布成功通过success方法在页面上提示“发布成功”，并通过U方法跳转回首页，如果失败通过error方法在页面上提示“发布失败，请重试”
		//要插入数据，一般先要通过create方法生成数据，那么data方法则是直接生成要操作的数据对象，具体示例可参考http://www.thinkphp.cn/document/323.html
    }
}
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
; html-script: false ]<?php
//留言板首页控制器
class IndexAction extends Action {
//IndexAction为控制器名，需要跟文件名字IndexAction.class.php相对应。
//必须要集成Action类才能使用一些默认的函数
    public function index(){
 //index方法，显示内容方法
 //如果直接访问单入口http://localhost/message等效于访问http://localhost/message/index.php[/Index][/index]
 //index.php为入口文件，Index为控制器IndexAction,index为方法名index()。
     $board = M('board')->select();
 //M方法等效于new Model('board')
 //上面这条语句等效于select * from tb_board(tb_在config.php文件中定义'DB_PREFIX' => 'tb_')
 $this->assign('board',$board)->display();
 //assign方法的两个参数，第一个为传进模板的变量名，第二个为传进变量的值。
 //display()方法会找对应的模板，在本例中是message/Tpl/Index/index.html,Index目录和index.html需要自建
    }
 //本例中index()方法，提取了tb_board表的全部数据，传入board变量，供index.html模板调用
    
 public function handle(){
 //handle()方法，发布内容方法
 //访问url为http://localhost/message/index.php/Index/handle，由于是提交表单方法，一般不建议直接访问
 if (!IS_POST) _404('页面不存在！', U('Index'));
 //如果不是通过post方法提交的内容，就会提示“页面不存在”（debug模式下可见），或者通过U方法跳转到Index首页(非debug模式)
     $data = array(
     'username' => I('username'),
     'content' => I('content'),
     );
 //获取表单提交数据
     
     if (M('board')->data($data)->add()) {
 $this->success('发布成功', U('index'));
     } else {
 $this->error('发布失败，请重试');
     }
 //实例化tb_board表，将$data通过data方法生成数据对象并添加进数据库，如果发布成功通过success方法在页面上提示“发布成功”，并通过U方法跳转回首页，如果失败通过error方法在页面上提示“发布失败，请重试”
 //要插入数据，一般先要通过create方法生成数据，那么data方法则是直接生成要操作的数据对象，具体示例可参考http://www.thinkphp.cn/document/323.html
    }
}
由于没有用到过于复杂的逻辑操作，所以就不引入模型了，直接进入视图Viewer的讲解。
5、建立模板表单 Viewer
知识点：
1、模板路径
2、模板表单html文件基础，可参考相关html文档
3、模板中调用thinkphp函数U
4、foreach标签用法（非html标签），循环输出变量中的各字段
5、将输出字段作为输入，传递给函数处理
控制器建立完毕，接下来就要基于控制器指定的路径建立Index目录index.html模板文件，路径为message/Tpl/Index/index.html，如有不理解，可以仔细查看控制器讲解

; html-script: false ]<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Message Board</title>
</head>
<body>
<!-- 以上内容又模板编写，不熟悉html的直接复制就ok了，需要自己编写的内容在<body>标签之后 -->
<div id='message-form'>
	<h1>留言板</span></h1>
	<form action="{:U('handle')}" method="post" name='board'>
	<!-- 这里要注意{:U('handle')}是个框架内置的thinkphp变量调用方法{:U}，U函数在之前已经介绍过了，最终这条url为http://localhost/message/index.php/Index/handle -->
	<!-- method必须为post方法，否则会触发控制器中的if (!IS_POST) -->
		<label for="username">用户名：</label>
		<input type="text" name='username' id='username'/>
		<!-- username输入框，name必须为username，否则无法正常写入，与控制器中I('username')相对应 -->

		<label for="content">内容：</label>
		<textarea name="content" id='content'></textarea>
		<!-- 同上 -->

		<input type="submit" value="提交" />
	</form>
</div>

<div>
	<foreach name='board' item='b'>
	<!-- 这里的board是控制器中的assign('board',$board)这段赋值的变量，b可以简单理解为board别名，供下面调用试用，可以为任意名字 -->
		<dl>
			<dt>
				<span class='num'>No.{$b.id}</span>
				<!-- 取出id -->
				<span class='username'>{$b.username}</span>
				<!-- 取出username -->
			</dt>
			<dd class='content'>
				<span class='content'>{$b.content}</span>
				<!-- 取出content -->
			</dd>
			<dd class='time'>
				<span class='time'>{$b.time|date='y-m-d H:i',###}</span>
				<!-- 取出时间，注意|之后的内容，调用了一个php函数date，参数值有两个，第一个是'='后面的'y-m-d H:i'，第2个是'###'，表示将原内容作为输入供函数处理，在本例中是将取出的时间戳传递给date函数格式化 -->
			</dd>
		</dl>
	</foreach>
	<!-- 循环输出段，将数据库中所有数据取出，并根据不同的字段填入对应的内容 -->
</div>
<!-- 到这里自己编写代码的部分已经结束，下面是body和html标签的关闭标签 -->
</body>
</html>
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
; html-script: false ]<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Message Board</title>
</head>
<body>
<!-- 以上内容又模板编写，不熟悉html的直接复制就ok了，需要自己编写的内容在<body>标签之后 -->
<div id='message-form'>
 <h1>留言板</span></h1>
 <form action="{:U('handle')}" method="post" name='board'>
 <!-- 这里要注意{:U('handle')}是个框架内置的thinkphp变量调用方法{:U}，U函数在之前已经介绍过了，最终这条url为http://localhost/message/index.php/Index/handle -->
 <!-- method必须为post方法，否则会触发控制器中的if (!IS_POST) -->
 <label for="username">用户名：</label>
 <input type="text" name='username' id='username'/>
 <!-- username输入框，name必须为username，否则无法正常写入，与控制器中I('username')相对应 -->
 
 <label for="content">内容：</label>
 <textarea name="content" id='content'></textarea>
 <!-- 同上 -->
 
 <input type="submit" value="提交" />
 </form>
</div>
 
<div>
 <foreach name='board' item='b'>
 <!-- 这里的board是控制器中的assign('board',$board)这段赋值的变量，b可以简单理解为board别名，供下面调用试用，可以为任意名字 -->
 <dl>
 <dt>
 <span class='num'>No.{$b.id}</span>
 <!-- 取出id -->
 <span class='username'>{$b.username}</span>
 <!-- 取出username -->
 </dt>
 <dd class='content'>
 <span class='content'>{$b.content}</span>
 <!-- 取出content -->
 </dd>
 <dd class='time'>
 <span class='time'>{$b.time|date='y-m-d H:i',###}</span>
 <!-- 取出时间，注意|之后的内容，调用了一个php函数date，参数值有两个，第一个是'='后面的'y-m-d H:i'，第2个是'###'，表示将原内容作为输入供函数处理，在本例中是将取出的时间戳传递给date函数格式化 -->
 </dd>
 </dl>
 </foreach>
 <!-- 循环输出段，将数据库中所有数据取出，并根据不同的字段填入对应的内容 -->
</div>
<!-- 到这里自己编写代码的部分已经结束，下面是body和html标签的关闭标签 -->
</body>
</html>

 
赞 13
赏
分享
 6
 
A+
发布日期：2014年02月13日  所属分类：PHP  ttlsa教程系列
标签：Thinkphp，留言板，前台，显示，插入，建库，建表，添加留言，需求分析，发布留言，模板，foreach，格式化

版权声明：本站原创文章，于3年前，由tonyty163发表，共 2073字。
转载请注明：（一）ThinkPHP实践之留言板前台-TTLSA | 运维生存时间 +复制链接
相关文章
Hugepage让你的PHP7更快
GCC PGO让你的PHP7更快
运维不再专业救火 不会PHP照样找出代码性能问题
使用redis、memchache实现PHP sessions共享
让PHP7达到最高性能的几个Tips
你可能喜欢
zabbix如何监控多个JMX/Redis等实例(105)
画图解释SQL联合语句
招系统运维(自动化基础运维方向)10-15k – 北京爱卡汽车网
svn 迁移到git下全过程
c++11 gcc4.8.x安装

 
 上一篇
（五）Thinkphp常用短函数使用简介-TTLSA
下一篇 
php_imagick超强的PHP图片处理扩展
发表评论


 昵称

 邮箱

 网址
滑动解锁才能提交

 
提交评论
目前评论：6   其中：访客  5   博主  0   引用   1

一 5 
回复 2016年04月19日 上午 10:01  沙发
撒啊啊
一 5 
回复 2016年04月18日 上午 9:25  板凳
一
等等 5 
回复 2016年03月13日 下午 3:01  地板
大
phper 5 
回复 2015年12月09日 下午 3:56  4楼
‘DB_PREFIX’ => ‘tb_’ 应该为’DB_PREFIX’ => ‘think_’
防风网 5 
回复 2015年06月28日 上午 9:55  5楼
不错的文章，内容才高八斗.

