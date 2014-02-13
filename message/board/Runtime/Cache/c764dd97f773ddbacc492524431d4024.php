<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Message Board</title>
</head>
<body>

<div id='message-form'>
	<h1>留言板</span></h1>
	<form action="<?php echo U('handle');?>" method="post" name='board'>

		<label for="username">用户名：</label>
		<input type="text" name='username' id='username'/>

		<label for="content">内容：</label>
		<textarea name="content" id='content'></textarea>

		<input type="submit" value="提交" />
	</form>
</div>

<div>
	<?php if(is_array($board)): foreach($board as $key=>$b): ?><dl>
			<dt>
				<span class='num'>No.<?php echo ($b["id"]); ?></span>
				<span class='username'><?php echo ($b["username"]); ?></span>
			</dt>
			<dd class='content'><?php echo ($b["content"]); ?></dd>
			<dd class='time'>
				<span class='time'><?php echo (date('y-m-d H:i',$b["time"])); ?></span>
			</dd>
		</dl><?php endforeach; endif; ?>
</div>
</body>
</html>