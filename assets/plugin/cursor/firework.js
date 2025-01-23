document.addEventListener('DOMContentLoaded', () => {
	let body = document.getElementsByTagName('body')[0];
	let myCanvas = document.createElement('canvas');
	myCanvas.id = 'canvas';
	myCanvas.style.position = 'fixed';
	myCanvas.style.left = '0';
	myCanvas.style.top = '0';
	body.before(myCanvas);//before插入可以将canvas插入到body前面
	body.style.opacity = 0.9;
	// body.appendChild(myCanvas);

	//requestAnimFrame 封装，可以兼容所有浏览器
	window.requestAnimFrame = (function () {
		return window.requestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			function (callback) {
				window.setTimeout(callback, 1000 / 60)
			}
	})();
	var canvas = document.getElementById("canvas"),
		// Canvas.getContext(contextID)返回一个用于在画布上绘图的环境；
		// 目前合法参数contextID只有"2d"，即二维绘图
		ctx = canvas.getContext("2d"),
		// 获取电脑可视区域宽高
		cw = window.innerWidth, ch = window.innerHeight,
		fireworks = [],//存放烟花数组
		particles = [],//存放绽放粒子数组
		hue = 180,//设置颜色范围
		limiterTotal = 5,//点击绽放速度
		limiterTick = 0,//点击计时器
		timerTotal = 40,//自动绽放速度
		timerTick = 0,//自动计时器
		mousedown = false,
		mx, my;
	// 设置画布宽高
	canvas.width = cw; canvas.height = ch;
	// 随机函数
	function random(min, max) {
		return Math.random() * (max - min) + min
	}
	// 计算两点距离
	function calculateDistance(p1x, p1y, p2x, p2y) {
		var xDistance = p1x - p2x, yDistance = p1y - p2y; return Math.sqrt(Math.pow(xDistance, 2) + Math.pow(yDistance, 2))
	}
	// 定义烟花对象
	function Firework(sx, sy, tx, ty) {
		this.x = sx;
		this.y = sy;
		this.sx = sx;
		this.sy = sy;
		this.tx = tx;
		this.ty = ty;
		this.distanceToTarget = calculateDistance(sx, sy, tx, ty);
		//运动距离
		this.distanceTraveled = 0;
		//生成的运动轨迹
		this.coordinates = [];
		this.coordinateCount = 3;
		while (this.coordinateCount--) {
			this.coordinates.push([this.x, this.y])
		}
		this.angle = Math.atan2(ty - sy, tx - sx);
		this.speed = 2;
		this.acceleration = 1.05;
		this.brightness = random(50, 70);//烟花的亮度
		this.targetRadius = 1//烟花圈的半径
	}
	//更新烟花位置
	Firework.prototype.update = function (index) {
		this.coordinates.pop();
		this.coordinates.unshift([this.x, this.y]);
		if (this.targetRadius < 8) {
			this.targetRadius += 0.3
		} else {
			this.targetRadius = 1
		}
		this.speed *= this.acceleration;
		var vx = Math.cos(this.angle) * this.speed,
			vy = Math.sin(this.angle) * this.speed;
		this.distanceTraveled = calculateDistance(this.sx, this.sy, this.x + vx, this.y + vy);
		//如果烟花运行距离大于或等于初始位置到目标位置之间的距离，生成新烟花并移除当前烟花，否则更新烟花位置
		if (this.distanceTraveled >= this.distanceToTarget) {
			//烟花绽放
			createParticles(this.tx, this.ty);
			//销毁烟花点
			fireworks.splice(index, 1)
		} else {
			this.x += vx; this.y += vy
		}
	};
	// 烟花射线发射
	Firework.prototype.draw = function () {
		// 重新开始新路径，把之前的路径都清空掉
		ctx.beginPath();
		//先保存一个坐标
		ctx.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[this.coordinates.length - 1][1]);
		//从moveTo提供的坐标绘制到指定坐标
		ctx.lineTo(this.x, this.y);
		//设置线条样式
		ctx.strokeStyle = "hsl(" + hue + ", 100%, " + this.brightness + "%)";
		//通过此函数将以上绘制的图形绘制到画布上
		ctx.stroke();
		ctx.beginPath();
		//画出鼠标点击时的圆圈
		//arc(圆心的x坐标，圆心的y坐标，圆的半径，起始角（以弧度计，即l圆心的3点钟位置是0度），结束角，规定应该是顺时针还是逆时针画图(可选))
		ctx.arc(this.tx, this.ty, this.targetRadius, 0, Math.PI * 2);
		//描边绘制
		ctx.stroke()
	};
	//烟花绽放方法
	function Particle(x, y) {
		this.x = x; this.y = y; this.coordinates = [];
		this.coordinateCount = 5;
		while (this.coordinateCount--) {
			this.coordinates.push([this.x, this.y])
		}
		// 往各个角度绽放
		this.angle = random(0, Math.PI * 2);
		this.speed = random(1, 10);
		this.friction = 0.95;
		this.gravity = 1;
		this.hue = random(hue - 20, hue + 20);
		this.brightness = random(50, 80);
		this.alpha = 1;
		this.decay = random(0.015, 0.03);//粒子消失时间（透明度增加速度）
	}
	Particle.prototype.update = function (index) {
		this.coordinates.pop();
		this.coordinates.unshift([this.x, this.y]);
		//粒子运动
		this.speed *= this.friction;
		this.x += Math.cos(this.angle) * this.speed;
		this.y += Math.sin(this.angle) * this.speed + this.gravity;
		this.alpha -= this.decay;//透明度增加
		if (this.alpha <= this.decay) {
			particles.splice(index, 1)//销毁烟花绽放粒子
		}
	};
	// 烟花绽放    
	Particle.prototype.draw = function () {
		ctx.beginPath();
		ctx.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[this.coordinates.length - 1][1]);
		ctx.lineTo(this.x, this.y);
		//烟花的颜色
		ctx.strokeStyle = "hsla(" + this.hue + ", 100%, " + this.brightness + "%, " + this.alpha + ")";
		ctx.stroke()
	};
	// 创建粒子
	function createParticles(x, y) {
		//烟花绽放粒子数量
		var particleCount = 200;
		while (particleCount--) {
			particles.push(new Particle(x, y))
		}
	}
	function loop() {
		// 让浏览器循环调用指定的函数来更新动画。
		requestAnimFrame(loop);
		hue += 0.5;
		ctx.globalCompositeOperation = "destination-out";
		ctx.fillStyle = "rgba(0, 0, 0, 0.5)";
		ctx.fillRect(0, 0, cw, ch);
		ctx.globalCompositeOperation = "lighter";
		var i = fireworks.length;
		while (i--) {
			fireworks[i].draw();
			fireworks[i].update(i)
		}
		var i = particles.length;
		while (i--) {
			particles[i].draw();
			particles[i].update(i)
		}
		// 随机自动选择位置
		if (timerTick >= timerTotal) {
			//鼠标没有点击的情况
			if (!mousedown) {
				// fireworks.push(new Firework(cw/2,ch,random(0,cw),random(0,ch/2)));
				timerTick = 0
			}
		} else {
			timerTick++
		}
		// 鼠标点击位置
		if (limiterTick >= limiterTotal) {
			if (mousedown) {
				fireworks.push(new Firework(cw / 2, ch, mx, my));
				limiterTick = 0
			}
		} else {
			limiterTick++
		}
	}
	body.addEventListener("mousemove", function (e) {
		mx = e.pageX - body.offsetLeft - window.scrollX;//鼠标点击坐标 - body偏移量 - 窗口滚动距离
		my = e.pageY - body.offsetTop - window.scrollY;//鼠标点击坐标 - body偏移量 - 窗口滚动距离
	});
	body.addEventListener("mousedown", function (e) {
		// e.preventDefault();
		mousedown = true
	});
	body.addEventListener("mouseup", function (e) {
		// e.preventDefault();
		mousedown = false
	});
	loop();
});