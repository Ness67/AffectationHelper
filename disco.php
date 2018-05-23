<?php session_start(); $_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
} session_destroy(); ?>
<html>
<head>
<meta http-equiv="refresh" content="0;url=index.html">
<title>SB Admin 2</title>
<script language="javascript">
   window.location.href = "index.php"
</script>
</head>
<body>
Go to <a href="index.html">/index.html</a>
</body>
</html>