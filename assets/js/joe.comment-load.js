Joe.DOMContentLoaded.initComment ||= (options = {}) => {
	console.log('调用：Joe.DOMContentLoaded.initComment', options);


}

document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.initComment, { once: true });