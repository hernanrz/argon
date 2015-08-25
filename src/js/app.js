/// <reference path="../typings/jquery/jquery.d.ts"/>
/**
*  Argon - Note Sharing Web App
*  Copyright (C) 2015  Hernan R.
*
*  This program is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program.  If not, see <http://www.gnu.org/licenses/gpl.html>.
*/

var Ar = {
	components: {
		UID: '',
		key: '',
		title: '',
		content: '',
		private: '',
		/**
		 * @param Object element The element to link
		 * @param String the name of the property to link it to
		 * @param String the name of the property of the element to get/set, defaults to "value"
		 */
		link: function (element, prop, elemprop) {

			elemprop = elemprop || "value";

			Object.defineProperty(this, prop, {

				get: function () {
					return element[elemprop];
				},
				set: function(content) {
					element[elemprop] = content;
				}
			});

			return this;
		}
	}
};


function saveNote() {
		var	params = {
			'content': Ar.components.content,
			'title'  : Ar.components.title,
			'private': Number(Ar.components.private)
		},
		url = "api/v1/note/",
		ajaxSettings = {
			method: '',
			dataType: 'json'
		};

		//Check if there is an UID defined
		if(Ar.components.UID == '') {
			ajaxSettings.method = "POST";
		}else {
			ajaxSettings.method = "PUT";
			params.UID = Ar.components.UID;
			params.key = Ar.components.key;
			//Need to add the key and UID to the url if we're gonna update the ntoe
			url = url + params.UID + "/" + params.key;
		}

		ajaxSettings.data = params;

		ajaxSettings.success = function (data) {
			$("#save-btn").text(Ar.loc["save"]);
			if(Ar.components.UID === '') {
				$("#action-links").show();
			}

			/** Set the UID and Key the server returns */
			Ar.components.UID = data.note.UID;
			if(data.note.key) {
				Ar.components.key = data.note.key;

				//Change current url since we just created a note
				if(typeof history.pushState === "undefined") {
					//Ugly way of doing things
					location.href = "n/" + data.note.UID + "/" + data.note.key;
				} else {
					//Fancy way
					history.pushState({}, "", "n/" + data.note.UID + "/" + data.note.key);
				}
			}

			/** Update the UI with the values from the server */
			Ar.components.title = data.note.title;
			Ar.components.content = data.note.content;
			Ar.components.private = data.note.private;
			document.title = data.note.title + " | Argón";
		};
		$("#save-btn").text(Ar.loc["saving"] + "...");
		$.ajax(url, ajaxSettings);
}

function attemptLogin(username, password) {
	$.ajax({
		
	});
}


$(document).ready(function(){
	var title = $("#title").get(0),
	textarea = $("#text-area").get(0),
	private = $("#private-chk").get(0);

	/**
	 * Link the elements on the page to the components object
	 */
	Ar
	.components
	.link(title, "title")
	.link(textarea, "content")
	.link(private, "private", "checked");

	$("#save-btn").on("click", function() {
		saveNote();
	});

	$("#delete-link").on("click", function() {
		if(confirm(Ar.loc["delete_prompt"])) {
			var url = "api/v1/note/" + Ar.components.UID + "/" + Ar.components.key;
			$.ajax(url, {
				method: 'DELETE',
				success: function (){
					location.href = "";
				}
			});
		}
	});

	$("#share-link").on("click", function() {
		if($("#share-url").toggle().is(":visible")) {
			var current_location = location.href, url;
			//Remove the "/" at the end of the url if it's there, since it messes up the rewrite rule
			if(current_location.charAt(current_location.length - 1) === "/") {
				current_location = current_location.substr(0, current_location.length - 1);
			}

			url = current_location.substr(0, current_location.lastIndexOf("/"));

			$("#share-url-val").val(url).select();
		}
	});

	$("#share-url-val").on("click doubleclick mouseover", function() {
		$("#share-url-val").focus().select();
	});

	$("#register-link, #cancel-register").on("click", function() {
		$("#register-form, #login-form").toggleClass("hidden");
	});


});