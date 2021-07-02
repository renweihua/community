/**
 * @Description: 鬼少浮窗播放器 5.0
 * @Author: 鬼少
 * @Author URL: http://tv1314.com
 */
jQuery.fn.extend({
	DragClose: function() {
		if (this.length) for (var a in $(this).data("options"))"dragObj" == a && $(this).data("options").dragObj.dostop()
	},
	Drag: function() {
		var a = {
			dragObj: $(this),
			parentObj: $(document),
			callback: null,
			isPhone: !1,
			lockX: !1,
			lockY: !1,
			maxWidth: 0,
			maxHeight: 0
		};
		arguments.length && (a = $.extend({}, a, arguments[0]));
		a.dragObj.data("options", a);
		var c = $(this)[0],
			b = a.dragObj,
			e = 0,
			d = 0,
			g = a.callback;
		"static" == $(this).css("position") && $(this).css("position", "relative");
		var m = 0,
			n = 0;
		a.isPhone ? (b.__start = function(f) {
			m = Math.max(a.parentObj.width(), a.maxWidth);
			n = Math.max(a.parentObj.height(), a.maxHeight);
			f = event.targetTouches[0];
			e = f.clientX - c.offsetLeft;
			d = f.clientY - c.offsetTop;
			b.on("touchmove", b.__move);
			b.on("touchend", b.__end);
			return !1
		}, b.__move = function(f) {
			touch = event.targetTouches[0];
			f = touch.clientX - e;
			var h = touch.clientX - d,
				k = c.offsetWidth,
				l = c.offsetHeight;
			0 > f ? f = 0 : f + k > m && (f = m - k);
			0 > h ? h = 0 : h + l > n && (h = n - l);
			a.lockX || (c.style.top = h + "px");
			a.lockY || (c.style.left = f + "px");
			g && g(b[0], f, h, k, l);
			return !1
		}, b.__end = function(a) {
			b.off("touchmove");
			b.off("touchend");
			_flag = !1;
			d = e = 0;
			g && g(b[0]);
			return !1
		}, b.dostart = function() {
			b.on("touchstart", b.__start)
		}, b.dostop = function() {
			b.off("touchstart");
			b.off("touchmove");
			b.off("touchend")
		}) : (b.__start = function(f) {
			m = Math.max(a.parentObj.width(), a.maxWidth);
			n = Math.max(a.parentObj.height(), a.maxHeight);
			e = f.clientX - c.offsetLeft;
			d = f.clientY - c.offsetTop;
			$(document).on("mousemove", b.__move);
			$(document).on("mouseup", b.__end);
			b[0].setCapture ? b[0].setCapture() : window.captureEvents && window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP);
			f.stopPropagation();
			f.preventDefault()
		}, b.__move = function(f) {
			var h = f.clientX - e,
				k = f.clientY - d,
				l = c.offsetWidth,
				p = c.offsetHeight;
			0 > h ? h = 0 : h + l > m && (h = m - l);
			0 > k ? k = 0 : k + p > n && (k = n - p);
			a.lockX || (c.style.top = k + "px");
			a.lockY || (c.style.left = h + "px");
			g && g(b[0], h, k, l, p);
			f.stopPropagation();
			f.preventDefault()
		}, b.__end = function(a) {
			b[0].releaseCapture ? b[0].releaseCapture() : window.releaseEvents && window.releaseEvents(Event.MOUSEMOVE | Event.MOUSEUP);
			$(document).off("mousemove");
			$(document).off("mouseup");
			d = e = 0;
			g && g(b[0]);
			a.stopPropagation();
			a.preventDefault()
		}, b.dostart = function() {
			b.on("mousedown", b.__start)
		}, b.dostop = function() {
			b.off("mousedown");
			$(document).off("mousemove");
			$(document).off("mouseup")
		});
		b.dostart()
	}
});
if (navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)) $("#wenkmPlayer").hide();
else if (top.location != self.location) $("#wenkmPlayer").hide();
else {
	var protocol = 'https:' == document.location.protocol ? 'https' : 'http';
	var wenkmList;
	var formatSecond = function(a) {
		return ("00" + Math.floor(a / 60)).substr(-2) + ":" + ("00" + Math.floor(a % 60)).substr(-2)
	},
	audio = new Audio,
	$player = $("#wenkmPlayer"),
	$tips = $("#wenkmTips"),
	$player1 = $(".switch-player", $player),
	$btns = $(".status", $player),
	$songName = $(".song", $player),
	$cover = $(".cover", $player),
	$songTime = $(".time", $player),
	$songList = $(".song-list .list", $player),
	$albumList = $(".album-list", $player),
	$songFrom = $(".player .artist", $player),
	$songFrom1 = $(".player .artist1", $player),
	$songFrom2 = $(".player .moshi", $player),
	$songFrom3 = $(".player .geci", $player),
	$songFrom4 = $(".player .switch-ksclrc", $player),
	songFrom33 = "开启",
	songFrom44 = "",
	roundcolor = "#6c6971",
	lightcolor = "#81c300",
	cur = "current",
	hosts = protocol + "://" + document.domain,
	domainurl = protocol + '://index.php?mod=musicapi&op=api3&ishttps=' + protocol + '&uid=',
	files = hosts +"/?plugin=gs_player",
	kscfile = hosts +"/content/plugins/gs_player/upload/krc/",
	lrcfile = hosts +"/content/plugins/gs_player/upload/lrc/",
	bgfile = hosts +"/content/plugins/gs_player/upload/bg/",
	songTotal = songId = 0,albumId = 1,
	random = !0,
	showLrc = !0,
	hasdefault = !1,
	hasgeci = !0,
	retries = 1,//不重试加载歌曲直接跳过
	retry = 1,
	lrcoffset = 0,//歌词偏移， 即将开发
	krcoffset = 5,//歌词偏移， 即将开发
	visTsMoving = !1,
	playisTsMoving = !1,
	currentFrameId = 0,
	musicfirsttip = !1;
	var cicleTime = null,
		cicleTime =  function() {
			if (audio.buffered.length) {
				var a = 100 * audio.buffered.start(currentFrameId) / audio.duration,
					b = 100 * audio.buffered.end(currentFrameId) / audio.duration;
				$(".playprogress .progressbg .progressbg2", $player).css({
					left: a + "%",
					width: b - a + "%"
				})
			}
		},
		wenkmMedia = {
			play: function() {
				localStorage.player_autoplay = 1;
				$player.addClass("playing");
				currentFrameId = GetCurrentFrame();
				hasLrc && (lrcTime = setInterval(wenkmLrc.lrc.play, 500), $("#wenkmLrc").addClass("show"), $(".switch-default").css("right", "65px"), hasdefault ? setTimeout(function() {
					$(".switch-ksclrc").show()
				}, 300) : $(".switch-ksclrc").show());
				hasKsc && (kscTime = setInterval(wenkmLrc.ksc.play, 95), $("#wenkmKsc").addClass("showPlayer"), $(".switch-default").css("right", "65px"), hasdefault ? setTimeout(function() {
					$(".switch-ksclrc").show()
				}, 300) : $(".switch-ksclrc").show());
			},
			pause: function() {
				$player.removeClass("playing");
				$(".switch-ksclrc").hide();
				$(".switch-default").css("right", "35px");
				hasLrc && wenkmLrc.lrc.hide();
				hasKsc && wenkmLrc.ksc.hide();
			},
			error: function() {
				setTimeout(function(){
					if(retry == retries){
						wenkmMedia.next();
						wenkmTips.show(wenkmList[albumId].data[songId].songname+' - 资源错误，播放下一曲');
						retry = 1;//重置重试次数
					}else{
						wenkmMedia.getInfos(wenkmMedia.getSongId(songId));
						wenkmTips.show('加载歌曲失败 - 重试第'+retry+'次');

						retry = retry+1;
					}
				},3000);
			},
			seeking: function() {
				if(audio.paused === true) audio.play();
				$player.addClass("playing");
				$cover.addClass("coverplay");
				wenkmLrc.load();
			},
			seeked: function() {
				currentFrameId = GetCurrentFrame();
			},
			volumechange: function() {
				var volumess = audio.volume;
				if(!1 == visTsMoving){
					$(".volumeprogress .progressbg .ts", $player).css("top", 100 * (1 - volumess) + "px");
					$(".volumeprogress .progressbg .progressbg1", $player).height(100 * volumess);
				}
				0 == volumess ? $(".player .bottom .volume", $player).addClass("fa-volume-off").removeClass("fa-volume-up fa-volume-down") : .4 > volumess ? $(".player .bottom .volume", $player).addClass("fa-volume-down").removeClass("fa-volume-up fa-volume-off") : $(".player .bottom .volume", $player).addClass("fa-volume-up").removeClass("fa-volume-off fa-volume-down");
			},
			getInfos: function(a) {
				currentFrameId = 0;
				$cover.removeClass("coverplay");
				songId = a;
				var srcinfo = handle_mp3(wenkmList[albumId].data[songId].file);
				audio.src = srcinfo[1];
				wenkmTips.show("开始从" + srcinfo[2] + "播放 - " + wenkmList[albumId].data[songId].songname);
				$songName.text(LimitStr(wenkmList[albumId].data[songId].songname));
				console.log(my_config['name_data'] + " - " + hosts + " - 当前播放：" + wenkmList[albumId].data[songId].songname + " - " + wenkmList[albumId].data[songId].singer);
				$songFrom.text(LimitStr(wenkmList[albumId].data[songId].singer));
				$songFrom1.text(wenkmList[albumId].name);
				wenkmList[albumId].data[songId].color = "0,0,0";
				$("li", $albumList).eq(albumId).addClass(cur).find(".artist").html("当前播放 > ").parent().siblings().removeClass(cur).find(".artist").html("").parent();
				"" == !$("ul", $songList).html() && $("[data-album=" + albumId + "]").length && ($("[data-album=" + albumId + "]").find("li").eq(songId).addClass(cur).siblings().removeClass(cur), $songList.mCustomScrollbar("scrollTo", $("li.current", $songList).position().top - 120));
				audio.volume = localStorage.player_volume;
				audio.play();
				handle_img(wenkmList[albumId].data[songId].pic);
				setTimeout(function() {
					wenkmLrc.load(); //开始歌词
					wenkmTips.show(wenkmList[albumId].data[songId].songname + songFrom44)
				},500);
			},
			getInfos1: function(a) { //载入不开始播放
				currentFrameId = 0;
				$cover.removeClass("coverplay");
				songId = a;
				var srcinfo = handle_mp3(wenkmList[albumId].data[songId].file);
				audio.src = srcinfo[1];
				$songName.text(LimitStr(wenkmList[albumId].data[songId].songname));
				$songFrom.text(LimitStr(wenkmList[albumId].data[songId].singer));
				$songFrom1.text(wenkmList[albumId].name);
				wenkmList[albumId].data[songId].color = "0,0,0";
				$("li", $albumList).eq(albumId).addClass(cur).find(".artist").html("暂停播放 > ").parent().siblings().removeClass(cur).find(".artist").html("").parent();
				"" == !$("ul", $songList).html() && $("[data-album=" + albumId + "]").length && ($("[data-album=" + albumId + "]").find("li").eq(songId).addClass(cur).siblings().removeClass(cur), $songList.mCustomScrollbar("scrollTo", $("li.current", $songList).position().top - 120));
				audio.volume = localStorage.player_volume;
				handle_img(wenkmList[albumId].data[songId].pic);
				setTimeout(function() {
					wenkmLrc.load(); //开始歌词
				},500);
			},
			getSongId: function(a) {
				return a >= songTotal ? 0 : 0 > a ? songTotal - 1 : a
			},
			next: function() {
				random ? wenkmMedia.getInfos(parseInt(Math.random() * songTotal)) : wenkmMedia.getInfos(wenkmMedia.getSongId(songId + 1))
			},
			prev: function() {
				random ? wenkmMedia.getInfos(parseInt(Math.random() * songTotal)) : wenkmMedia.getInfos(wenkmMedia.getSongId(songId - 1))
			},
			timeupdate: function() {
				cicleTime();
				playisTsMoving || ($(".playprogress .progressbg .ts", $player).css("left", 100 * (audio.currentTime / audio.duration).toFixed(2) + "%"),$(".playprogress .progressbg .progressbg1", $player).css("width", 100 * (audio.currentTime / audio.duration).toFixed(2) + "%"), $(".time", $player).text(formatSecond(audio.currentTime) + " / " + formatSecond(audio.duration)), audio.currentTime < audio.duration / 2 ? $(".status",$player).css("background-image", "linear-gradient(90deg, " + roundcolor + " 50%, transparent 50%, transparent), linear-gradient(" + (90 + 180 / (audio.duration / 2) * audio.currentTime) + "deg, " + lightcolor + " 50%, " + roundcolor + " 50%, " + roundcolor + ")") : $(".status",$player).css("background-image", "linear-gradient(" + (90 + 180 / (audio.duration / 2) * audio.currentTime) + "deg, " + lightcolor + " 50%, transparent 50%, transparent), linear-gradient(270deg, " + lightcolor + " 50%, " + roundcolor + " 50%, " + roundcolor + ")"))
			}
		},
		wenkmTipsTime = null,
		wenkmTips = {
			show: function(a) {
				clearTimeout(wenkmTipsTime);
				$("#wenkmTips").text(a).addClass("show");
				this.hide()
			},
			hide: function() {
				wenkmTipsTime = setTimeout(function() {
					$("#wenkmTips").removeClass("show");
					0 == musicfirsttip && (musicfirsttip = !0, wenkmTips.show("欢迎光临"+my_config['name_data'] + "！"+ hosts))
				}, 2000)
			}
		};
	audio.addEventListener("play", wenkmMedia.play, !1);
	audio.addEventListener("pause", wenkmMedia.pause, !1);
	audio.addEventListener("ended", wenkmMedia.next, !1);
	audio.addEventListener("playing", wenkmMedia.playing, !1);
	audio.addEventListener("volumechange", wenkmMedia.volumechange, !1);
	audio.addEventListener("error", wenkmMedia.error, !1);
	audio.addEventListener("seeking", wenkmMedia.seeking, !1);
	audio.addEventListener("timeupdate", wenkmMedia.timeupdate, !1);
	audio.addEventListener("seeked", wenkmMedia.seeked, !1);
	$('.album_play').click(function () {
		$(".loading,.loading1").show();
		$('#pjax-content').addClass("xg");
		var album_id = $(this).attr('data-id');
		$.post(U("/Music/albumSongs"), {"id": album_id},function(data){
			if(data){
				var len = wenkmList.length;
				wenkmList[len] = data;
				albumId = len;
				songId = 0;
				songTotal = data['list'].length;
				GSPlayer.playList.creat.album();
			}
   		}, "json");
		return false;
	});
	$('body').on("click", '.addplay', function () {
		$(".loading,.loading1").show();
		$('#pjax-content').addClass("xg");
		var song = {};
		$(this).addClass('gsplaying');
		$(".addplay").not($(this)).removeClass('gsplaying');
		song['pic'] = $(this).attr('data-pic');
		song['singer'] = $(this).attr('data-singer');
		song['songname'] = $(this).attr('data-songname');
		song['file'] = $(this).attr('data-file');
		if(song){
			var len = wenkmList[0].data.length;
			wenkmList[0].data[len] = song;
			albumId = 0;
			songTotal =len;
			wenkmPlayer.playList.creat.album1(!0);
			wenkmMedia.getInfos(len);
			localStorage.player_list = JSON.stringify(wenkmList[0].data);
		}
		return false;
	});
	$('body').on("click", '.gsplayer-switch', function () {
		var id = $(this).attr('data-id');
		//$(".gsplayer-list[data-id="+ id +"]").slideToggle(500);
		$(".gsplayer-list[data-id="+ id +"]").toggleClass("list-show");
		return false;
	});
	$(".switch-player", $player).click(function() {
		$player.toggleClass("show")
	});
	$(".pause", $player).click(function() {
		localStorage.player_autoplay = 0;
		$("li", $albumList).eq(albumId).addClass(cur).find(".artist").html("暂停播放 > ").parent().siblings().removeClass(cur).find(".artist").html("").parent();
		wenkmTips.show("暂停播放 - " + wenkmList[albumId].data[songId].songname);
		$cover.removeClass("coverplay");
		audio.pause();
		if(localStorage.player_autoplay == 1){setTimeout(function() {wenkmTips.show('下次访问将暂停播放');},1500);localStorage.player_autoplay = 0;}
	});
	$(".play", $player).click(function() {
		localStorage.player_autoplay = 1;
		$("li", $albumList).eq(albumId).addClass(cur).find(".artist").html("当前播放 > ").parent().siblings().removeClass(cur).find(".artist").html("").parent();
		wenkmTips.show("开始播放 - " + wenkmList[albumId].data[songId].songname);
		$cover.addClass("coverplay");
		audio.play();
		if(localStorage.player_autoplay == 0){setTimeout(function() {wenkmTips.show('下次访问将自动播放');},1500);localStorage.player_autoplay = 1;}
	});
	$(".prev", $player).click(function() {
		localStorage.player_autoplay = 1;
		wenkmMedia.prev();
		$(".loading,.loading1").show();
		$('#pjax-content').addClass("xg");
		if(localStorage.player_autoplay == 0){setTimeout(function() {wenkmTips.show('下次访问将自动播放');},1500);localStorage.player_autoplay = 1;}
	});
	$(".next", $player).click(function() {
		localStorage.player_autoplay = 1;
		wenkmMedia.next();
		$(".loading,.loading1").show();
		$('#pjax-content').addClass("xg");
		if(localStorage.player_autoplay == 0){setTimeout(function() {wenkmTips.show('下次访问将自动播放');},1500);localStorage.player_autoplay = 1;}
	});
	$(".random", $player).click(function() {
		$(this).addClass(cur);
		$(".loop", $player).removeClass(cur);
		random = !0;
		wenkmTips.show("随机播放");
		$songFrom2.html('<i class="random fa fa-random current"></i> 随机播放')
	});
	$(".loop", $player).click(function() {
		$(this).addClass(cur);
		$(".random", $player).removeClass(cur);
		random = !1;
		wenkmTips.show("顺序播放");
		$songFrom2.html('<i class="loop fa fa-retweet"></i> 顺序播放')
	});
	var $Volumeprogress = $(".volumeprogress .progressbg .ts", $player);
	$Volumeprogress.Drag({
		parentObj: $(".volumeprogress .progressbg", $player),
		lockY: !0,
		callback: function(a, b, c, e, g) {
			if (5 == arguments.length) {
				visTsMoving = !0;
				var d;
				d = ((84 - c) / 84).toFixed(2);
				1 < Number(d) ? d = 1 : 0 > Number(d) && (d = 0, $(a).css("top", "84px"));
				$(".volumeprogress .progressbg .progressbg1", $player).height(100 * d);
				audio.volume = d,localStorage.player_volume = d
			}else visTsMoving = !1
		}
	});
	var $playprogress = $(".playprogress .progressbg .ts", $player);
	$playprogress.Drag({
		parentObj: $(".playprogress .progressbg", $player),
		lockX: !0,
		callback: function(a, b, c, e, g) {
			if (5 == arguments.length) {
				localStorage.player_autoplay = 1;
				playisTsMoving = !0;
				var d = $(".playprogress .progressbg", $player).width(),
					d = b / (d - $(a).width()),
					d = d.toFixed(2);
				$(".playprogress .progressbg .progressbg1", $player).width(b);
				audio.currentTime = audio.duration * d;
			} else playisTsMoving = !1
		}
	});
	$(".playprogress .progressbg", $player).click(function(a) {
		localStorage.player_autoplay = 1;
		playisTsMoving = !1;
		a = a.pageX - $(this).offset().left;
		var b = $(this).width();
		a = (a / b).toFixed(2);
		audio.currentTime = audio.duration * a;
	});
	$(".volumeprogress .progressbg", $player).click(function(a) {
		a = ((100 - (a.pageY - $(this).offset().top-6)) / 100).toFixed(2);
		if(Number(a) > 1 ) a = 1;
		if(Number(a) < 0 ) a = 0;
		$(".volumeprogress .progressbg .ts", $player).css("top", 100 * (1 - a) + "px");
		$(".volumeprogress .progressbg .progressbg1", $player).height(100 * a);
		localStorage.player_volume = a;
		audio.volume = a
	});
	$(".switch-playlist").click(function() {
		$player.toggleClass("showAlbumList");
		var imgh = $(".blur").height();
	});
	$songList.mCustomScrollbar();
	$(".song-list .header,.song-list .fa-angle-right", $player).click(function() {
		$player.removeClass("showSongList")
	});
	$(".switch-ksclrc").click(function() {
		$player.toggleClass("ksclrc");
		$("#wenkmLrc").toggleClass("hide");
		$("#wenkmKsc").toggleClass("hidePlayer");
		$("#wenkmLrc").hasClass("hide") ? (hasLrc && $songFrom3.html('<i class="fa fa-times-circle"></i> Lrc歌词关闭'), hasKsc && $songFrom3.html('<i class="fa fa-times-circle"></i> 卡拉OK关闭'), wenkmTips.show("歌词显示已关闭"), hasgeci = !1, songFrom33 = "关闭", $songFrom4.html('<i class="fa fa-toggle-off" title="打开歌词"></i>')) : (hasLrc && $songFrom3.html('<i class="fa fa-check-circle"></i> Lrc歌词开启'), hasKsc && $songFrom3.html('<i class="fa fa-check-circle"></i> 卡拉OK开启'), wenkmTips.show("开启歌词显示"), hasgeci = !0, songFrom33 = "开启", $songFrom4.html('<i class="fa fa-toggle-on" title="关闭歌词"></i>'));
		$("#wenkmPlayer i").each(function() {
			$("#tooltip").remove();
			if (this.title) {
				var a = this.title;
				$(this).mouseover(function(b) {
					this.title = "";
					$("body").append('<div id="tooltip">' + a + "</div>");
					$("#tooltip").css({
						left: b.pageX - 15 + "px",
						top: b.pageY + 30 + "px",
						opacity: "0.8"
					}).fadeIn(1)
				}).mouseout(function() {
					this.title = a;
					$("#tooltip").remove()
				}).mousemove(function(a) {
					$("#tooltip").css({
						left: a.pageX - 15 + "px",
						top: a.pageY + 30 + "px"
					})
				})
			}
		})
	});
	wenkmPlayer.playList = {
		creat: {
			album: function() {
				var a = wenkmList.length,
				    b = "";
				$(".header", $albumList).text(my_config.name_data + " - 专辑列表(" + a + ")");
				for (var c = 0; c < a; c++) b += '<li><i class="fa fa-angle-right"></i><span class="index">' + (c + 1) + '</span><span class="artist"></span>《' + wenkmList[c].name + "》 - " + wenkmList[c].desc + "</li>";
				$(".list", $albumList).html("<ul>" + b + "</ul>").mCustomScrollbar();
				$("li", $albumList).click(function() {
					var a = $(this).index();
					$(this).hasClass(cur) ? wenkmPlayer.playList.creat.song(a, !0) : wenkmPlayer.playList.creat.song(a, !1);
					$player.addClass("showSongList")
				});
				songTotal = wenkmList[albumId].data.length;
				domainurl = domainurl + my_config['domain'] + '&';
				showLrc = my_config['lrcplay'];
				//音量设置
				if(my_config['volume'] == ''){
					my_config['volume'] = '0.8';
				}
				if(my_config['lrcplay'] == '0'){
					$(".switch-ksclrc").click();
				}
				var volume = localStorage.player_volume ? localStorage.player_volume : my_config['volume'];
				localStorage.player_volume = volume;
				if(localStorage.player_autoplay == '1' || (!localStorage.player_autoplay && my_config['autoplay'] == '1')){
					if(my_config['loadradom'] == "1"){
							$(".random", $player).click();
							wenkmMedia.getInfos(parseInt(Math.random() * songTotal));
					}else{
							wenkmMedia.getInfos(0);
					}
				}else{
					wenkmMedia.getInfos1(0);
				}
			},
			album1: function() {
				var a = wenkmList.length,
				    b = "";
				$(".header", $albumList).text(my_config.name_data + " - 专辑列表(" + a + ")");
				for (var c = 0; c < a; c++) b += '<li><i class="fa fa-angle-right"></i><span class="index">' + (c + 1) + '</span><span class="artist"></span>《' + wenkmList[c].name + "》 - " + wenkmList[c].desc + "</li>";
				$(".list", $albumList).html("<ul>" + b + "</ul>").mCustomScrollbar();
				$("li", $albumList).click(function() {
					var a = $(this).index();
					$(this).hasClass(cur) ? wenkmPlayer.playList.creat.song(a, !0) : wenkmPlayer.playList.creat.song(a, !1);
					$player.addClass("showSongList")
				});
			},
			song: function(a, b) {
				var songdata = wenkmList[a].data;
				songTotal = songdata.length;
				var c = "";
				$(".song-list .header span", $player).text(wenkmList[a].name + "(" + songTotal + ")");
				for (var d = 0; d < songTotal; d++) c += '<li><span class="index">' + (d + 1) + '</span><span class="artist"></span>' + songdata[d].songname + "</li>";// - " + wenkmList[a].artist_name[d] + "
				$("ul", $songList).html(c);
				$songList.attr("data-album", a);
				$songList.mCustomScrollbar("update");
				b ? ($("li", $songList).eq(songId).addClass(cur).siblings().removeClass(cur), $songList.mCustomScrollbar("scrollTo", $("li.current", $songList).position().top - 120)) : $songList.mCustomScrollbar("scrollTo", "top");
				$("li", $songList).click(function() {
					$(".loading,.loading1").show();
					$('#pjax-content').addClass("xg");
					albumId = a;
					$(this).hasClass(cur) ? ($(".loading,.loading1").hide(), wenkmTips.show("正在播放 - " + wenkmList[albumId].data[songId].songname)) : ($(this).addClass(cur).siblings().removeClass(cur), songId = $(this).index(), wenkmMedia.getInfos(songId))
				})
			}
		}
	};
	var hasLrc = !1,
		hasKsc = !1,
		kscLineNow1 = !1,
		kscLineNow2 = !1,
		lrcTimeLine = [],
		lrcHeight = $("#wenkmLrc").height(),
		lrcTime = null,
		kscTime = null,
		letterTime1 = null,
		letterTime2 = null,
		tempNum1 = 0,
		tempNum2 = 0,
		wenkmLrc = {
			load: function() {
				wenkmLrc.lrc.hide();
				wenkmLrc.ksc.hide();
				hasKsc = hasLrc = !1;
				$("#wenkmLrc,#wenkmKsc").html("");
				$.ajax({
					url: domainurl + 'action=kugou_newkrc&param=' + wenkmList[albumId].data[songId].songname.replace("?","") + '-' + wenkmList[albumId].data[songId].singer.replace("?","") +'&dt=' + audio.duration,
					cache: !1,
					dataType: "text",
					success: function(a) {
						if(a != ''){
							setTimeout(function() {
								hasKsc = !0;
								$songFrom3.html('<i class="fa fa-check-circle"></i> 卡拉OK' + songFrom33);
								songFrom44 = $("#wenkmLrc").hasClass("hide") ? " - 卡拉OK歌词已关闭！" : " - 卡拉OK歌词获取成功!";
								wenkmLrc.ksc.format(a);
								$(".switch-ksclrc").show()
							}, 200)
						}else{
								wenkmLrc.ksc.hide();
								if(wenkmList[albumId].type == '163'){
									$.ajax({
										url: domainurl + 'action=wy_lrc&param=' +  wenkmList[albumId].data[songId].songid,
										cache: !1,
										dataType: "script",
										success: function() {
											setTimeout(function() {
												hasLrc = !0;
												songFrom44 = $("#wenkmLrc").hasClass("hide") ? " - 歌词显示已关闭！" : " - 歌词匹配成功!";
												$songFrom3.html('<i class="fa fa-check-circle"></i> Lrc歌词' + songFrom33);
												wenkmLrc.lrc.format(cont)
											}, 200);
											return false;
										},
										error: function() {
											wenkmLrc.nolrc();
											return false;
										}
									});
								}else{
									$.ajax({
										url: domainurl + 'action=auto_lrc&param=' +  wenkmList[albumId].data[songId].songname.replace("?","") + "-" + wenkmList[albumId].data[songId].singer.replace("?",""),
										cache: !1,
										dataType: "script",
										success: function() {
											setTimeout(function() {
												hasLrc = !0;
												songFrom44 = $("#wenkmLrc").hasClass("hide") ? " - 歌词显示已关闭！" : " - 歌词匹配成功!";
												$songFrom3.html('<i class="fa fa-check-circle"></i> Lrc歌词' + songFrom33);
												wenkmLrc.lrc.format(cont)
											}, 200);
											return false;
										},
										error: function() {
											wenkmLrc.nolrc();
											return false;
										}
									});
								}
						}
						return false;
					},
					error: function() {
						wenkmLrc.nolrc();
						return false;
					}
				});
			},
			nolrc : function() {
				songFrom44 = " - 没有匹配到歌词!";
				$songFrom3.html('<i class="fa fa-times-circle"></i> 未匹配到歌词');
				$(".switch-default").css("right", "35px")
				wenkmLrc.lrc.hide();
				wenkmLrc.ksc.hide();
			},
			lrc: {
				format: function(a) {
					function b(a) {
						var b = a.split(":");
						a = +b[0];
						var c = +b[1].split(".")[0],
							b = +b[1].split(".")[1];
						return 60 * a + c + Math.round(b / 1000)
					}
					hasLrc = !0;
					a = a.replace(/\[[A-Za-z]+:(.*?)]/g, "").split(/[\]\[]/g);
					var c = "";
					lrcTimeLine = [];
					for (var d = 1; d < a.length; d += 2) {
						var f = b(a[d]);
						lrcTimeLine.push(f);
						if(a[d + 1]==''){a[d + 1] = a[d + 3];}
						if(a[d + 1]==''){a[d + 1] = a[d + 5];}
						c = 1 == d ? c + ('<li class="wenkmLrc' + f + ' current">' + a[d + 1] + "</li>") : c + ('<li class="wenkmLrc' + f + '">' + a[d + 1] + "</li>")
					}
					$("#wenkmLrc").html("<ul>" + c + "</ul>");
					setTimeout(function() {
						$("#wenkmLrc").addClass("show")
					}, 500);
					lrcTime = setInterval(wenkmLrc.lrc.play, 500)
				},
				play: function() {
					if(audio.paused === true){
						wenkmLrc.lrc.hide();
						wenkmLrc.ksc.hide();
					}
					var a = Math.round(audio.currentTime);//偏移
					0 < $.inArray(a, lrcTimeLine) && (a = $(".wenkmLrc" + a), a.hasClass(cur) || (a.addClass(cur).siblings().removeClass(cur), $("#wenkmLrc").animate({
						scrollTop: lrcHeight * a.index()
					})))
				},
				hide: function() {
					clearInterval(lrcTime);
					$("#wenkmLrc").removeClass("show")
				}
			},
			ksc: {
				format: function(a) {
					hasKsc = !0;
					var b = [],
						c = [],
						d = [],
						f = [],
						g = "",
						e = "b";
						a=a.replace(/[ ]/g,"");
						a.replace(/\'(\d*):(\d*).(\d*)\',\s*\'(\d*):(\d*).(\d*)\',\s*\'(.*)\',\s*\'(.*)\'/g, function(a, e, g, h, k, m, n, l, p) {
						a = k | 0;
						m |= 0;
						n |= 0;
						b.push(600 * (e | 0) + 10 * (g | 0) + Math.round((h | 0) / 100));
						c.push(600 * a + 10 * m + Math.round(n / 100));
						d.push(l);
						f.push(p)
						});
					for (a = 0; a < b.length; a++) {
						var k = "",
						l = f[a].split(",");
						0 <= d[a].indexOf("男|：") && (e = "m");
						0 <= d[a].indexOf("女|：") && (e = "g");
						0 <= d[a].indexOf("合|：") && (e = "t");
						d[a] = d[a].split("|");
						for (var h = 0; h < d[a].length; h++) k = "，" == d[a][h] ? k + ('<span class="blank"><em dir="' + l[h] + '"></em></span>') : k + ('<span><em dir="' + l[h] + '">' + d[a][h] + "</em></span>");
						g += '<div id="wenkmKsc' + c[a] + '" class="wenkmKsc' + b[a] + " line line" + (0 == a % 2 ? 1 : 2) + " " + e + '"><div class="bg">' + k + '</div><div class="lighter">' + k + "</div></div>"
					}
					$("#wenkmKsc").html(g);
					setTimeout(function() {
						$("#wenkmKsc").addClass("showPlayer")
					}, 500);
					kscTime = setInterval(wenkmLrc.ksc.play, 80)
				},
				play: function() {
					if(audio.paused === true){
						wenkmLrc.lrc.hide();
						wenkmLrc.ksc.hide();
					}
					var a = Math.round(10 * audio.currentTime) + krcoffset;//偏移
					if ($(".wenkmKsc" + (a + 10)).length && !$(".wenkmKsc" + (a + 10)).hasClass(cur)) {
						var b = $(".wenkmKsc" + (a + 10));
						b.addClass(cur);
						b.hasClass("line1") ? b.siblings(".line1").removeClass(cur) : b.siblings(".line2").removeClass(cur);
						setTimeout(function() {
							b.hasClass("line1") ? (wenkmLrc.ksc.showLetters.line1(b), kscLineNow1 = !0) : (wenkmLrc.ksc.showLetters.line2(b), kscLineNow2 = !0)
						}, 1E3)
					}
					$("#wenkmKsc" + (a - 30)).length && $("#wenkmKsc" + (a - 30)).removeClass(cur)
				},
				showLetters: {
					line1: function(a) {
						var b = $(".lighter span", a),
							c = b.eq(tempNum1++),
							c = $("em", c),
							d = +c.attr("dir");
						c.animate({
							width: "100%"
						}, d);
						tempNum1 < b.length ? letterTime1 = setTimeout(function() {
							wenkmLrc.ksc.showLetters.line1(a)
						}, d) : (tempNum1 = 0, kscLineNow1 = !1)
					},
					line2: function(a) {
						var b = $(".lighter span", a),
							c = b.eq(tempNum2++),
							c = $("em", c),
							d = +c.attr("dir");
						c.animate({
							width: "100%"
						}, d);
						tempNum2 < b.length ? letterTime2 = setTimeout(function() {
							wenkmLrc.ksc.showLetters.line2(a)
						}, d) : (tempNum2 = 0, kscLineNow2 = !1)
					}
				},
				hide: function() {
					clearInterval(kscTime);
					$("#wenkmKsc").removeClass("showPlayer")
				}
			}
		}
}
function LimitStr(a, b, c) {
	b = b || 8;
	c = c || "...";
	for (var d = "", f = a.length, g = 0, e = 0; g < 2 * b && e < f; e++) g += 128 < a.charCodeAt(e) ? 2 : 1, d += a.charAt(e);
	e < f && (d += c);
	return d
}
function file(a, b, c) {
	id = 0;
	albumId = b;
	songTotal = songId = 0;
	$player.removeClass("showSongList");
	$(".loading,.loading1").show();
	$('#pjax-content').addClass("xg");
	$.ajax({
		url: files + "&api=list",
		type: "GET",
		dataType: "script",
		success: function() {
			wenkmTips.show(wenkmList[albumId].song_album + " - 载入成功!");
			$(".switch-default").show();
			hasdefault = !0;
			wenkmPlayer.newplayList.creat.album();
			wenkmMedia.getInfos(c)
		},
		error: function(a, b, c) {
			wenkmTips.show("歌曲列表获取失败!");
			$(".switch-default").hide()
		}
	})
}
function music(a, b) {
	$(".loading,.loading1").show();
	$('#pjax-content').addClass("xg");
    for (var i = 0; i < wenkmList.length; i++) {
      if(wenkmList[i].id == a){
        if(b == 0){
			localStorage.player_autoplay = 1;
        	albumId = i;
        	wenkmMedia.getInfos(0);
            return false;
        }
        for (var x = 0; x < wenkmList[i].data.length; x++) {
      		if (wenkmList[i].data[x].songid == b) {
				localStorage.player_autoplay = 1;
                albumId = i;
        		wenkmMedia.getInfos(x);
                return false;
            }
  	  	}
            wenkmTips.show("该歌曲已被删除！");
    		$(".loading,.loading1").hide();
			$('#pjax-content').removeClass("xg");
			return false;
      }
    }
    wenkmTips.show("该歌单已被删除！");
	$(".loading,.loading1").hide();
	$('#pjax-content').removeClass("xg");
	return false;
}
function handle_mp3(data){
	var down_url = new Array();
	if(data.indexOf("content") >=0){
		down_url[1] = data;
		down_url[2] = '本地';
	}else if(data.indexOf("{wy}") >=0){
		var id = data.substring(4,data.indexOf("{key}"));
		down_url[1] = domainurl + "action=wy&param=" + id + "&.mp3";
		down_url[2] = '网易';
	}else if(data.indexOf("{xiami}") >=0){
		var id = data.substring(7,data.indexOf("{key}"));
		down_url[1] = domainurl + "action=xiami&param=" + id + "&.mp3";
		down_url[2] = '虾米';
	}else if(data.indexOf("{kugou}") >=0){
		var id = data.substring(7,data.indexOf("{key}"));
		down_url[1] = domainurl + "action=kugou&param=" + id + "&.mp3";
		down_url[2] = '酷狗';
	}else if(data.indexOf("{baidu}") >=0){
		var id = data.substring(7,data.indexOf("{key}"));
		down_url[1] = domainurl + "action=baidu&param=" + id + "&.mp3";
		down_url[2] = '百度';
	}else if(data.indexOf("{qq}") >=0){
		var id = data.substring(4,data.indexOf("{key}"));
		down_url[1] = domainurl + "action=qq&param=" + id + "&.mp3";
		down_url[2] = '腾讯';
	}else{
		down_url[1] = data;
		down_url[2] = '网络';
	}
	return down_url;
}
function handle_img(url){
	var imgurl,color,aplha;
	if(my_config['showcolor'] == '1'){
		if(url && url.indexOf("http") > 0 ){
			url = domainurl + "action=img_http&param=" + wenkmList[albumId].data[songId].pic;
		}else{
			url = domainurl + "action=img_auto&param=" + wenkmList[albumId].data[songId].file;
		}
		$.ajax({
			url: url,
			type: "GET",
			dataType: "script",
			success: function() {
				color = imginfo[0].img_color;
				imgurl = imginfo[0].img_url;
				fontcr = imginfo[0].font_color;
				aplha = '0.7';
				$(".loading,.loading1").hide();
				$('#pjax-content').removeClass("xg");
				loadblur(imgurl);
				loadpic(color,aplha,imgurl,fontcr);
			},
			error: function(a, b, f) {
				$(".loading,.loading1").hide();
				$('#pjax-content').removeClass("xg");
			}
		});
	}else{
		imgurl = url;
		color = '0,0,0';
		aplha = '0.4';
		loadpic(color,aplha,imgurl);
	}
}
function GetCurrentFrame() {
	var a = audio.buffered.length;
	if (1 == a) return 0;
	for (var b = $(".playprogress .progressbg", $player).width(), b = parseInt($(".playprogress .progressbg .ts", $player).css("left")) / b * audio.duration, c = 0; c < a; c++) if (b >= audio.buffered.start(c) && b <= audio.buffered.end(c)) return c;
	return 0
}
function loadpic(color,aplha,imgurl,fontcr) {
	$player.css({
		background: "rgba(" + color + ","+ aplha +")"
	});
	$player1.css({
		background: "rgba(" + color + ","+ aplha +")"
	});
	$tips.css({
		background: "rgba(" + color + ","+ aplha +")"
	});
	var b = new Image;
	b.src = imgurl;
	$cover.addClass("changing");
	b.onload = function() {
		$(".control,.infos").css({
			color: "rgba(" + fontcr + ")"
		});
		setTimeout(function() {
			$(".loading,.loading1").hide();
			$('#pjax-content').removeClass("xg");
		}, 800);
		setTimeout(function() {
			$cover.addClass("coverplay")
		}, 100);
		setTimeout(function() {
			$cover.removeClass("changing")
		}, 100)
	};
	b.onerror = function() {
			$(".loading,.loading1").hide();
			$('#pjax-content').removeClass("xg");
		b.src = bgfile + "defaultSinger.jpg"
	};
	$cover.html(b);
}
function loadblur(n) {
	if (!!window.ActiveXObject || "ActiveXObject" in window){
		$(".blur-img").remove();
		return false;
	}
	var v = "",
	f = $(".blur");
	var i = new Image;
	i.onload = function() {
		if (n == v) {
			var i = f.clone();
			f.parent().append(i.css({
				display: "none",
				top: -f.height()-3 + "px"
			}).attr("src", n)), i.fadeIn(1000, function() {
				i.css("top", "0px"), f.remove(), f = i
			})
		}
	}, i.src = n, v = n
}
//crc32
function crc32(e) {
	var t, r, n, a = new Array(256);
	for (t = 0; 256 > t; t++) {
		for (n = t, r = 0; 8 > r; r++) n = 1 & n ? n >> 1 & 2147483647 ^ 3988292384 : n >> 1 & 2147483647;
		a[t] = n
	}
	for ("string" != typeof e && (e = "" + e), n = 4294967295, t = 0; t < e.length; t++) n = n >> 8 & 16777215 ^ a[255 & n ^ e.charCodeAt(t)];
	return n ^= 4294967295, (n >> 3).toString(16)
}
function formatSecond(a) {
	return ("00" + Math.floor(a / 60)).substr(-2) + ":" + ("00" + Math.floor(a % 60)).substr(-2)
}
function base64_encode(str){
                var c1, c2, c3;
                var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
                var i = 0, len= str.length, string = '';
                while (i < len){
                        c1 = str.charCodeAt(i++) & 0xff;
                        if (i == len){
                                string += base64EncodeChars.charAt(c1 >> 2);
                                string += base64EncodeChars.charAt((c1 & 0x3) << 4);
                                string += "==";
                                break;
                        }
                        c2 = str.charCodeAt(i++);
                        if (i == len){
                                string += base64EncodeChars.charAt(c1 >> 2);
                                string += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
                                string += base64EncodeChars.charAt((c2 & 0xF) << 2);
                                string += "=";
                                break;
                        }
                        c3 = str.charCodeAt(i++);
                        string += base64EncodeChars.charAt(c1 >> 2);
                        string += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
                        string += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >> 6));
                        string += base64EncodeChars.charAt(c3 & 0x3F)
                }
                        return string
}
$(document).ready(function() {
	setTimeout("loadplayer()",1500)
	loadplayer = function () {
		if(localStorage.player_show && new Date().getTime()-localStorage.player_show < '1500'){
			$("#wenkmPlayer").hide();
		}else{
			$.ajax({
				url: files + "&api=list",
				type: "GET",
				dataType: "json",
				success: function(data) {
					my_config = data.config;
					wenkmList = data.list;
					if(localStorage.player_list) wenkmList[0].data = JSON.parse(localStorage.player_list);
					wenkmPlayer.playList.creat.album();
				},
				error: function(a, b, c) {
					wenkmTips.show("歌曲列表获取失败!")
				}
			});
			setInterval(function(){localStorage.player_show = new Date().getTime()},"1500");
		}
	}
	$(window).keydown(function(event) {
		if(event.keyCode == 192){
			if(audio.paused){
				$(".play", $player).click();
			}else{
				$(".pause", $player).click();
			}
		}
		if(event.ctrlKey == true && event.keyCode == 39){
			$(".next", $player).click();
		}
		if(event.ctrlKey == true && event.keyCode == 37){
			$(".prev", $player).click();
		}
		if(event.ctrlKey == true && event.keyCode == 38){
			var volume = (localStorage.player_volume + 0.1).toFixed(2) <= 1 ? (localStorage.player_volume + 0.1).toFixed(2) : 1.00;
			audio.volume = volume,localStorage.player_volume = volume
		}
		if(event.ctrlKey == true && event.keyCode == 40){
			var volume = (localStorage.player_volume - 0.1).toFixed(2) >= 0 ? (localStorage.player_volume - 0.1).toFixed(2) : 0.01;
			audio.volume = volume,localStorage.player_volume = volume
		}
	})
});
