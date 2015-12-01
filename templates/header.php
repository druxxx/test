    <html>
    <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>FORUM</title>
<meta name="description" content=""/>
<meta name="keywords" content=""/>
    <?$cTemplate->LoadStatic(array(
        'style.css',
        'https://code.jquery.com/jquery-2.1.4.min.js',
        'http://code.jquery.com/ui/1.10.4/jquery-ui.js',
        'jquery-my-ui.js',
        'objXHR.js',
        'validate.js',
        'common.js',
    ));
    ?>

	<script type="text/javascript">
        var website  = "<?=WEBSITE?>";
		var gObjSite =
		{
			"is_login"     :  <?=(IS_LOGIN ? 1 : 0)?>,
			"curl"         : "<?=substr($_SERVER['REQUEST_URI'], 0,strpos($_SERVER['REQUEST_URI'], '?'))?>"
		};
  </script>
  <script>
    window.onload = function() {
        DynamicHistory.OnLoad();
    };
  </script>

</head>