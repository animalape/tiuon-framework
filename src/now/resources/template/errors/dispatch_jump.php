<?php 
	$_data_['title']       = $prompt['title']?$prompt['title']:null;
	$_data_['status']      = $prompt['status']?$prompt['status']:0;
	$_data_['warning']     = $prompt['warning']?$prompt['warning']:0;
	$_data_['jump_status'] = $prompt['jump_status']?$prompt['jump_status']:0;
	$_data_['code']        = $prompt['code']?$prompt['code']:0;
	$_data_['message']     = $prompt['message']?$prompt['message']:null;
	$_data_['url']         = $prompt['url']?$prompt['url']:'javascript:history.back(-1)';
	$_data_['wait']        = $prompt['wait']?$prompt['wait']:30;
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php if($_data_['warning']){ echo L('_ERROR_JUMP_PROMPT_');}else{ echo $_data_['title'];}?></title>
<style type="text/css">
  *{margin:0;padding:0;color:#444}
.t1{border-bottom: 1px dashed #c6d9b6;color: #ff4000;font-weight: bold; margin: 0 0 20px; padding-bottom: 18px;}
.t2{margin-bottom:8px; font-weight:bold}
ol{margin:0 0 20px 22px;padding:0;}
ol li{line-height:30px}
body {margin: 0px; padding:0px; font-family:"微软雅黑", Arial, "Trebuchet MS", Verdana, Georgia,Baskerville,Palatino,Times; font-size:16px;background: #eceff0;}
div{margin-left:auto; margin-right:auto;}
a {text-decoration: none; color: #00b254;}
a:hover {color: #00b254;}
h1,h2,h3,h4 {
	margin:0;
	font-weight:normal; 
	font-family: "微软雅黑", Arial, "Trebuchet MS", Helvetica, Verdana ; 
}
h1{font-size:44px; color:#<?php if($_data_['warning']){echo 'f4a91c'; }else{ if($_data_['status']){echo '48d167';}else{echo 'e65a1c';} } ?>; padding:20px 0px 10px 0px;}
h2{color:#<?php if($_data_['status']){echo '90d3a0';}else{echo 'd08b6c';} ?>; font-size:16px; padding:10px 0px 25px 0px;}
#page{width:30%; padding:20px 20px 40px 40px; margin-top:130px;}
.text-right{
   font-size:12px;
   float:right;
   right:10px;
   color: #888;
}
.success{
	color:#48d167;
}
.error{
	color:#e65a1c;
}
.warning{
	color:#f4a91c;
}
.jump{
	font-size: 12px;
}
</style>
</head>
<body>
<div id="wrapper">
<div id="page" style=" line-height:30px;background:#fff;box-shadow: 0 1px 1px rgba(0,0,0,.05) ">
<?php if($_data_['warning']){?>
	<h1>!</h1>
	<p class="warning"><?php echo $_data_['message'];?></p>
	<p class="detail"></p>
	<p class="jump">
	    <?php echo L('_ERROR_PAGE_AUTO_');?> <a id="href" href="<?php echo $_data_['url'];?>"><?php echo L('_ERROR_JUMP_');?></a> <?php echo L('_ERROR_WAITING_TIME_');?>： <b id="wait"><?php echo $_data_['wait'];?></b>
	</p>
<?php }else{?>
	<?php if($_data_['jump_status']) {?>
	    <?php if($_data_['code']) {?>
	        <h1>:)</h1>
	        <p class="success"><?php echo $_data_['message'];?></p>
	    <?php }else{?>
	        <h1>:(</h1>
	        <p class="error"><?php echo $_data_['message'];?></p>
	    <?php } ?>
	    <p class="detail"></p>
	    <p class="jump">
	        <?php echo L('_ERROR_PAGE_AUTO_');?> <a id="href" href="<?php echo $_data_['url'];?>"><?php echo L('_ERROR_JUMP_');?></a> <?php echo L('_ERROR_WAITING_TIME_');?>： <b id="wait"><?php echo $_data_['wait'];?></b>
	    </p>
	<?php }else{?>
		<h1><?php echo $_data_['title'];?></h1>
		<h2><?php echo $_data_['message'];?></h2>
	<?php }?>
<?php }?>
</div>
</div>
<?php if($_data_['jump_status']){?>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
<?php }?>
</body>
</html>