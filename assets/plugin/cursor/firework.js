
jQuery.ajaxSetup({ cache: true });
$.getScript(Joe.CDN_URL + 'EaselJS/1.0.2/easeljs.min.js', function () {
	var Fireworks, GRAVITY, K, SPEED, ToRadian, canvas, context, ctx, fireBoss, repeat, stage;
	canvas = document.createElement('canvas');
	document.body.appendChild(canvas);
	context = canvas.getContext("2d");
	canvas.width = window.innerWidth;
	canvas.height = window.innerHeight;
	stage = new createjs.Stage(canvas);
	stage.autoClear = false;
	ctx = canvas.getContext("2d");
	ctx.fillStyle = "rgba(0, 0, 0, 0)";
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	createjs.Ticker.setFPS(120);
	createjs.Touch.enable(stage);
	stage.update();

	// 重力
	GRAVITY = 1;

	// 抵抗
	K = 0.9;

	// 速度
	SPEED = 12;

	// 从度数转换为弧度
	ToRadian = function (degree) {
		return degree * Math.PI / 180.0;
	};

	// 制作烟花的class
	Fireworks = class Fireworks {
		constructor(sx = 100, sy = 100, particles = 70) {
			var circle, i, j, rad, ref, speed;
			this.sx = sx;
			this.sy = sy;
			this.particles = particles;
			this.sky = new createjs.Container();
			this.r = 0;
			this.h = Math.random() * 360 | 0;
			this.s = 100;
			this.l = 50;
			this.size = 3;
			for (i = j = 0, ref = this.particles; (0 <= ref ? j < ref : j > ref); i = 0 <= ref ? ++j : --j) {
				speed = Math.random() * 12 + 2;
				circle = new createjs.Shape();
				circle.graphics.f(`hsla(${this.h}, ${this.s}%, ${this.l}%, 1)`).dc(0, 0, this.size);
				circle.snapToPixel = true;
				circle.compositeOperation = "lighter";
				rad = ToRadian(Math.random() * 360 | 0);
				circle.set({
					x: this.sx,
					y: this.sy,
					vx: Math.cos(rad) * speed,
					vy: Math.sin(rad) * speed,
					rad: rad
				});
				this.sky.addChild(circle);
			}
			stage.addChild(this.sky);
		}

		explode() {
			var circle, j, p, ref;
			if (this.sky) {
				++this.h;
				for (p = j = 0, ref = this.sky.getNumChildren(); (0 <= ref ? j < ref : j > ref); p = 0 <= ref ? ++j : --j) {
					circle = this.sky.getChildAt(p);
					// 加速度
					circle.vx = circle.vx * K;
					circle.vy = circle.vy * K;
					// 位置计算
					circle.x += circle.vx;
					circle.y += circle.vy + GRAVITY;

					this.l = Math.random() * 100;
					// 粒度
					this.size = this.size - 0.001;
					if (this.size > 0) {
						circle.graphics.c().f(`hsla(${this.h}, 100%, ${this.l}%, 1)`).dc(0, 0, this.size);
					}
				}
				if (this.sky.alpha > 0.1) {
					this.sky.alpha -= K / 50;
				} else {
					stage.removeChild(this.sky);
					this.sky = null;
				}
			} else {
			}
		}
	};

	fireBoss = [];

	setInterval(function () {
		ctx.fillStyle = "rgba(0, 0, 0, 0.1)";
		ctx.fillRect(0, 0, canvas.width, canvas.height);
	}, 40);

	// setInterval(function () {
	// 	var x, y;
	// 	x = Math.random() * canvas.width | 0;
	// 	y = Math.random() * canvas.height | 0;
	// 	fireBoss.push(new Fireworks(x, y));
	// 	return fireBoss.push(new Fireworks(x, y));
	// }, 1300);

	repeat = function () {
		var fireworks, j, ref;
		for (fireworks = j = 0, ref = fireBoss.length; (0 <= ref ? j < ref : j > ref); fireworks = 0 <= ref ? ++j : --j) {
			if (fireBoss[fireworks].sky) {
				fireBoss[fireworks].explode();
			}
		}
		stage.update();
	};

	createjs.Ticker.on("tick", repeat);

	stage.addEventListener("stagemousedown", function () {
		fireBoss.push(new Fireworks(stage.mouseX, stage.mouseY));
		return fireBoss.push(new Fireworks(stage.mouseX, stage.mouseY));
	});
});