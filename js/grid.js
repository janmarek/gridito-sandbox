/**
 * Gridito javascript
 *
 * @author Jan Marek
 * @license MIT
 */
var gridito = {
	loadWindow: function (href, title) {
		var el = jQuery('<div></div>').attr("title", title).appendTo('body');
		el.load(href, function () {
			el.dialog({
				modal: true,
				width: "auto",
				height: "auto"
			});
			el.find("input:first").focus();
		});
	},

	confirmationQuestion: function (event, question) {
		// thx to Panda
		var e = $.Event(event);

		if (!confirm(question)) {
			e.stopImmediatePropagation();
			e.preventDefault();
		}
	},

	initializeGrid: function (grid) {
		// sorting icons
		function initSortingIcons(normalClass, hoverClass) {
			grid.find("table.gridito-table th .sorting a span." + normalClass).hover(function () {
				$(this).removeClass(normalClass).addClass(hoverClass);
			}, function () {
				$(this).removeClass(hoverClass).addClass(normalClass);
			});
		};

		initSortingIcons("ui-icon-carat-2-n-s", "ui-icon-triangle-1-n");
		initSortingIcons("ui-icon-triangle-1-n", "ui-icon-triangle-1-s");
		initSortingIcons("ui-icon-triangle-1-s", "ui-icon-carat-2-n-s");

		// buttons
		grid.find("table.gridito-table td.gridito-actioncell a, div.gridito-toolbar a").each(function () {
			var options = {};
			var el = $(this);
			var icon = el.attr("icon");
			if (icon) options.icons = {primary: icon};
			el.button(options);
		});

		// tr hover
		grid.find("table.gridito-table tbody tr").hover(function () {
			$(this).addClass("ui-state-hover");
		}, function () {
			$(this).removeClass("ui-state-hover");
		});

		// paginator buttons
		grid.find("div.gridito-paginator a").each(function () {
			var el = $(this);

			el.button({
				disabled: el.hasClass("disabled")
			});
		});
	}
};

// init
$("div.gridito").livequery(function () {
	gridito.initializeGrid($(this));
});