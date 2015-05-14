<!DOCTYPE html>
<head>
<style>@import url('<?php echo $rel_dir; ?>/includes/css/infusion.css')</style>
<script>
$('a').on("click",function(){
	$('div.changelog_version').slideUp("slow");
	var id = $(this).attr("id");
	$('div[id="'+id+'"]').slideDown("slow");
});
</script>
</head>
<body>
<div style="overflow: auto">
<a href='#' class='displayVersion' id='v1.1'>Version 1.1 - Released May 14, 2015</a>
<div id='v1.1' class='changelog_version' style="display: block">
<pre>
	[->] Fixed issue where Mode was non-optional
	[->] Minor interface changes
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v1.0'>Version 1.0 - Released May 11, 2015</a>
<div id='v1.0' class='changelog_version'>
<pre>
	[->] Infusion Created
</pre>
</div>
</div>
</body>
</html>