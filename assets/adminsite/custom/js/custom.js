$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
	e.target; // newly activated tab
	e.relatedTarget; // previous active tab
	if ('action' in e.currentTarget.dataset) {
		if (e.currentTarget.dataset.action == "scrollTop") {
			window.scrollTo(0, 0);
		}
	}
});
