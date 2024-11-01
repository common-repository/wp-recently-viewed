if(viewHistory) {
	var page = {
		"title": document.getElementsByTagName('title')[0].innerHTML,
		"url": location.href
	};
	viewHistory.addHistory(page);
}