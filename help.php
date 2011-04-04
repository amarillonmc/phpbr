<?php

define('CURSCRIPT', 'help');

require './include/common.inc.php';

$extrahead = <<<EOT
<STYLE type=text/css>
BODY {
	FONT-SIZE: 10pt;MARGIN: 0; color:#eee; FONT-FAMILY: "Trebuchet MS","Gill Sans","Microsoft Sans Serif",sans-serif;
}
A {
	COLOR: #eee
}
A:visited {
	COLOR: #eee
}
A:active {
	color: #98fb98;text-decoration:underline
}
P{ line-height:16px
}

DIV {
	PADDING-LEFT: 1em;PADDING-right: 1em
}

.subtitle2 {
	font-family: "微软雅黑"; color: #98fb98; width: 100%;font-size: 16px;font-weight:900;
}

DIV.FAQ {
	PADDING-LEFT: 1em; line-height:16px
}
DIV.FAQ DT {
	COLOR: #98fb98
}
DIV.FAQ DD {
	
}

</STYLE>
EOT;


include template('help');



?>