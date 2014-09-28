<html>
<head>
    <link rel="icon" type="image/png" href="../favicon.png" />
    <title>Телефония, управление</title>
    <style type="text/css" media="all"> 
	@import url("./s.css");
        @import url("/css/smoothness/jquery-ui-1.8.1.custom.css");
    </style>
    <script src="../js/jquery-1.4.2.js"></script>
    <script src="../js/jquery.ui.core.js"></script>
    <script src="../js/jquery.ui.datepicker.js"></script>
    <script src="../js/i18n/jquery.ui.datepicker-ru.js"></script>
    <script language="javascript" type="text/javascript" src="../js/flot/jquery.flot.js"></script>
    <script src="../js/jquery.graphTable-0.2.js"></script>
    <script>
        $(document).ready(function() {
            //{JQ}
            //$("td[@colspan]").empty();
            //$("#maintbl > tr:nth-child(odd)").addClass("odd");
            //$("#maintbl > tr:nth-child(odd)").hide();
            //$("#maintbl").hide();
        });
    </script>
</head>
<body>
    <table border="0" cellpadding="6" cellspacing="0" width="100%" bgcolor="#f6f6f6">
	<tr><td height="40" bgcolor="#660000">{menu}</td></tr>
	<tr><td height="500" valign="top" class="body">{body}</td></tr>
	<tr><td height="30" bgcolor="#660000" style="color: #ffffff;">{footer}</td></tr>
    </table>
</body>
</html>