//update time
function updateTime(timing,mode)
{
	if(timing){
		t = timing;
		tm = mode;
		h = Math.floor(t/3600);
		m = Math.floor((t%3600)/60);
		s = t%60;
		// add a zero in front of numbers<10
		h=checkTime(h);
		m=checkTime(m);
		s=checkTime(s);
		$('timing').innerHTML = h + ':' + m + ':' +s;
		tm ? t++ : t--;
		setTimeout("updateTime(t,tm)",1000);
	}
	else{
		window.location.href = 'index.php';
	}
}

//icon select
function iconMover(){
	gd = document.valid.gender[0].checked ? 'm' : 'f';
	inum = document.valid.icon.selectedIndex;
	$('iconImg').innerHTML = '<img src="img/' + gd + '_' + inum + '.gif" alt="' + inum + '">';
}
function dniconMover(){
	dngd = document.cmd.dngender[0].checked ? 'm' : 'f';
	dninum =document.cmd.dnicon.selectedIndex;
	$('dniconImg').innerHTML = '<img src="img/' + dngd + '_' + dninum + '.gif" alt="' + dninum + '">';
}

function postCommand(){
	$('submit').disabled = true;
	var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBody(document.forms['cmd']);
	oXmlHttp.open("post", "command.php", true);
	oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (oXmlHttp.status == 200) {
				showGamedata(oXmlHttp.responseText);
				$('submit').disabled = false;
			} else {
				showNotice(oXmlHttp.statusText);
			}
		}
	};
	oXmlHttp.send(sBody);
}


function showGamedata(sGamedata){
	gamedata = sGamedata.parseJSON();
	if(gamedata['url']) {
		window.location.href = gamedata['url'];
	} else if(!gamedata['main']) {
		window.location.href = 'index.php';
	}
	if(gamedata['team']) {
		$('team').value = gamedata['team'];
		gamedata['team'] = '';
	}

	for(var id in gamedata) {
		if((id == 'toJSONString')||(!gamedata[id])) {continue;}
		$(id).innerHTML = gamedata[id];
	}
}

function showNotice(sNotice) {
	$('notice').innerText = sNotice;
}

function sl(id) {
	$(id).checked = true;
}

function showNews(n){
	var oXmlHttp = zXmlHttp.createRequest();

	oXmlHttp.open("post", "news.php", true);
	oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (oXmlHttp.status == 200) {
				showNewsdata(oXmlHttp.responseText);
			} else {
				showNotice(oXmlHttp.statusText);
			}
		}
	};
	oXmlHttp.send('newsmode=' + n);
}
function showNewsdata(newsdata) {
	news = newsdata.parseJSON();
	if(news['msg']){
		newchat = '';
		for(var nid in news['msg']) {
			if(nid == 'toJSONString') {continue;}
			newchat += news['msg'][nid];
		}
		$('newsinfo').innerHTML = newchat;
	} else {
		$('newsinfo').innerHTML = news;
	}
}
function showAlive(mode){
	window.location.href = 'alive.php?alivemode=' + mode;
}

var refchat = null;

function chat(mode,reftime) {
	clearTimeout(refchat);
	var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBody(document.forms['sendchat']);
	oXmlHttp.open("post", "chat.php", true);
	oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (oXmlHttp.status == 200) {
				showChatdata(oXmlHttp.responseText);
			} else {
				showNotice(oXmlHttp.statusText);
			}
		}
	};
	oXmlHttp.send(sBody);
	if(mode == 'send'){$('chatmsg').value = '';}
	rtime = reftime;
	refchat = setTimeout("chat('ref',rtime)",rtime);
}
/*
function showChatdata(jsonchat) {
	chatdata = jsonchat.parseJSON();
	if(chatdata['msg']) {
		$('lastcid').value=chatdata['lastcid'];
		newchat = String(chatdata['msg']).replace(/<br>,/,'<br>');
		$('chatlist').innerHTML = newchat + $('chatlist').innerHTML;
	}
}
*/

function showChatdata(jsonchat) {
	chatdata = jsonchat.parseJSON();
	if(chatdata['msg']) {
		$('lastcid').value=chatdata['lastcid'];
		newchat = '';
		for(var cid in chatdata['msg']) {
			if(cid == 'toJSONString') {continue;}
			newchat += chatdata['msg'][cid];
		}
		$('chatlist').innerHTML = newchat + $('chatlist').innerHTML;
	}			
}
