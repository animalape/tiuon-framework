<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    	<title><? echo L('_DEBUG_SYSTEM_ERROR_');?></title>
        <meta name="robots" content="noindex,nofollow" />
<style>
html{
    font-family: sans-serif;
    -webkit-text-size-adjust: 100%;
}
body{
    margin: 0;
	-moz-user-select: none;
    -khtml-user-select: none;
    user-select: none;
}
#debug-button{position:fixed;bottom:1rem;right:0;z-index:2147483647;padding:.5rem;font-size:1rem;background:#ff9999;}
#btn-li{
	position: relative;
	margin-top:10px;
}
.btn-co{
	position: relative;
	margin-top:6px;
}
.btn{
	background-color:#e6e9e9;
	border-radius:3px;
	margin-left:3px;
	cursor: pointer;
}
.btn-basic{
	background-color:#e6e9e9;
	border-radius:3px;
	cursor: pointer;
}
/* 按鈕 */
.btn{
	font-size:13px;
	margin-left:3px;
	padding: 3px 6px 3px 6px;
	border-radius:3px;
	background-color:#e6e9e9;
	color:fff;
	cursor: pointer;
}
.btn-warning{
	background-color:#f5c10c;
}
/* 按鈕 完*/
.error{
	padding: 10px 10px 10px 10px;
}
#btn-debug{
	font-size:13px;
	padding: 3px 10px 10px 10px;
	border-bottom-left-radius:3px;
	border-bottom-right-radius:3px;
	background-color:#f5c10c;
	color:#fff;
	cursor: pointer;
}
#btn-debug:hover{
	background-color:#d4a70b;
}
.debug-button{
	position: relative;
	float: right;
	right:10px;
}
#debug-content{
	display:none;
	z-index:2147483647;
	margin:0;
	padding:0;
	width:100%;
	background-color:#f5c10c;
	font-size:.8rem;
	position:fixed;
	bottom:0;
}
#debug-content-li{
	padding: 15px 10px 10px 10px;
	height:20rem;
	overflow-y:scroll;
}
</style>
</head>
    <body>
    <div class="error">
	    <?php echo $showhtml; ?>
	    <div class="debug-button">
	        <span id="btn-debug"> <?php echo L('_DEBUG_MORE_');?> </span>
	    </div>
	</div>
	<!-- 內容 -->
	<div id="debug-content">
	    <!-- 菜單 -->
	    <div id="btn-li">
		    <span class="btn" id="sysmain" style="background-color:#69D34E;"><b><?php echo L('_DEBUG_ESSENTIAL_INFORMATION_');?></b></span>
		    <?php echo $tab;?>
		</div>
		<div id="debug-content-li">
	    	<div id="debug-lisysmain">
	    	    [<?php echo L('_DEBUG_SQL_QUERY_TIME_CONSUMING_');?>] : <?php echo self::$pdotime*1000;?><?php echo L('_DEBUG_MSEC_')?><br>
	    	    [<?php echo L('_DEBUG_SCRIPT_TOTAL_RUN_TIME_');?>] : <?php echo $runtime*1000;?><?php echo L('_DEBUG_MSEC_')?><br>
	    	    [<?php echo L('_DEBUG_MEMORY_USAGE_');?>] : <?php echo file_size_format(memory_get_usage());?><br>
	    	    [<?php echo L('_DEBUG_MEMORY_PEAK_');?>] : <?php echo file_size_format(memory_get_peak_usage());?>
	    	</div>
	    	<?php echo $html;?>
		</div>
	</div>
<script>
(function(win){
	var n = n || {}
	d = document;
	n = function(a){
		return{
			obj:a,
			on:function(a,b){
				var obj = this.obj;
				switch(a){
                    case 'click':
					    d.getElementById(obj).onclick = b
					break;
                }
			}
		}
	}
	win.t = n;
})(window);
    window.onload = function(){
	    t('btn-debug').on('click',function(){
	        var debug = document.getElementById('debug-content');
	        if(!debug.style.display || 'none' == debug.style.display){
		        debug.style.display = 'block';
	        }else{
		        debug.style.display = 'none';
	        }
        });
	    var li = document.getElementById('debug-content-li').getElementsByTagName('div');
		for(ii=0;ii<li.length;ii++){
			if('debug-lisysmain' != li[ii].id) li[ii].style.display = 'none';
		}
        var button = document.getElementById('btn-li').getElementsByTagName('span');
		for(i=0;i<button.length;i++){
			t(button[i].id).on('click',function(){
                var show = document.getElementById('debug-li'+this.id);
				for(b=0;b<button.length;b++){
					if(this.id == button[b].id && show.style.display == 'none'){
						button[b].style.background = '#69D34E';
					}else{
						button[b].style.background = '';
					}
				}
				for(a=0;a<li.length;a++){
					if('debug-li'+ this.id == li[a].id && show.style.display == 'none'){
						li[a].style.display = 'block';
					}else{
						li[a].style.display = 'none';
					}
				}
            });
		};
    }
</script>
</body>
</html>