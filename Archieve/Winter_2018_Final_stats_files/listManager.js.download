if(typeof BEDL=="undefined"||!BEDL){var BEDL={};}

if(!BEDL.page){BEDL.page = {};}

(function()
{
	BEDL.page.listManager = {
		init: function() {
			var lists = YAHOO.util.Dom.getElementsByClassName("bedl-listManager");
			for(var i = 0; i < lists.length; i++) {
				var defaultState = lists[i].getAttribute("data-bedl-listmanager-defaultState");

				if(!defaultState || defaultState !== "expanded") {
					YAHOO.util.Dom.addClass(lists[i], "bedl-listManager-collapsed");
					state = "collapsed";
				} else {
					YAHOO.util.Dom.addClass(lists[i], "bedl-listManager-expanded");
					state = "expanded";
				}

				BEDL.page.listManager.insertStateElement(lists[i], state);
			}
		},

		insertStateElement: function(bedlList, state) {
			var stateElement = document.createElement("a");
			stateElement.setAttribute("title", state);
			stateElement.setAttribute("href", "#");
			YAHOO.util.Dom.addClass(stateElement, "stateModifier");

			YAHOO.util.Event.addListener(stateElement, "click", BEDL.page.listManager.changeState, bedlList);
			bedlList.insertBefore(stateElement, bedlList.firstChild, bedlList);
		},

		changeState: function(e, bedlList) {
			var stateModifier = YAHOO.util.Event.getTarget(e);

			if(YAHOO.util.Dom.hasClass(bedlList, "bedl-listManager-collapsed")) {
				YAHOO.util.Dom.removeClass(bedlList, "bedl-listManager-collapsed");
				YAHOO.util.Dom.addClass(bedlList, "bedl-listManager-expanded");
				//If target element was a link, set the title appropriately
				if(stateModifier.tagName == "A") {
					this.setAttribute("title", "expanded");
				}
			} else {
				YAHOO.util.Dom.removeClass(bedlList, "bedl-listManager-expanded");
				YAHOO.util.Dom.addClass(bedlList, "bedl-listManager-collapsed");
				if(stateModifier.tagName == "A") {
					this.setAttribute("title", "collapsed");
				}
			}

			YAHOO.util.Event.stopEvent(e);
		}
	};
})();

YAHOO.util.Event.addListener(window, "load", BEDL.page.listManager.init);