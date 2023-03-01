var base_url = $("base").attr("href"),
	page_loading = $(".dimmer"),
	this_is_item_list_product = $("#item-list-product"),
	this_is_pagination_product = $("#pagination-product"),
	classname = [],
	_uri_init = new URI(),
	_uri_segment_1 = _uri_init.segment(0),
	_uri_page = _uri_init.segment(1),
	icheck_cat = $(".categories-check");
window.stateChangeIsLocal = !1;
var initialURL = "",
	popped = "state" in window.history && null !== window.history.state,
	size_select2 = ((initialURL = location.href), $(".size-select2")),
	gender_select2 = $(".gender-select2"),
	store_select2 = $(".store-select2"),
	start_date_val = '',
	end_date_val = '',
	dateToday = new Date();
	//dateMinWeek = dateToday.setDate(dateToday.getDate() - 7);

	let data_filter = null;

function history_push(e, t, a) {
	History.pushState(e, t, a);
}

function _this_loader_show(e) {
	$(e).find(".segment").dimmer("show"),
		setTimeout(function () {
			var t = $(e).find(".segment").find(".dimmer"),
				a = $(e).find(".segment").find(".loader");
			$(e).find(".segment").removeClass("dimmed"),
				$(t).hasClass("disabled") &&
				($(t).removeClass("disabled"), $(t).removeClass("dimmer")),
				$(a).hasClass("disabled") && $(a).removeClass("disabled"),
				$(t).addClass("active"),
				$(a).addClass("active");
		});
}

function _this_loader_hide() {
	$(".segment").dimmer("hide");
}

function _this_loader_disabled(e) {
	$(e).find(".segment").dimmer("hide");
	var t = $(e).find(".segment").find(".dimmer"),
		a = $(e).find(".segment").find(".loader");
	$(e).find(".segment").addClass("dimmed"),
		$(t).hasClass("active") &&
		($(t).removeClass("active"), $(t).addClass("dimmer")),
		$(a).hasClass("active") && $(a).removeClass("active"),
		$(t).addClass("disabled"),
		$(a).addClass("disabled");
}

function getUrlVars() {
	for (
		var e,
			t = {},
			a = window.location.href
			.slice(window.location.href.indexOf("?") + 1)
			.split("&"),
			i = 0; i < a.length; i++
	) {
		var r = (e = a[i].split("="))[0],
			o = e[1];
		null !== o && (t[r] = o);
	}
	return t;
}

function getUrlVariable(e) {
	for (var t, a = {}, i = e.split("&"), r = 0; r < i.length; r++) {
		var o = (t = i[r].split("="))[0],
			s = t[1];
		null !== s && (a[o] = s);
	}
	return a;
}

function getUrlParameter(e) {
	var t,
		a,
		i = window.location.search.substring(1).split("&");
	for (a = 0; a < i.length; a++)
		if ((t = i[a].split("="))[0] === e)
			return void 0 === t[1] || decodeURIComponent(t[1]);
}

function removeArrayParam(e, t, a) {
	var i,
		r = a.split("?")[0],
		o = [],
		s = -1 !== a.indexOf("?") ? a.split("?")[1] : "";
	if ("" !== s) {
		for (var n = (o = s.split("&")).length - 1; n >= 0; n -= 1)
			(i = o[n].split("[]=")[0]),
			(paramValue = o[n].split("[]=")[1]),
			i === e && paramValue === t && o.splice(n, 1);
		o.length && (r = r + "?" + o.join("&"));
	}
	return r;
}

function current_url() {
	var e = window.location.href,
		t = e.indexOf("?"),
		a = e.substring(t),
		i = e.replace(a, "");
	return (e = i);
}

function get_checkbox_classname() {
	var e = {},
		t = [];
	return (
		$('input[type="checkbox"]').each(function () {
			this.checked &&
				(void 0 === e[this.name] && (e[this.name] = []),
					e[this.name].push(this.value),
					t.push($(this).data("index")));
		}),
		t
	);
}

function search_select2(e, t, a) {
	$.fn.select2 &&
		($(e).select2({
				multiple: !0,
				placeholder: "Size"
			}),
			$(t).select2({
				multiple: !0,
				placeholder: "Gender"
			}),
			$(a).select2({
				multiple: !0,
				placeholder: "Store Location"
			}));
}

function clear_datepicker(e) {
	$(e).val("").datepicker("update");
}

function search_datepicker(e) {

	var dateMinW = new Date(Date.now() - 604800000);

	let options = {
		changeMonth: true,
		changeYear: !1,
		format: "d MM yyyy",
		autoclose: !0,
		duration: "fast",
		//startDate: dateToday,
		startDate: dateMinW,
		clearBtn: !0,
	};

	$(e)
		.datepicker(options)
		.focus(function () {
			$(".datepicker")
				.find(".datepicker-switch")
				.click(function (e) {
					e.preventDefault(), e.stopPropagation();
				});
		})
		.on("changeDate", function (e) {
			if (e.target.className == 'datepicker-start form-control') {
				start_date_val = $(e.target).val() || null;
				if ($(e.target).val()) {
					let add_date = new Date(e.date).getDate() + 1;
					options.startDate = new Date(e.date.getFullYear(), e.date.getMonth(), add_date);
					$('.datepicker-end').datepicker('setStartDate', options.startDate);
				}
			}
			
			if (e.target.className == 'datepicker-end form-control') {
				end_date_val = $(e.target).val() || null;
				if ($(e.target).val()) {
					let min_date = new Date(e.date).getDate() - 1;
					let endDate = new Date(e.date.getFullYear(), e.date.getMonth(), min_date);
					$('.datepicker-start').datepicker('setEndDate', endDate);
				}
			}

			if (
				"categories" == _uri_segment_1 ||
				"product" == _uri_segment_1 ||
				"product-category" == _uri_segment_1
			) {
				// var t = getUrlVars(window.location.href),
				// 	a = $(".datepicker-start").val(),
				// 	i = $(".datepicker-end").val(),
				// 	r = [];
				// if (
				// 	($('input[type="checkbox"]').each(function () {
				// 			this.checked && r.push($(this).data("index"));
				// 		}),
				// 		"" != a && "" != i)
				// )
					
					// "" != a && (void 0 === t.start && (t.start = ""), (t.start = a)),
					// "" != i && (void 0 === t.end && (t.end = ""), (t.end = i));
					// filterProductsByTag(t, {
					// 	url: t,
					// 	page: 1,
					// 	classname: r
					// });
					// filterProductsByTagNew();
				// if ("" == a && "" == i)
				// 	void 0 !== t.start && delete t.start,
				// 	void 0 !== t.end && delete t.end;
					// filterProductsByTag(t, {
					// 	url: t,
					// 	page: 1,
					// 	classname: r
					// });
					// filterProductsByTagNew();
			}
		});

}

function getPagination(e, t, a) {
	$(e).pagination({
		items: a.data_total_rows,
		itemsOnPage: a.data_limit,
		cssStyle: "light-theme",
		displayedPages: 3,
		hrefTextPrefix: t,
		hrefTextSuffix: a.data_url,
		currentPage: a.data_page_number,
		edges: 1,
		ellipsePageSet: !1,
		selectOnClick: !1,
		prevText: '<i class="fa fa-angle-left">',
		nextText: '<i class="fa fa-angle-right">',
	});
}

function searchProduct(e, t) {
	void 0 === t && (t = {});
	var a, i, r, o;
	return (
		(a = $("#search-list")),
		(i = $("#pagination-search-product")),
		(r = window.location.href),
		(o = current_url()),
		$.ajax({
			url: base_url + _uri_segment_1 + "/filteringsearch/" + t.page,
			dataType: "json",
			type: "POST",
			data: t,
			beforeSend: function (e) {
				loader_show(".loading-mask"),
					$(a).hide(),
					$(i).hide(),
					$(size_select2).prop("disabled", !0),
					$(gender_select2).prop("disabled", !0),
					$(store_select2).prop("disabled", !0),
					$("button[type=submit], input, .reset-filter").prop("disabled", !0),
					setTimeout(function () {
						var e = $(a).offset();
						$("html, body").scrollTop(e);
					});
			},
			success: function (e) {
				var t = e.data,
					s = (e.pagination, e.page),
					n = e.total_rows,
					d = e.limit,
					l = r,
					c = e.url;
				"" !== e &&
					((l = e.url),
						(l =
							"" !== o ?
							"" == l ?
							_uri_segment_1 + "/" + s :
							_uri_segment_1 + "/" + s + "?" + l :
							"" !== l ?
							_uri_segment_1 + "/" + s + "?" + l :
							_uri_segment_1 + "/" + s)),
					history_push({
						page: l,
						result: e
					}, "Search - Gading Kostum", l),
					$(a).empty(),
					setTimeout(function () {
						$.trim($(a).html()).length ||
							($(a).append(t),
								setTimeout(function () {
									var e = $(document).find(".size-select2"),
										t = $(document).find(".gender-select2"),
										a = $(document).find(".store-select2"),
										i = $(document).find(".datepicker-search-start"),
										r = $(document).find(".datepicker-search-end");
									search_select2(e, t, a),
										search_datepicker(i),
										search_datepicker(r),
										$(document).find(".product-items").matchHeight(),
										$("html, body").animate({
											scrollTop: $(".form-search-product").offset().top - 15,
										});
								})),
							$.trim($(i).html()).length ?
							0 == n ?
							$(i).pagination("destroy") :
							$(i).pagination("drawPage", s) :
							("" !== c && (c = "?" + c),
								getPagination(i, _uri_segment_1 + "/", {
									data_page_number: s,
									data_total_rows: n,
									data_limit: d,
									data_url: c,
								}));
					});
			},
			complete: function () {
				$(a).show(),
					$(i).show(),
					loader_hide(".loading-mask"),
					$(size_select2).prop("disabled", !1),
					$(gender_select2).prop("disabled", !1),
					$(store_select2).prop("disabled", !1),
					$("button[type=submit], input, .reset-filter").prop("disabled", !1);
			},
			fail: function () {
				$(a).show(),
					$(i).show(),
					loader_hide(".loading-mask"),
					$(size_select2).prop("disabled", !1),
					$(gender_select2).prop("disabled", !1),
					$(store_select2).prop("disabled", !1),
					$("button[type=submit], input, .reset-filter").prop("disabled", !1);
			},
			error: function (e, t, r) {
				alert(r),
					$(a).show(),
					$(i).show(),
					loader_hide(".loading-mask"),
					$(size_select2).prop("disabled", !1),
					$(gender_select2).prop("disabled", !1),
					$(store_select2).prop("disabled", !1),
					$("button[type=submit], input, .reset-filter").prop("disabled", !1);
			},
		}),
		!1
	);
}

function filterProductsByTagNew() {
	let t = data_filter;
	if (start_date_val && end_date_val) {
		t.url['start'] = start_date_val;
		t.url['end'] = end_date_val;
	}
	var a = "",
		i = "",
		r = "categories/";
	"categories" == _uri_segment_1 && (i = "Categories - Gading Kostum"),
		("categories" != _uri_segment_1 && "product-category" != _uri_segment_1) ||
		(a = "categories/filteringselect/"),
		"product-category" == _uri_segment_1 &&
		(i = "Product Category Archives - Gading Kostum"),
		"product" == _uri_segment_1 &&
		((i = "Product - Gading Kostum"),
			(a = "product/filteringselectproduct/"),
			(r = "product/")),
		void 0 === t && (t = {});
	var o = [],
		s = 0;
	$('input[type="checkbox"]').each(function () {
			var e = $(this).data("slug");
			$(this).is(":checked") && (o.push(e), (s += $(this).length));
		}),
		o.join("||");
	var n, d, l, c;
	return (
		(n = $("#item-list-product")),
		(d = $("#pagination-product")),
		(l = window.location.href),
		(c = current_url()),
		$.ajax({
			url: base_url + a + t.page,
			dataType: "json",
			type: "POST",
			data: t,
			beforeSend: function (e) {
				$('input[type="checkbox"]').prop("disabled", !0),
					$(".datepicker-start").prop("disabled", !0),
					$(".datepicker-end").prop("disabled", !0),
					$(".reset-filter").prop("disabled", !0),
					$('button.do-filter').attr('disabled', !0),
					$(n).hide(),
					$(d).hide(),
					loader_show(".loader", "loading"),
					setTimeout(function () {
						var e = $(n).offset();
						$("html, body").scrollTop(e);
					});
			},
			success: function (e) {
				$('input[type="checkbox"]').prop("disabled", !1),
					$(".datepicker-start").prop("disabled", !1),
					$(".datepicker-end").prop("disabled", !1),
					$(".reset-filter").prop("disabled", !1);
				var t = e.data,
					a = (e.pagination, e.page),
					o = e.total_rows,
					p = e.limit,
					u = l,
					h = e.url;
				if (
					("" !== e &&
						((u = e.url),
							(u =
								"" !== c ?
								"" == u ?
								r + a :
								r + a + "?" + u :
								"" !== u ?
								r + a + "?" + u :
								r + e.page)),
						"product-category" == _uri_segment_1)
				) {
					var g = window.location,
						m = g.protocol + "//" + g.host + "/" + g.pathname.split("/")[1];
					s >= 2 ?
						(window.location.href = m + "/" + u) :
						($(n).empty(),
							$(d).empty(),
							setTimeout(function () {
								$.trim($(n).html()).length ||
									($(n).append(t),
										setTimeout(function () {
											$(document).find(".product-items").matchHeight(),
												$("html, body").animate({
													scrollTop: $(n).offset().top - 15,
												});
										}));
							}));
				} else
					history_push({
						page: u,
						result: e
					}, i, u),
					$(n).empty(),
					$(d).empty(),
					setTimeout(function () {
						$.trim($(n).html()).length ||
							($(n).append(t),
								setTimeout(function () {
									$(document).find(".product-items").matchHeight(),
										$("html, body").animate({
											scrollTop: $(n).offset().top - 15,
										});
								}));
							});
				$.trim($(d).html()).length ?
					0 == o ?
					$(d).pagination("destroy") :
					$(d).pagination("drawPage", a) :
					("" !== h && (h = "?" + h),
						getPagination(d, "search/", {
							data_page_number: a,
							data_total_rows: o,
							data_limit: p,
							data_url: h,
						}));
			},
			complete: function () {
				$('input[type="checkbox"]').prop("disabled", !1),
					$(".datepicker-start").prop("disabled", !1),
					$(".datepicker-end").prop("disabled", !1),
					$(".reset-filter").prop("disabled", !1),
					$('button.do-filter').prop('disabled', !1),
					$(n).show(),
					$(d).show(),
					loader_hide(".loader");
			},
			fail: function () {
				$('input[type="checkbox"]').prop("disabled", !1),
					$(".datepicker-start").prop("disabled", !1),
					$(".datepicker-end").prop("disabled", !1),
					$(".reset-filter").prop("disabled", !1),
					$('button.do-filter').attr('disabled', !1),
					$(n).show(),
					$(d).show(),
					loader_hide(".loader");
			},
			error: function (e, t, a) {
				alert(a),
					$(n).show(),
					$(d).show(),
					loader_hide(".loader"),
					$('button.do-filter').attr('disabled', !1),
					$('input[type="checkbox"]').prop("disabled", !1),
					$(".datepicker-start").prop("disabled", !1),
					$(".datepicker-end").prop("disabled", !1),
					$(".reset-filter").prop("disabled", !1);
			},
		}),
		!1
	);


}

function filterProductsByTag(e, t) {
	data_filter = t;
	return;
	var a = "",
		i = "",
		r = "categories/";
	"categories" == _uri_segment_1 && (i = "Categories - Gading Kostum"),
		("categories" != _uri_segment_1 && "product-category" != _uri_segment_1) ||
		(a = "categories/filteringselect/"),
		"product-category" == _uri_segment_1 &&
		(i = "Product Category Archives - Gading Kostum"),
		"product" == _uri_segment_1 &&
		((i = "Product - Gading Kostum"),
			(a = "product/filteringselectproduct/"),
			(r = "product/")),
		void 0 === t && (t = {});
	var o = [],
		s = 0;
	$('input[type="checkbox"]').each(function () {
			var e = $(this).data("slug");
			$(this).is(":checked") && (o.push(e), (s += $(this).length));
		}),
		o.join("||");
	var n, d, l, c;
	return (
		(n = $("#item-list-product")),
		(d = $("#pagination-product")),
		(l = window.location.href),
		(c = current_url()),
		$.ajax({
			url: base_url + a + t.page,
			dataType: "json",
			type: "POST",
			data: t,
			beforeSend: function (e) {
				$('input[type="checkbox"]').prop("disabled", !0),
					$(".datepicker-start").prop("disabled", !0),
					$(".datepicker-end").prop("disabled", !0),
					$(".reset-filter").prop("disabled", !0),
					$(n).hide(),
					$(d).hide(),
					loader_show(".loader", "loading"),
					setTimeout(function () {
						var e = $(n).offset();
						$("html, body").scrollTop(e);
					});
			},
			success: function (e) {
				$('input[type="checkbox"]').prop("disabled", !1),
					$(".datepicker-start").prop("disabled", !1),
					$(".datepicker-end").prop("disabled", !1),
					$(".reset-filter").prop("disabled", !1);
				var t = e.data,
					a = (e.pagination, e.page),
					o = e.total_rows,
					p = e.limit,
					u = l,
					h = e.url;
				if (
					("" !== e &&
						((u = e.url),
							(u =
								"" !== c ?
								"" == u ?
								r + a :
								r + a + "?" + u :
								"" !== u ?
								r + a + "?" + u :
								r + e.page)),
						"product-category" == _uri_segment_1)
				) {
					var g = window.location,
						m = g.protocol + "//" + g.host + "/" + g.pathname.split("/")[1];
					s >= 2 ?
						(window.location.href = m + "/" + u) :
						($(n).empty(),
							$(d).empty(),
							setTimeout(function () {
								$.trim($(n).html()).length ||
									($(n).append(t),
										setTimeout(function () {
											$(document).find(".product-items").matchHeight(),
												$("html, body").animate({
													scrollTop: $(n).offset().top - 15,
												});
										}));
							}));
				} else
					history_push({
						page: u,
						result: e
					}, i, u),
					$(n).empty(),
					$(d).empty(),
					setTimeout(function () {
						$.trim($(n).html()).length ||
							($(n).append(t),
								setTimeout(function () {
									$(document).find(".product-items").matchHeight(),
										$("html, body").animate({
											scrollTop: $(n).offset().top - 15,
										});
								}));
					});
				$.trim($(d).html()).length ?
					0 == o ?
					$(d).pagination("destroy") :
					$(d).pagination("drawPage", a) :
					("" !== h && (h = "?" + h),
						getPagination(d, "search/", {
							data_page_number: a,
							data_total_rows: o,
							data_limit: p,
							data_url: h,
						}));
			},
			complete: function () {
				$('input[type="checkbox"]').prop("disabled", !1),
					$(".datepicker-start").prop("disabled", !1),
					$(".datepicker-end").prop("disabled", !1),
					$(".reset-filter").prop("disabled", !1),
					$(n).show(),
					$(d).show(),
					loader_hide(".loader");
			},
			fail: function () {
				$('input[type="checkbox"]').prop("disabled", !1),
					$(".datepicker-start").prop("disabled", !1),
					$(".datepicker-end").prop("disabled", !1),
					$(".reset-filter").prop("disabled", !1),
					$(n).show(),
					$(d).show(),
					loader_hide(".loader");
			},
			error: function (e, t, a) {
				alert(a),
					$(n).show(),
					$(d).show(),
					loader_hide(".loader"),
					$('input[type="checkbox"]').prop("disabled", !1),
					$(".datepicker-start").prop("disabled", !1),
					$(".datepicker-end").prop("disabled", !1),
					$(".reset-filter").prop("disabled", !1);
			},
		}),
		!1
	);
}
("categories" != _uri_segment_1 &&
	"product" != _uri_segment_1 &&
	"product-category" != _uri_segment_1) ||
History.Adapter.bind(window, "statechange", function () {
		if (window.stateChangeIsLocal) window.stateChangeIsLocal = !1;
		else {
			var e = History.getState().data,
				t = !popped && location.href == initialURL;
			if (((popped = !0), t)) return;
			var a = e.result.classname,
				i = e.result.start_date,
				r = e.result.end_date;
			void 0 === i ?
				$(".datepicker-start").val("") :
				$(".datepicker-start").val(i),
				void 0 === r ?
				$(".datepicker-end").val("") :
				$(".datepicker-end").val(r),
				void 0 === a ?
				$(document).find('input[type="checkbox"]').prop("checked", !1) :
				$(document)
				.find('input[type="checkbox"]')
				.each(function () {
					var e = $(this).data("index"),
						t = $("." + e)[0],
						i = !1;
					$.each(a, function (e, a) {
							$(t).hasClass(a) && (i = !0);
						}),
						$(t).prop("checked", i);
				});
			var o = getUrlVars(e.newurl),
				s = $("#item-list-product"),
				n = $("#pagination-product"),
				d = window.location.href,
				l = current_url(),
				c = e.result,
				p = e.result.data,
				u = e.result.pagination;
			"" !== c &&
				((o = c.url), (o = "" !== l ? "?" + o : "" !== o ? "?" + o : d));
			_this_loader_show("#loader-list-product"),
				$(s).hide(),
				$(n).hide(),
				$(s).empty(),
				$(n).empty(),
				setTimeout(function () {
					$.trim($(s).html()).length ||
						($(s).append(p),
							setTimeout(function () {
								$(document).find(".product-items").matchHeight(),
									$("html, body").animate({
										scrollTop: $(s).offset().top - 15
									});
							})),
						$.trim($(n).html()).length || $(n).append(u);
				}, 10),
				setTimeout(function () {
					$(s).show(),
						$(n).show(),
						_this_loader_disabled("#loader-list-product");
				}, 20);
		}
	}),
	"search" == _uri_segment_1 &&
	History.Adapter.bind(window, "statechange", function () {
		if (window.stateChangeIsLocal) window.stateChangeIsLocal = !1;
		else {
			var e = History.getState().data,
				t = !popped && location.href == initialURL;
			if (((popped = !0), t)) return;
			var a = getUrlVars(e.newurl),
				i = $("#search-list"),
				r = $("#pagination-search-product"),
				o = window.location.href,
				s = current_url(),
				n = e.result,
				d = e.result.data,
				l = e.result.pagination;
			"" !== n &&
				((a = n.url), (a = "" !== s ? "?" + a : "" !== a ? "?" + a : o));
			_this_loader_show("#loader-list-product"),
				$(i).hide(),
				$(r).hide(),
				$(i).empty(),
				$(r).empty(),
				setTimeout(function () {
					$.trim($(i).html()).length ||
						($(i).append(d),
							setTimeout(function () {
								var e = $(document).find(".size-select2"),
									t = $(document).find(".gender-select2"),
									a = $(document).find(".store-select2"),
									i = $(document).find(".datepicker-search-start"),
									r = $(document).find(".datepicker-search-end");
								search_select2(e, t, a),
									search_datepicker(i),
									search_datepicker(r),
									$(document).find(".product-items").matchHeight(),
									$("html, body").animate({
										scrollTop: $(".form-search-product").offset().top - 15,
									});
							})),
						$.trim($(r).html()).length || $(r).append(l);
				}, 10),
				setTimeout(function () {
					$(i).show(),
						$(r).show(),
						_this_loader_disabled("#loader-list-product");
				}, 20);
		}
	});
var sidebar_categories = $(".ui.accordion").accordion();
$(sidebar_categories).accordion({
	selector: {
		trigger: ".icon"
	},
	exclusive: !1,
	onOpening: function (e) {
		var t = $(this).prev(),
			a = $(t).find("i");
		$(a).hasClass("plus") && ($(a).removeClass("plus"), $(a).addClass("minus"));
	},
	onClosing: function (e) {
		var t = $(this).prev(),
			a = $(t).find("i");
		$(a).hasClass("minus") &&
			($(a).removeClass("minus"), $(a).addClass("plus"));
	},
});
var _uri_cat_or_prod = _uri_init.segment(0);
_uri_page = _uri_init.segment(1);
if (
	("categories" == _uri_segment_1 ||
		"product" == _uri_segment_1 ||
		"product-category" == _uri_segment_1) &&
	(void 0 === _uri_page ||
		"" == _uri_page ||
		1 == $.isNumeric(_uri_page) ||
		("product-category" == _uri_segment_1 && !$.isNumeric(_uri_page)))
) {
	var _url = getUrlVars((_this_location_href = window.location.href));
	(void 0 !== _uri_page && "" != _uri_page) || (_uri_page = 1);
	classname = [];
	if (
		($('input[type="checkbox"]').each(function () {
				this.checked && classname.push($(this).data("index"));
			}),
			"product-category" == _uri_segment_1)
	) {
		_uri_page = 1;
		var _get_flag = $('input[type="checkbox"]:checked').data("categories"),
			_get_slug = $('input[type="checkbox"]:checked').val();
		(_url = {})[_get_flag] = _get_slug;
	}
	var data_options = {
			url: _url,
			page: _uri_page,
			classname: classname
		},
		start_date = $(".datepicker-start"),
		end_date = $(".datepicker-end");
		search_datepicker(start_date),
		search_datepicker(end_date),
		filterProductsByTag(_url, data_options),
			filterProductsByTagNew();
			
		$('input[type="checkbox"]').on("click", function (e) {
			// $(".drawer-toggle-sidebar").trigger("click");
			var t = $(this).data("index"),
				a = $(this),
				i = getUrlVars($(a).val()),
				r = $(this).parent().parent(),
				o = $(r).find("i"),
				s = !1;
			if (o.length > 0) {
				$(o).trigger("click");
				var n = $(this).prop("checked"),
					d = $(r).prop("id"),
					l = $("." + d).find('input[type="checkbox"]');
				1 == n ?
					l.length > 0 &&
					$.each(l, function (e, t) {
						$(t).prop("checked", !0);
					}) :
					0 == n &&
					l.length > 0 &&
					$.each(l, function (e, t) {
						$(t).prop("checked", !1);
					}),
					(s = !0);
			}
			setTimeout(function () {
				var e = {},
					r = [],
					o = [];
				$('input[type="checkbox"]').each(function () {
						this.checked &&
							(void 0 === e[this.name] && (e[this.name] = []),
								e[this.name].push(this.value),
								o.push($(this).data("index")));
					}),
					$.each(e, function (e, t) {
						r.push(e + "=" + t.join("||"));
					});
				var n = r.join("&"),
					d = ($(a).val(), window.location.href, $(a).data("slug")),
					l = $(a).data("categories"),
					c = current_url(),
					p = _uri_init.segment(0),
					u = _uri_init.segment(1);
				(_uri_page = 1),
				1 == s ? ((i = getUrlVariable(n)), (d = "")) : (i = getUrlVars(n)),
					(_data_options = {
						url: i,
						slug: d,
						categories: l,
						page: _uri_page,
						classname: o,
						current_classname: t,
						uri_cat: p,
						uri_slug: u,
						current_url: c,
						checked: e,
					}),
					filterProductsByTag(n, _data_options);
					// filterProductsByTagNew();
			}, 10);
		}),
		$(document).on('click', '.do-filter', function (e) {
			// alert('data');
			$(".drawer-toggle-sidebar").trigger("click");
			e.preventDefault();
			filterProductsByTagNew();
		}),
		$(document)
		.find("#pagination-product")
		.on("click", "a", function (e) {
			e.preventDefault();
			var t,
				a = $("#pagination-product"),
				i = $(a).pagination("getCurrentPage");
			filterProductsByTag((t = getUrlVars((t = $(this).prop("href")))), {
				url: t,
				page: i,
				classname: get_checkbox_classname(),
			});
			filterProductsByTagNew();
		}),
		$(document)
		.find("#item-list-product")
		.on("change", ".show", function (e) {
			e.preventDefault();
			var t = getUrlVars((t = window.location.href)),
				a = get_checkbox_classname(),
				i = $(this).val();
			void 0 === t.show ?
				(t.show = i) :
				"default" == i ?
				delete t.show :
				(delete t.show, (t.show = i)),
				void 0 === _uri_page && (_uri_page = 1),
				filterProductsByTag(t, {
					url: t,
					page: _uri_page,
					classname: a
				});
				filterProductsByTagNew();
		}),
		$(document)
		.find("#item-list-product")
		.on("change", ".sort-by", function (e) {
			e.preventDefault();
			var t = getUrlVars((t = window.location.href)),
				a = get_checkbox_classname(),
				i = $(this).val(),
				r = $(this).find(":selected").data("name");
			void 0 === t[r] ? (t[r] = i) : (delete t[r], (t[r] = i)),
				void 0 === _uri_page && (_uri_page = 1),
				filterProductsByTag(t, {
					url: t,
					page: _uri_page,
					classname: a
				});
				filterProductsByTagNew();
		}),
		$(document).on("click", ".reset-filter", function (e) {
			e.preventDefault(), $(".drawer-toggle-sidebar").trigger("click");
			var t = window.location.href,
				a = t.indexOf("?"),
				i = t.substring(a),
				r = t.replace(i, "");
			(t = r),
			(_uri_page = 1),
			$(".datepicker-start").val(""),
				$(".datepicker-end").val(""),
				filterProductsByTag(_url, {
					url: t,
					page: _uri_page
				});
				filterProductsByTagNew();
		});
}
if ("search" == _uri_segment_1) {
	var _this_location_href;
	_url = getUrlVars((_this_location_href = window.location.href));
	void 0 === _uri_page && (_uri_page = 1),
		searchProduct(_url, (data_options = {
			url: _url,
			page: _uri_page
		})),
		$(document).on("click", ".reset-filter", function (e) {
			e.preventDefault();
			var t = window.location.href,
				a = t.indexOf("?"),
				i = t.substring(a),
				r = t.replace(i, "");
			searchProduct(_url, {
					url: (t = r),
					page: (_uri_page = 1)
				}),
				$("#search-header").val(""),
				$(".form-advanced-search").find("strong").html(""),
				$("input[name='k']").val(""),
				$(".gender-select2").val(null).trigger("change"),
				$(".size-select2").val(null).trigger("change"),
				$(".store-select2").val(null).trigger("change"),
				clear_datepicker(".datepicker-search-start"),
				clear_datepicker(".datepicker-search-end");
		}),
		$(document)
		.find("#search-list")
		.on("change", ".show", function (e) {
			e.preventDefault();
			var t = getUrlVars((t = window.location.href)),
				a = get_checkbox_classname(),
				i = $(this).val();
			void 0 === t.show ?
				(t.show = i) :
				"default" == i ?
				delete t.show :
				(delete t.show, (t.show = i)),
				void 0 === _uri_page && (_uri_page = 1),
				searchProduct(t, {
					url: t,
					page: _uri_page,
					classname: a
				});
		}),
		$(document)
		.find("#search-list")
		.on("change", ".sort-by", function (e) {
			e.preventDefault();
			var t = getUrlVars((t = window.location.href)),
				a = get_checkbox_classname(),
				i = $(this).val(),
				r = $(this).find(":selected").data("name");
			void 0 === t[r] ? (t[r] = i) : (delete t[r], (t[r] = i)),
				void 0 === _uri_page && (_uri_page = 1),
				searchProduct(t, {
					url: t,
					page: _uri_page,
					classname: a
				});
		}),
		$(".form-search-product").on("submit", function (e) {
			e.preventDefault();
			window.location.href, $(this).serializeArray(), $(this).serialize();
			var t = {},
				a = [],
				i = $(this).find("input[name='k']").val(),
				r = $(this).find("input[name='start']").val(),
				o = $(this).find("input[name='end']").val();
			"" != i && (void 0 === t.k && (t.k = []), t.k.push(i)),
				"" != r && (void 0 === t.start && (t.start = []), t.start.push(r)),
				"" != o && (void 0 === t.end && (t.end = []), t.end.push(o));
			var s = $(".size-select2");
			(s = $(s).select2("val")).length > 0 &&
				(void 0 === t.size && (t.size = []), t.size.push(s));
			var n = $(".gender-select2");
			(n = $(n).select2("val")).length > 0 &&
				(void 0 === t.gender && (t.gender = []), t.gender.push(n));
			var d = $(".store-select2");
			(d = $(d).select2("val")).length > 0 &&
				(void 0 === t.store_location && (t.store_location = []),
					t.store_location.push(d)),
				$.each(t, function (e, t) {
					a.push(e + "=" + t.join("||"));
				});
			var l = a.join("&");
			searchProduct((l = l.replace(/,/g, "||")), {
				url: (_url = getUrlVariable(l)),
				page: 1,
			});
		}),
		$(document)
		.find("#pagination-search-product")
		.on("click", "a", function (e) {
			e.preventDefault();
			var t,
				a = $("#pagination-search-product"),
				i = $(a).pagination("getCurrentPage");
			searchProduct((t = getUrlVars((t = $(this).prop("href")))), {
				url: t,
				page: i,
				classname: get_checkbox_classname(),
			});
		});
}
var slideWrapper = $(".slideshow-main"),
	iframes = slideWrapper.find(".embed-player"),
	lazyImages = slideWrapper.find(".slideshow-items"),
	lazyCounter = 0;

function postMessageToPlayer(e, t) {
	null != e && null != t && e.contentWindow.postMessage(JSON.stringify(t), "*");
}

function playPauseVideo(e, t) {
	var a, i, r, o, s;
	if (
		((i = (a = e.find(".slick-current")).attr("class").split(" ")[1]),
			(o = a.find("iframe").get(0)),
			(r = a.data("video-start")),
			"vimeo" === i)
	)
		switch (t) {
			case "play":
				null != r &&
					r > 0 &&
					!a.hasClass("started") &&
					(a.addClass("started"),
						postMessageToPlayer(o, {
							method: "setCurrentTime",
							value: r
						})),
					postMessageToPlayer(o, {
						method: "play",
						value: 1
					});
				break;
			case "pause":
				postMessageToPlayer(o, {
					method: "pause",
					value: 1
				});
		}
	else if ("youtube" === i)
		switch (t) {
			case "play":
				postMessageToPlayer(o, {
						event: "command"
					}),
					postMessageToPlayer(o, {
						event: "command",
						func: "playVideo"
					});
				break;
			case "pause":
				postMessageToPlayer(o, {
					event: "command",
					func: "pauseVideo"
				});
		}
	else
		"video" === i &&
		null != (s = a.children("video").get(0)) &&
		("play" === t ? s.play() : s.pause());
}

function resizePlayer(e, t) {
	if (e[0]) {
		var a,
			i,
			r = $(".main-slider"),
			o = r.width(),
			s = r.height();
		t = t || 1110 / 623;
		e.each(function () {
			var e = $(this);
			o / t < s ?
				((a = Math.ceil(s * t)),
					e
					.width(a)
					.height(s)
					.css({
						left: (o - a) / 2,
						top: 0
					})) :
				((i = Math.ceil(o / t)),
					e
					.width(o)
					.height(i)
					.css({
						left: 0,
						top: (s - i) / 2
					}));
		});
	}
}

function loader_show(e, t = "") {
	"" == t && (t = !1),
		$(e).busyLoad("show", {
			text: t,
			color: "#707070",
			background: "rgba(255,255,255,1)",
		});
}

function loader_hide(e) {
	$(e).busyLoad("hide");
}
$(function () {
	$(document).on("bl.shown", function (e, t, a) {
			$(a).css("height", "200px");
		}),
		$(document).on("bl.hidden", function (e, t, a) {
			$(a).css("height", "0px");
		});
	var e = $(window),
		t = $(".drawer"),
		a = $(".drawer-sidebar"),
		i = {
			class: {
				nav: "drawer-nav",
					toggle: "drawer-toggle",
					overlay: "drawer-overlay",
					open: "drawer-open",
					close: "drawer-close",
					dropdown: "drawer-dropdown",
			},
			iscroll: {
				mouseWheel: !1,
				preventDefault: !1
			},
			showOverlay: !0,
		},
		r = {
			class: {
				nav: "drawer-menu-sidebar",
					toggle: "drawer-hamburger-sidebar",
					overlay: "drawer-overlay-sidebar",
					open: "drawer-open",
					close: "drawer-close",
					dropdown: "drawer-dropdown",
			},
			iscroll: {
				mouseWheel: !1,
				preventDefault: !1
			},
			showOverlay: !0,
		};

	function o() {
		var o = $(e).width(),
			s = $("#ex1");
		o > 991 ?
			(setTimeout(function () {
					t.length > 0 && $(t).drawer("destroy");
				}),
				setTimeout(function () {
					a.length > 0 && $(a).drawer("destroy");
				}, 10),
				s.length > 0 &&
				($(s).trigger("zoom.destroy"),
					$(s).zoom({
						magnify: 1,
						on: "mouseover",
						touch: !0
					}))) :
			(s.length > 0 &&
				($(s).zoom(),
					$(s).trigger("zoom.destroy"),
					$(s).on("click", function (e) {
						e.preventDefault();
						var t = $(this).find("img").attr("src"),
							a = window.open(t, "_blank");
						a && a.focus();
					})),
				setTimeout(function () {
					t.length > 0 && $(t).drawer(i);
				}),
				setTimeout(function () {
					a.length > 0 && $(a).drawer(r);
				}, 10));
	}
	t.length > 0 && $(t).drawer(i),
		$(".box-info-product").matchHeight(),
		setTimeout(function () {
			a.length > 0 && $(a).drawer(r);
		}, 10),
		o(),
		$(window).resize(function () {
			$(this).width();
			o();
		}),
		$(".product-items").matchHeight(),
		$(".dropdown-menu a.dropdown-toggle").on("click", function (e) {
			return (
				$(this).next().hasClass("show") ||
				$(this)
				.parents(".dropdown-menu")
				.first()
				.find(".show")
				.removeClass("show"),
				$(this).next(".dropdown-menu").toggleClass("show"),
				$(this)
				.parents("li.nav-item.dropdown.show")
				.on("hidden.bs.dropdown", function (e) {
					$(".dropdown-submenu .show").removeClass("show");
				}),
				!1
			);
		});
	var s = $.fn.popover.Constructor.prototype.leave;
	$.fn.popover.Constructor.prototype.leave = function (e) {
		var t,
			a,
			i =
			e instanceof this.constructor ?
			e :
			$(e.currentTarget)[this.type](this.getDelegateOptions())
			.data("bs." + this.type);
		s.call(this, e),
			e.currentTarget &&
			((t = $(e.currentTarget).siblings(".popover")),
				(a = i.timeout),
				t.one("mouseenter", function () {
					clearTimeout(a),
						t.one("mouseleave", function () {
							$.fn.popover.Constructor.prototype.leave.call(i, i);
						});
				}));
	};
	var n = $("base").attr("href");
	$(".dimmer"), $(".categories-check"), new URI();

	function d() {
		var e = window.location.href,
			t = e.indexOf("?"),
			a = e.substring(t),
			i = e.replace(a, "");
		return (e = i);
	}
	d(),
		$(".categories-check"),
		new URI(),
		$(".categories-check"),
		d(),
		$("#item-list-product");

	function l(e) {
		$(e).find(".segment").dimmer("show");
		var t = $(e).find(".segment").find(".dimmer"),
			a = $(e).find(".segment").find(".loader");
		$(e).find(".segment").removeClass("dimmed"),
			$(t).hasClass("disabled") && $(t).removeClass("disabled"),
			$(a).hasClass("disabled") && $(a).removeClass("disabled"),
			$(t).addClass("active"),
			$(a).addClass("active");
	}

	function c(e) {
		$(e).find(".segment").dimmer("hide");
		var t = $(e).find(".segment").find(".dimmer"),
			a = $(e).find(".segment").find(".loader");
		$(e).find(".segment").addClass("dimmed"),
			$(t).hasClass("active") && $(t).removeClass("active"),
			$(a).hasClass("active") && $(a).removeClass("active"),
			$(t).addClass("disabled"),
			$(a).addClass("disabled");
	}
	slideWrapper.on("init", function (e) {
			(e = $(e.currentTarget)),
			setTimeout(function () {
					playPauseVideo(e, "play");
				}, 1e3),
				resizePlayer(iframes, 1110 / 623);
		}),
		slideWrapper.on("beforeChange", function (e, t) {
			playPauseVideo((t = $(t.$slider)), "pause");
		}),
		slideWrapper.on("afterChange", function (e, t) {
			playPauseVideo((t = $(t.$slider)), "play");
		}),
		slideWrapper.on("lazyLoaded", function (e, t, a, i) {
			++lazyCounter === lazyImages.length && lazyImages.addClass("show");
		}),
		$(slideWrapper).slick({
			arrow: !0,
			dots: !0,
			infinite: !0,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: !0,
			autoplaySpeed: 4e3,
		}),
		$(".product-home-slide").slick({
			arrow: !0,
			dots: !1,
			infinite: !0,
			slidesToShow: 3,
			slidesToScroll: 1,
			autoplay: !0,
			autoplaySpeed: 4e3,
			responsive: [{
					breakpoint: 992,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 450,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				},
			],
		});
	$("#search-header");
	var p = $(".size-select2"),
		u = $(".gender-select2"),
		h = $(".store-select2");

	function g(e, t) {
		$(e).slick(t);
	}
	$.fn.select2 &&
		($(p).select2({
				placeholder: "Size"
			}),
			$(u).select2({
				placeholder: "Gender"
			}),
			$(h).select2({
				placeholder: "Store Location"
			})),
		$(".datepicker").datepicker({
			format: "d MM yyyy",
			autoclose: !0
		}),
		$(".product-slide").slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: !1,
			fade: !0,
			asNavFor: ".product-slide-nav",
		}),
		$(".product-slide-nav").slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: ".product-slide",
			dots: !1,
			focusOnSelect: !0,
			arrows: !1,
		});
	var m = {
		arrow: !0,
		dots: !1,
		infinite: !0,
		slidesToShow: 4,
		slidesToScroll: 1,
		autoplay: !0,
		autoplaySpeed: 4e3,
		responsive: [{
				breakpoint: 1199,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 450,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			},
		],
	};

	function f(e) {
		$(".popover-content").remove(), $(".popover").remove();
		var t = e.data,
			a = $(document).find("#calendar");
		if (a.length > 0) {
			var i = new Date(),
				r = (new Date(i.getFullYear(), i.getMonth(), i.getDate()), []),
				o = [],
				s = [],
				n = [],
				d = [],
				l = [],
				c = $(a).data("class"),
				p = $(a).data("date"),
				u = $(a).data("stock"),
				h = $(a).data("rental"),
				lastSevenLate = i.getDate()-7;

			if (
				((c = c.split(",")),
					(p = p.split(",")),
					(u = u.split(",")),
					(h = h.split(",")),
					p.length > 0)
			)
				for (var g = 0; g < p.length; g++)
					(o[p[g]] = c[g]), (s[p[g]] = u[g]), (n[p[g]] = h[g]);
			$(a)
				.datepicker({
					templates: {
						leftArrow: '<i class="arrow left icon"></i>',
						rightArrow: '<i class="arrow right icon"></i>',
					},
					defaultViewDate: "month",
					stepMonths: 0,
					changeMonth: !1,
					changeYear: !1,
					startDate: "0",
					autoclose: !0,
					beforeShowDay: function (e) {
						var t = e.getMonth() + 1 + "/" + e.getDate() + "/" + e.getFullYear();
						if (t in s) {
							var a = s[t].split(",");
							d.push(a);
						}
						if (t in n) {
							a = n[t].split(",");
							l.push(a);
						}
						if (t in o) {
							var i = t.split("/"),
								c = o[t].split(",");
							let redClass = ' ';
							let dateLate = new Date(i[2], i[0], i[1]);
							if(dateLate.getDate() >= lastSevenLate){
								redClass = ' red-date';
							}
							return (
								r.push("toggle-" + i[0] + i[1] + i[2]),
								{
									classes: c[0] + " toggle-" + i[0] + i[1] + i[2] + redClass
								}
							);
						}
					},
				})
				.on("changeDate", function (e) {
					$(".popover-content").remove(), $(".popover").remove();
					var a = e.format(),
						i = "0/0";
					void 0 === t ?
						($(".popover-content").remove(), $(".popover").remove()) :
						((i = t.default.available + "/" + t.default.stock),
							$.each(t, function (e, t) {
								a == e && "default" !== e && (i = t.available + "/" + t.stock);
							}),
							$(".popover-content").remove(),
							$(".popover").remove());
					$(e.target);
					var r = $(this).find(".datepicker-days").find(".day");
					$(r).each(function (e, t) {
							$(t).hasClass("active") ?
								($(t).popover({
										container: "body",
										trigger: "click",
										title: "Availability",
										content: i,
										html: !0,
										placement: "top",
									}),
									$(t).popover("show")) :
								$(t).popover("hide");
						}),
						$(r).trigger("mouseenter touchstart");
				})
				.on("changeMonth", function (e) {
					$(".popover-content").remove(), $(".popover").remove();
				}),
				$(a)
				.find(".datepicker-switch")
				.click(function (e) {
					e.preventDefault(), e.stopPropagation();
				});
		}
		$(a).on("mouseleave touchend touchcancel", function () {
			$(".popover-content").remove(), $(".popover").remove();
		});
	}
	var _ = $("input[name='cat_slug[]']"),
		v = {};
	_.length > 0 &&
		$.each(_, function (e, t) {
			var a = $(this).val(),
				i = $(this).data("categories");
			void 0 === v[i] && (v[i] = []);
			a.split(",");
			v[i].push(a);
		});
	// var w = $("input[name='cat[]']"),
	// 	y = $("input[name='prod']"),
	// 	b = [],
	// 	k = $(".subtitle-product-detail");
	// change w & y to empty array to disable ajax
	var w = [],
		y = [],
		b = [],
		k = $(".subtitle-product-detail");
	w.length > 0 && y.length > 0 ?
		((y = $(y).val()),
			$.each(w, function (e, t) {
				var a = $(t).val();
				b.push(a);
			}),
			$.ajax({
				url: n + "getrelated",
				data: {
					product_id: y,
					category_id: b,
					category_slug: v
				},
				type: "POST",
				dataType: "json",
				beforeSend: function (e) {
					l(".wrapper-box-related-product");
				},
				success: function (e) {
					var t = $(".wrapper-related-slide");
					$(document).find(".related-slide");
					$(t).empty(),
						1 == e.flag ?
						($.each(k, function (e, t) {
								$(t).hasClass("related") &&
									$(t).hasClass("hide") &&
									($(t).removeClass("hide"), $(t).addClass("show"));
							}),
							setTimeout(function () {
								$.trim($(t).html()).length ||
									($(t).append(e.template),
										g(".related-slide", m),
										setTimeout(function () {
											$(document).find(".product-items").matchHeight();
										})),
									c(".wrapper-box-related-product");
							})) :
						($.each(k, function (e, t) {
								$(t).hasClass("related") && $(t).parent().parent().remove();
							}),
							c(".wrapper-box-related-product"));
				},
				fail: function () {
					c(".wrapper-box-related-product");
				},
			})) :
		$.each(k, function (e, t) {
			$(t).hasClass("related") && $(t).parent().parent().remove();
		}),
		// (y = $("input[name='prod']")),
		// (k = $(".subtitle-product-detail")),
		(y = []),
		(k = $(".subtitle-product-detail")),
		y.length > 0 ?
		((y = $(y).val()),
			$.ajax({
				url: n + "getsugges",
				data: {
					product_id: y
				},
				type: "POST",
				dataType: "json",
				beforeSend: function () {
					l(".wrapper-box-sugges-product");
				},
				success: function (e) {
					var t = $(".wrapper-sugges-slide");
					$(document).find(".sugges-slide");
					$(t).empty(),
						1 == e.flag ?
						($.each(k, function (e, t) {
								$(t).hasClass("sugges") &&
									$(t).hasClass("hide") &&
									($(t).removeClass("hide"), $(t).addClass("show"));
							}),
							setTimeout(function () {
								$.trim($(t).html()).length ||
									($(t).append(e.template),
										g(".sugges-slide", m),
										setTimeout(function () {
											$(document).find(".product-items").matchHeight();
										})),
									c(".wrapper-box-sugges-product");
							})) :
						($.each(k, function (e, t) {
								$(t).hasClass("sugges") && $(t).parent().parent().remove();
							}),
							c(".wrapper-box-sugges-product"));
				},
				fail: function () {
					c(".wrapper-box-sugges-product");
				},
			})) :
		$.each(k, function (e, t) {
			$(t).hasClass("sugges") && $(t).parent().parent().remove();
		});
	window.History, $("#log");
	new URI();
	var T = $("input[name='radio_size']"),
		C = $("input[name='radio_size_product']"),
		y = $("input[name='prod']"),
		x = $(".main-calendar");
	if (1 == T.length) {
		var P = $(T).val();
		l(".wrapper-datepicker"),
			x.length > 0 && $(x).css("opacity", 0),
			$.ajax({
				url: n + "getcalendar",
				data: {
					product_id: $(y).val(),
					product_sizestock_id: P
				},
				type: "POST",
				dataType: "json",
				beforeSend: function (e) {
					l(".wrapper-datepicker");
				},
				success: function (e) {
					if (1 == e.flag) {
						var t = $(".main-calendar");
						$(t).empty();
						var a = e;
						setTimeout(function () {
							$.trim($(t).html()).length ||
								($(t).append(e.template),
									setTimeout(function () {
										f(a),
											c(".wrapper-datepicker"),
											x.length > 0 && $(x).css("opacity", 1);
									}));
						});
					}
				},
				fail: function () {
					x.length > 0 && $(x).css("opacity", 1), c(".wrapper-datepicker");
				},
			});
	}
	$(C).on("change", function (e) {
		e.preventDefault(), l(".wrapper-datepicker");
		var t = $(this).val();
		x.length > 0 && $(x).css("opacity", 0),
			$.ajax({
				url: n + "getcalendar",
				data: {
					product_id: $(y).val(),
					product_sizestock_id: t
				},
				type: "POST",
				dataType: "json",
				beforeSend: function (e) {
					l(".wrapper-datepicker"),
						$(".popover-content").remove(),
						$(".popover").remove();
				},
				success: function (e) {
					if (1 == e.flag) {
						var t = e.product_sizestock;
						(void 0 !== t || t.length > 0) &&
						$(".subtitle-datepicker").html(t[0].product_size);
						var a = $(".main-calendar");
						$(a).empty();
						var i = e;
						setTimeout(function () {
							$.trim($(a).html()).length ||
								($(a).append(e.template),
									setTimeout(function () {
										f(i),
											c(".wrapper-datepicker"),
											x.length > 0 && $(x).css("opacity", 1),
											$("html, body").animate({
												scrollTop: $(a).offset().top - 15,
											});
									}));
						});
					}
				},
				fail: function () {
					x.length > 0 && $(x).css("opacity", 1), c(".wrapper-datepicker");
				},
			});
	});
});
