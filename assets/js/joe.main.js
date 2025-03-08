Joe.DOMContentLoaded.main ||= () => {
	console.log('调用：Joe.DOMContentLoaded.main');

	/** 自定义导航栏高亮 */
	if (document.querySelector('.navbar-nav')) {
		$('.navbar-nav li.menu-item').removeClass('current-menu-item');
		const pathname = window.location.pathname;
		const search = window.location.search;
		const path = search ? pathname + search : pathname;
		$('.navbar-nav a').each(function () {
			const temp_path = $(this).attr('href');
			if (temp_path == path || temp_path == window.location.href) {
				$(this).parent('li.menu-item').addClass('current-menu-item');
				$(this).parent('li.menu-item').parent('ul.sub-menu').parent('li.menu-item').addClass('current-menu-item');
			}
		});
	}

	/** 反机器人评论机制 */
	(() => {
		if (!window.Joe.commentsAntiSpam) return;
		const r = document.getElementById(window.Joe.respondId);
		if (!r) return;
		const input = document.createElement('input');
		input.type = 'hidden';
		input.name = '_';
		input.value = window.Joe.commentsAntiSpam;
		const forms = r.getElementsByTagName('form');
		if (forms.length > 0) forms[0].appendChild(input);
	})();

	/* 座右铭 */
	{
		const mottoArray = ['风急天高猿啸哀，渚清沙白鸟飞回', '无边落木萧萧下，不尽长江滚滚来', '万里悲秋常作客，百年多病独登台', '艰难苦恨繁霜鬓，潦倒新停浊酒杯', '君不见黄河之水天上来，奔流到海不复回', '君不见高堂明镜悲白发，朝如青丝暮成雪', '人生得意须尽欢，莫使金樽空对月', '天生我材必有用，千金散尽还复来', '问君能有几多愁，恰似一江春水向东流', '不知天上宫阙，今夕是何年', '我欲乘风归去，又恐琼楼玉宇', '高处不胜寒，起舞弄清影，何似在人间', '醉里挑灯看剑，梦回吹角连营', '八百里分麾下炙，五十弦翻塞外声，沙场秋点兵', '在天愿为比翼鸟，在地愿为连理枝', '莫愁前路无知己，天下谁人不识君', '枯藤老树昏鸦，小桥流水人家', '夕阳西下，断肠人在天涯', '安能摧眉折腰事权贵，使我不得开心颜？', '国破山河在，城春草木深', '感时花溅泪，恨别鸟惊心', '羌管悠悠霜满地，人不寐，将军白发征夫泪', '五花马，千金裘，呼儿将出换美酒', '山无陵，江水为竭，冬雷震震，夏雨雪，天地合，乃敢与君绝', '劝君更尽一杯酒,西出阳关无故人', '人生自是有情痴，此恨不关风与月', '千山鸟飞绝，万径人踪灭', '孤舟蓑笠翁，独钓寒江雪', '十年生死两茫茫，不思量，自难忘。千里孤坟，无处话凄凉', '纵使相逢应不识，尘满面，鬓如霜', '少年不识愁滋味，爱上层楼。爱上层楼，为赋新词强说愁', '时光只解催人老，不信多情，长恨离亭，泪滴春衫酒易醒', '梧桐昨夜西风急，淡月胧明，好梦频惊，何处高楼雁一声？', '惶恐滩头说惶恐，零丁洋里叹零丁', '人生自古谁无死？留取丹心照汗青', '前不见古人，后不见来者', '念天地之悠悠，独怆然而涕下', '我醉欲眠卿且去，明朝有意抱琴来', '人生自古谁无死，留取丹心照汗青', '黑云压城城欲摧，甲光向日金鳞开'];
		let motto = mottoArray[Math.floor(Math.random() * mottoArray.length)];
		$(".joe_motto").html(motto);
		if (Joe.options.MOTTO) {
			if (Joe.options.MOTTO.startsWith("https://") || Joe.options.MOTTO.startsWith("http://") || Joe.options.MOTTO.startsWith("//")) {
				$.get(Joe.options.MOTTO, text => $(".joe_motto").html(text), "text");
			} else {
				$(".joe_motto").html(Joe.options.MOTTO);
			}
		}
	}

	{
		$('a[href]').each(function () {
			if (!Joe.internalUrl(this)) $(this).attr('target', '_blank').attr('rel', 'external nofollow');
		});
	}

	{
		// tooltip.js
		window.Joe.tooltip();
		// popover.js
		$("[data-toggle='popover']:not([data-original-title])").popover({ html: true });
	}

	{
		const modeElement = $(".joe_action_item.mode");
		const isDark = Joe.themeManager.currentTheme === 'dark';

		// 切换图标状态
		modeElement.find(".icon-1").toggleClass("active", isDark);
		modeElement.find(".icon-2").toggleClass("active", !isDark);

		// 更新提示文字
		if (!Joe.IS_MOBILE) {
			const title = isDark ? '日间模式' : '夜间模式';
			modeElement.attr('title', title).tooltip({ container: "body", trigger: 'hover' });
		}
	}
}
document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.main, { once: true });