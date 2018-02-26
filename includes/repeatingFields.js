if(typeof BEDL=="undefined"||!BEDL){var BEDL={};}

if(!BEDL.form){BEDL.form = {};}

(function()
{
	BEDL.form.repeatingFields = {
		init: function() {
			var repeatingElements = YAHOO.util.Dom.getElementsByClassName("bedl-repeatingFields");
			for(var i = 0; i < repeatingElements.length; i++) {
				var bedlFieldset = repeatingElements[i].getElementsByTagName("fieldset")[0];
				if(bedlFieldset) {
					BEDL.form.repeatingFields.setFieldsetIndex(bedlFieldset);
					BEDL.form.repeatingFields.addInputListeners(bedlFieldset);
				}
			}
		},

		addInputListeners: function(bedlFieldset) {
			var bedlInputs = YAHOO.util.Dom.getElementsByClassName("bedl-input", null, bedlFieldset);

			for(var i = 0; i < bedlInputs.length; i++) {
				if(bedlInputs[i].getAttribute("data-bedl-addnewset")) {
					var bedlInputObject = bedlInputs[i].getElementsByTagName("input")[0] ||
						bedlInputs[i].getElementsByTagName("select")[0];

					YAHOO.util.Event.addListener(bedlInputObject, "change", BEDL.form.repeatingFields.addNewSet, bedlInputs);
				}
			}
		},

		addNewSet: function(e, bedlInputs) {
			var bedlFieldset = YAHOO.util.Dom.getAncestorByTagName(bedlInputs[0], "fieldset");
			var fieldsWrapper = YAHOO.util.Dom.getAncestorByClassName(bedlFieldset, "bedl-repeatingFields");
			var maxFieldsets = fieldsWrapper.getAttribute("data-bedl-maxfieldsets");

			//Remove listeners from the old fieldset
			BEDL.form.repeatingFields.removeInputListeners(bedlFieldset);

			var newFieldset = bedlFieldset.cloneNode(true);
			//Set the new fields to the default values
			BEDL.form.repeatingFields.setDefaultValues(newFieldset);

			var fieldsetIndex = BEDL.form.repeatingFields.setFieldsetIndex(newFieldset);
			BEDL.form.repeatingFields.setInputNames(newFieldset);

			//Don't add new listeners if maxFieldsets has been reached
			if(isNaN(maxFieldsets) || fieldsetIndex < maxFieldsets) {
				//Add listeners to the new fieldset
				BEDL.form.repeatingFields.addInputListeners(newFieldset);
			}

			//Don't add the new fieldset if maxFieldsets has already been reached
			if(isNaN(maxFieldsets) || fieldsetIndex < (1 * maxFieldsets + 1)) {
				//Add the new fieldset to the DOM
				bedlFieldset.parentNode.appendChild(newFieldset);
			}
		},

		removeInputListeners: function(bedlFieldset) {
			var bedlInputs = YAHOO.util.Dom.getElementsByClassName("bedl-input", null, bedlFieldset);
			fieldsetIndex = 1;

			for(var i = 0; i < bedlInputs.length; i++) {
				if(bedlInputs[i].getAttribute("data-bedl-addnewset")) {
					var bedlInputObject = bedlInputs[i].getElementsByTagName("input")[0] ||
						bedlInputs[i].getElementsByTagName("select")[0];

					YAHOO.util.Event.removeListener(bedlInputObject, "change", BEDL.form.repeatingFields.addNewSet);
				}
			}
		},

		setDefaultValues: function(bedlFieldset) {
			var bedlInputs = YAHOO.util.Dom.getElementsByClassName("bedl-input", null, bedlFieldset);

			for(var i = 0; i < bedlInputs.length; i++) {
				var defaultValue = bedlInputs[i].getAttribute("data-bedl-defaultvalue") || "";
				var inputFields = bedlInputs[i].getElementsByTagName("input");
				if(inputFields.length == 0) {
					inputFields = bedlInputs[i].getElementsByTagName("select");
				}

				for(var j = 0; j < inputFields.length; j++) {
					if(inputFields[j].tagName == "SELECT") {
						if(defaultValue == "") {
							//Default select value, if none specified, is 0
							inputFields[j].selectedIndex = 0;
						} else {
							//Find specified default value and set to index
							for(var k = 0; k < inputFields[j].options.length; k++) {
								if(inputFields[j].options[k].value == defaultValue) {
									inputFields[j].selectedIndex = k;
								}
							}
						}
					} else {
						//Set default values of non-select tags
						if(inputFields[j].type == "text" || inputFields[j].type == "textarea") {
							inputFields[j].value = defaultValue;
						}
					}
				}
			}
		},

		setFieldsetIndex: function(bedlFieldset) {
			var fieldsetIndex = 1;

			//Since fieldset should be a clone of the last fieldset that exists, we should
			//be able to use the existing data attribute to know the index
			fieldsetIndex = bedlFieldset.getAttribute("data-bedl-fieldsetindex");
			if(!isNaN(fieldsetIndex)) {
				fieldsetIndex++;
			}

			bedlFieldset.setAttribute("data-bedl-fieldsetindex", fieldsetIndex);

			return fieldsetIndex;
		},

		setInputNames: function(bedlFieldset) {
			var fieldsetIndex = bedlFieldset.getAttribute("data-bedl-fieldsetindex");

			var bedlInputs = YAHOO.util.Dom.getElementsByClassName("bedl-input", null, bedlFieldset);
			for(var i = 0; i < bedlInputs.length; i++) {
				var baseName = bedlInputs[i].getAttribute("data-bedl-basename");

				if(baseName) {
					var bedlLabelObject = bedlInputs[i].getElementsByTagName("label")[0];
					bedlLabelObject.setAttribute("for", baseName + fieldsetIndex);

					var bedlInputObject = bedlInputs[i].getElementsByTagName("input")[0] ||
						bedlInputs[i].getElementsByTagName("select")[0];
					bedlInputObject.setAttribute("name", baseName + fieldsetIndex);
					bedlInputObject.setAttribute("id", baseName + fieldsetIndex);
				}
			}
		}
	};
})();

YAHOO.util.Event.addListener(window, "load", BEDL.form.repeatingFields.init);