<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Cleanup Deck Mobile</title>
	<meta name="Author" content="bryanmcbride.com" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="stylesheet" href="resources/jquery.mobile-1.1.0/jquery.mobile-1.1.0.min.css">
	<script src="resources/jquery-1.7.2.min.js"></script>
	<script type="text/javascript">
		$(document).bind("mobileinit", function () {
		    $.extend($.mobile, {
		        ajaxEnabled: false
		    });
		});
	</script>
	<script src="resources/jquery.mobile-1.1.0/jquery.mobile-1.1.0.min.js"></script>
</head>
<body>
<!-- loginPage -->
<div data-role="page" data-inset="true" id="loginPage">
	<div data-role="header" data-position="fixed">
		<h1 style="text-align: center; margin: 11px">Cleanup Deck Mobile</h1>
	</div>
	<div data-role="content">
		<form action="checklogin.php" method="POST">
			<fieldset>
				<label for="username">Username:</label>
				<input type="text" name="username" id="username" value=""  />
				<label for="mypassword">Password:</label>
				<input type="password" name="password" id="password" value="" />
				<input id="Submit1" type="submit" value="Login" data-role="button" data-inline="true" data-theme="b" />
				<p>By logging into this application, I acknowledge that I have read and agree to the <a href="#termsPage" data-transition="flip">Terms of Use</a> and <a href="#privacyPage" data-transition="flip">Privacy Policy</a>.</p>
				<hr />
				Don't have a login? <a href="mailto:info@terradex.com?subject=Cleanup Deck Mobile" data-role="button" data-icon="check" data-iconpos="right">Request a login</a>
			</fieldset>
	   </form>
	</div>
</div>
<!-- termsPage -->
<div data-role="page" id="termsPage">
	<div data-role="header">
		<h1 style="text-align: center; margin: 11px">Terms of Use</h1>
		<a href="#loginPage" data-icon="arrow-l">Home</a>
	</div>
	<div data-role="content">
		<div data-role="fieldcontain">
			<p>Terms of use stuff goes here...</p>
		</div>
	</div>
</div>
<!-- privacyPage -->
<div data-role="page" id="privacyPage">
	<div data-role="header">
		<h1 style="text-align: center; margin: 11px">Privacy Policy</h1>
		<a href="#loginPage" data-icon="arrow-l">Home</a>
	</div>
	<div data-role="content">
		<div data-role="fieldcontain">
		   <p>Privacy stuff goes here...</p>
		</div>
	</div>
</div>
</body>
</html>