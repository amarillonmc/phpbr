<?php

define('CURSCRIPT', 'help');

require_once './include/common.inc.php';

$extrahead = <<<EOT
<STYLE type=text/css>BODY {
	FONT-SIZE: 9pt; MARGIN: 0.5em; COLOR: white; FONT-FAMILY: "Trebuchet MS","Gill Sans","Microsoft Sans Serif",sans-serif; BACKGROUND-COLOR: black
}
A {
	COLOR: #f00
}
A:visited {
	COLOR: #f00
}
A:active {
	COLOR: #f00
}
TD {
	PADDING-RIGHT: 3px; PADDING-LEFT: 3px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px
}
TABLE.menutbl TD {
	PADDING-RIGHT: 7px; PADDING-LEFT: 7px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px
}
TABLE.weapon {
	BORDER-RIGHT: #fff 1px solid; BORDER-TOP: #fff 1px solid; MARGIN-LEFT: 20px; BORDER-LEFT: #fff 1px solid; BORDER-BOTTOM: #fff 1px solid
}
TABLE.weapon TH {
	BACKGROUND: #333; COLOR: lime
}
TABLE.weapon TR.t2 TD {
	BACKGROUND: #ccc; COLOR: black
}
TABLE.weapon TR.t1 TD {
	BACKGROUND: #888; COLOR: #eee
}
DIV {
	PADDING-LEFT: 1em
}
.box {
	BORDER-RIGHT: #d0d0d0 1px solid; PADDING-RIGHT: 5px; BORDER-TOP: #d0d0d0 1px solid; PADDING-LEFT: 5px; BACKGROUND: #ccc; PADDING-BOTTOM: 5px; MARGIN: 1em; BORDER-LEFT: #d0d0d0 1px solid; COLOR: black; PADDING-TOP: 5px; BORDER-BOTTOM: #d0d0d0 1px solid
}
.box B {
	PADDING-RIGHT: 3px; PADDING-LEFT: 3px; BACKGROUND: #333; PADDING-BOTTOM: 3px; COLOR: lime; PADDING-TOP: 3px
}
DIV.prolog {
	PADDING-RIGHT: 1em; PADDING-LEFT: 1em; PADDING-BOTTOM: 1em; COLOR: #efa; PADDING-TOP: 1em
}
DIV.prolog B {
	COLOR: lime
}
DIV.FAQ {
	PADDING-LEFT: 1em
}
DIV.FAQ DT {
	COLOR: #cfb
}
DIV.FAQ DD {
	
}
TABLE.thanktbl TD {
	BORDER-BOTTOM: #aaa 1px dotted
}
</STYLE>
EOT;


include_once template('help');



?>