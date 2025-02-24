/**
 * 支付JS
 */

(function ($) {
	// var _win = window._win;
	var _body = $('body');
	var _modal = false;
	var is_verify = false;
	var order_result = {};
	var pay_inputs = {};
	var pay_ajax_url = window.Joe.BASE_API;
	var modal_id = 'zibpay_modal';

	init();

	function init() {
		var _modal_html =
			'<div class="modal fade flex jc" style="display:none;" id="' +
			modal_id +
			'" tabindex="-1" role="dialog" aria-hidden="false">\
		<div class="modal-dialog" role="document">\
			<div class="pay-payment alipay">\
				<div class="modal-body modal-pay-body">\
					<div class="row-5 hide-sm">\
						<img style="max-width: 100%;max-height: 100%;" class="lazyload pay-sys t-alipay" alt="alipay" src="" data-src="' +
			window.Joe.THEME_URL +
			'assets/images/pay/alipay-sys.png">\
						<img style="max-width: 100%;max-height: 100%;" class="lazyload pay-sys t-wxpay" alt="wxpay" src="" data-src="' +
			window.Joe.THEME_URL +
			'assets/images/pay/wechat-sys.png">\
						<img style="max-width: 100%;max-height: 100%;" class="lazyload pay-sys t-qqpay" alt="qqpay" src="" data-src="' +
			window.Joe.THEME_URL +
			'assets/images/pay/qqpay-sys.png">\
					</div>\
					<div class="row-5">\
					<div class="pay-qrcon">\
						<div class="qrcon">\
							<div class="pay-logo-header mb10">\
								<span class="pay-logo"></span><span class="pay-logo-name t-alipay">支付宝</span><span class="pay-logo-name t-wxpay">微信支付</span><span class="pay-logo-name t-qqpay">QQ支付</span>\
							</div>\
							<div class="pay-title em09 muted-2-color padding-h6"></div>\
							<div><span class="em09">￥</span><span class="pay-price em14"></span></div>\
							<div class="pay-qrcode">\
								<img src="" alt="pay-qrcode" style="max-width: 100%;max-height: 100%;">\
							</div>\
						</div>\
					<div class="pay-switch"></div>\
					<div class="pay-notice"><div class="notice load">正在生成订单，请稍候</div></div>\
					</div>\
				</div>\
				</div>\
			</div>\
		</div>\
	</div>';

		$('link#zibpay_css').length || $('head').append('<link type="text/css" id="zibpay_css" rel="stylesheet" href="' + window.Joe.THEME_URL + 'assets/css/joe.pay.css?version=' + window.Joe.VERSION + '">');
		$('#' + modal_id).length || _body.append(_modal_html);

		$(document).ready(weixin_auto_send);
		_body.on('click', '.initiate-pay', initiate_pay);
		_body.on('click', '.pay-vip', vip_pay);
		_body.on('click', '.coupon-submit', coupon_submit);

		//模态框关闭停止查询登录
		_body.on('hide.bs.modal', '#' + modal_id, function () {
			order_result.trade_no = false;
			is_verify = false;
		});

		_modal = $('#' + modal_id);
	}

	//优惠码恢复初始化
	function coupon_recovery(form) {
		var _actual_price_number = form.find('.actual-price-number'); //实际付款金额的元素
		if (_actual_price_number.length) {
			var _actual_price = parseFloat(_actual_price_number.data('price'));
			_actual_price_number.text(_actual_price);
		}

		form.find('.coupon-data-box').html('');
		form.find('input[name="coupon"]').val('');
	}

	function coupon_submit() {
		var $this = $(this);
		var $coupon = $this.parents('.coupon-input-box').find('input[name="coupon"]');
		var form = $this.parents('form');
		var ajax_data = form.serializeObject();
		var $coupon_data_box = $this.parents('.coupon-input-box').find('.coupon-data-box');
		ajax_data.action = 'coupon_submit';
		if (!ajax_data.coupon) {
			return autolog.warn('请输入优惠码'), !1;
		}

		var _actual_price_number = form.find('.actual-price-number'); //实际付款金额的元素
		var _actual_price = 0; //实际付款金额的元素
		if (_actual_price_number.length) {
			_actual_price = parseFloat(_actual_price_number.data('price'));
			_actual_price_number.text(_actual_price);
		}

		$coupon_data_box.html('');
		zib_ajax(
			$this,
			ajax_data,
			function (n) {
				if (n.error) {
					$coupon.val('');
				}

				var discount_html = '';

				if (n.discount.type == 'subtract') {
					discount_html = '<span class="muted-2-color">优惠立减</span><span class="font-bold c-red em14">-' + n.discount.val + '</span>';
					if (_actual_price) {
						_actual_price = (_actual_price - n.discount.val).toFixed(2);
					}
				} else if (n.discount.type == 'multiply') {
					if (_actual_price) {
						discount_html = '<span class="c-red">' + n.discount.val * 10 + '折优惠</span><span class="font-bold c-red em14">-' + parseFloat((_actual_price * (1 - n.discount.val)).toFixed(2)) + '</span>';
						_actual_price = (_actual_price * n.discount.val).toFixed(2);
					} else {
						discount_html = '<span class="muted-2-color">优惠</span><span class="font-bold c-red em14">' + n.discount.val * 10 + '折</span>';
					}
				}

				discount_html = discount_html ? '<div class="discount-box flex jsb ab">' + discount_html + '</div>' : '';
				if (n.expire_time) {
					discount_html += '<div class="coupon-time flex jsb ab"><span class="muted-2-color">有效期至</span><span class="muted-color">' + n.expire_time + '</span></div>';
				}

				var box_html = '<div class="mt10 coupon-box mb10 muted-box padding-h6 line-16"><div><span class="badg badg-sm mr6 jb-blue">优惠码可用</span>' + (n.title ? '<span>' + n.title + '</span>' : '') + '</div>' + discount_html + '</div>';

				$coupon_data_box.html(box_html);

				if (_actual_price_number.length) {
					_actual_price = _actual_price < 0 ? 0 : parseFloat(_actual_price);
					_actual_price_number.text(_actual_price);
				}
			},
			'stop'
		);
		return !1;
	}

	function ajax_send(data, _this) {
		// data.openid && Qmsg.info('正在发起支付，请稍后...', 'load', '', 'pay_ajax'); //微信JSAPI支付
		data.openid && autolog.info('正在发起支付，请稍后...'); //微信JSAPI支付

		zib_ajax(
			_this,
			data,
			function (n) {
				console.log(n)
				//错误处理
				if (n.error_code && n.error_code == 'coupon_error') {
					coupon_recovery(_this.parents('form'));
				}

				//1.遇到错误
				if (n.error) {
					return;
				}

				//2.打开链接
				if (n.url && n.open_url) {
					window.location.href = n.url;
					window.location.reload;
					autolog.info('正在跳转到支付页面');
					return;
				}

				//2.加载易支付的POST提价
				if (n.form_html) {
					_body.append(n.form_html);
					autolog.info('正在跳转到支付页面');
					return;
				}

				//3.微信JSAPI支付
				if (n.jsapiParams) {
					var jsapiParams = n.jsapiParams;
					var jsapi_return = n.jsapi_return || 0;
					if (typeof WeixinJSBridge == 'undefined') {
						//安卓手机需要挂载
						if (document.addEventListener) {
							document.addEventListener('WeixinJSBridgeReady', weixin_bridge_ready(jsapiParams, jsapi_return), false);
						} else if (document.attachEvent) {
							document.attachEvent('WeixinJSBridgeReady', weixin_bridge_ready(jsapiParams, jsapi_return));
							document.attachEvent('onWeixinJSBridgeReady', weixin_bridge_ready(jsapiParams, jsapi_return));
						}
					} else {
						weixin_bridge_ready(jsapiParams, jsapi_return);
					}
					// Qmsg.info('请完成支付', '', '', data.openid ? 'pay_ajax' : '');
					autolog.info('请完成支付');
					return;
				}

				//4.扫码支付
				if (n.url_qrcode) {
					_modal.find('.more-html').remove(); //隐藏更多内容
					$('.modal:not(#' + modal_id + ')').modal('hide'); //隐藏其他模态框
					_modal.find('.pay-qrcode img').attr('src', n.url_qrcode); //加载二维码
					qrcode_notice('请扫码支付，支付成功后会自动跳转', '');
					n.more_html && _modal.find('.pay-notice').before('<div class="more-html">' + n.more_html + '</div>');
					n.order_name && _modal.find('.pay-title').html(n.order_name);
					n.order_price && _modal.find('.pay-price').html(n.order_price);
					n.payment_method && _modal.find('.pay-payment').removeClass('wxpay alipay qqpay').addClass(n.payment_method);

					_modal.modal('show');

					//开始ajax检测是否付费成功
					order_result = n;
					if (!is_verify) {
						verify_pay();
						is_verify = true;
					}
				}
			},
			'stop'
		);
	}

	//扫码支付检测是否支付成功
	function verify_pay() {
		if (order_result.trade_no) {
			$.ajax({
				type: 'POST',
				url: pay_ajax_url + '/check-pay',
				data: {
					trade_no: order_result.trade_no,
					check_sdk: order_result.check_sdk,
				},
				dataType: 'json',
				success: function (n) {
					if (n.status == '1') {
						qrcode_notice('支付成功，页面跳转中', 'success');
						setTimeout(function () {
							if ('undefined' != typeof pay_inputs.return_url && pay_inputs.return_url) {
								window.location.href = delQueStr('openid', delQueStr('zippay', pay_inputs.return_url));
								window.location.reload;
							} else {
								location.href = delQueStr('openid', delQueStr('zippay'));
								location.reload;
							}
						}, 2000);
					} else if (n.status == 2) {
						qrcode_notice(n.msg, 'warning');
						setTimeout(() => {
							_modal.modal('hide');
						}, 1000);
					} else {
						setTimeout(function () {
							verify_pay();
						}, 2000);
					}
				},
			});
		}
	}

	function initiate_pay() {
		var _this = $(this);
		_this.text('正在支付中...');
		_this.css('pointer-events', 'none');
		_this.css('opacity', '0.7');
		var form = _this.parents('form');
		pay_inputs = form.serializeObject();
		pay_inputs.routeType = 'initiate_pay';
		pay_inputs.return_url = pay_inputs.return_url ? pay_inputs.return_url : window.location.href;
		ajax_send(pay_inputs, _this);
		return false;
	}

	//扫码支付通知显示
	function qrcode_notice(msg, type) {
		var notice_box = _modal.find('.pay-notice .notice');
		msg = type == 'load' ? '<i class="loading mr6"></i>' + msg : msg;
		notice_box.removeClass('load warning success danger').addClass(type).html(msg);
	}

	//微信JSAPI支付
	function weixin_bridge_ready(jsapiParams, jsapi_return) {
		WeixinJSBridge.invoke('getBrandWCPayRequest', jsapiParams, function (res) {
			var this_url = delQueStr('openid', delQueStr('zippay'));

			if (res.err_msg == 'get_brand_wcpay_request:ok') {
				// 使用以上方式判断前端返回,微信团队郑重提示：
				//res.err_msg将在用户支付成功后返回ok，但并不保证它绝对可靠。
				//支付成功跳转到通知地址
				if (jsapi_return) {
					location.href = jsapi_return;
					location.reload; //刷新页面
					return;
				}
			}

			location.href = this_url;
			location.reload; //刷新页面
		});
	}

	//微信JSAPI支付收到回调之后，再次自动提交
	function weixin_auto_send() {
		var zippay = GetRequest('zippay');
		var openid = GetRequest('openid');

		if (zippay && openid && is_weixin_app()) {
			pay_inputs.pay_type = 'wechat';
			pay_inputs.openid = openid;
			pay_inputs.action = 'initiate_pay';

			ajax_send(pay_inputs, $('<div></div>'));
		}
	}

	//判断是否在微信浏览器内
	function is_weixin_app() {
		var ua = window.navigator.userAgent.toLowerCase();
		return ua.match(/MicroMessenger/i) == 'micromessenger';
	}

	function vip_pay() {
		var _this = $(this);

		var _modal =
			'<div class="modal fade flex jc" id="modal_pay_uservip" tabindex="-1" role="dialog" aria-hidden="false">\
	<div class="modal-dialog" role="document">\
	<div class="modal-content">\
	<div class="modal-body"><h4 style="padding:20px;" class="text-center"><i class="loading zts em2x"></i></h4></div>\
	</div>\
	</div>\
	</div>\
	</div>';
		$('#modal_pay_uservip').length || _body.append(_modal);
		var modal = $('#modal_pay_uservip');
		var vip_level = _this.attr('vip-level') || 1;
		if (modal.find('.payvip-modal').length) {
			$('a[href="#tab-payvip-' + vip_level + '"]').tab('show');
			modal.modal('show');
		} else {
			// Qmsg.info('加载中，请稍等...', 'load', '', 'payvip_ajax');
			autolog.load('加载中，请稍等...');
			$.ajax({
				type: 'POST',
				url: pay_ajax_url,
				data: {
					action: 'pay_vip',
					vip_level: vip_level,
				},
				dataType: 'json',
				success: function (n) {
					var msg = n.msg || '请选择会员选项';
					if (msg.indexOf('登录') != -1) {
						modal.remove();
						$('.signin-loader:first').click();
					}
					// Qmsg.info(msg, n.ys ? n.ys : n.error ? 'danger' : '', 3, 'payvip_ajax');
					autolog.info(msg);
					if (!n.error) {
						modal.find('.modal-content').html(n.html);
						if (!modal.find('.modal-content .tab-pane.active').length) {
							modal.find('.modal-content a[data-toggle="tab"]:eq(0)').click();
						}
						modal.trigger('loaded.bs.modal').modal('show');
						auto_fun();
					}
				},
			});
		}
		return !1;
	}

	//卡密充值的内容切换
	var card_pass_controller = '[data-controller="payment_method"][data-value="card_pass"]';
	_body.on('controller.change', card_pass_controller, function (e, a) {
		var _this = $(this);
		var form = _this.parents('form');
		if (a) {
			form.find('.charge-box').hide();
		} else {
			form.find('.charge-box').show();
		}
	});

	_body.on('loaded.bs.modal', '#refresh_modal', function () {
		if ($(this).find(card_pass_controller).length) {
			$(this).find('input[name="payment_method"]').trigger('change');
		}
	});
})(jQuery);

function GetRequest(name) {
	var url = window.parent.location.search || ''; //获取url中"?"符后的字串
	// var theRequest = new Object();
	if (url.indexOf('?') != -1) {
		var str = url.substr(1);
		if (str.indexOf('#' != -1)) {
			str = str.substr(0);
		}
		strs = str.split('&');
		for (var i = 0; i < strs.length; i++) {
			if (strs[i].indexOf(name) != -1) {
				return strs[i].split('=')[1];
			}
		}
	}
	return null;
}

//从链接中删除参数
function delQueStr(ref, url) {
	var str = '';
	url = url || window.location.href;
	if (url.indexOf('?') != -1) {
		str = url.substr(url.indexOf('?') + 1);
	} else {
		return url;
	}
	var arr = '';
	var returnurl = '';
	if (str.indexOf('&') != -1) {
		arr = str.split('&');
		for (var i in arr) {
			if (arr[i].split('=')[0] != ref) {
				returnurl = returnurl + arr[i].split('=')[0] + '=' + arr[i].split('=')[1] + '&';
			}
		}
		return url.substr(0, url.indexOf('?')) + '?' + returnurl.substr(0, returnurl.length - 1);
	} else {
		arr = str.split('=');
		if (arr[0] == ref) {
			return url.substr(0, url.indexOf('?'));
		} else {
			return url;
		}
	}
}
