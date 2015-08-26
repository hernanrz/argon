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
	user_session_token: false,
	session_started: true,
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

/**
*	Requests a new session token for the user
*/
function attemptLogin(username, password) {
	Ar.components.auth_header = username + ":" + password;
	Ar.current_username = username;
	$.ajax("api/v1/session", {
		type: "POST",
		dataType: 'json',
		beforeSend: function (request) {
			request.setRequestHeader("X-Auth-Key", Ar.components.auth_header);
			Ar.components.auth_header = null;
		},
		error: function(data) {
				$(".status-box").removeClass("hidden");
				$("#ajax-message").text(data.responseJSON.status);
		},
		success: function(data) {
			var token_pair = Ar.current_username + ":" + data.session_token;
			localStorage.setItem("user_session_token", token_pair);
			Ar.user_session_token = token_pair;
			initSession();
		}
	});
}

/**
*	Displays user's dashboard, notes, and others
*/
function initSession() {
	Ar.current_username = Ar.user_session_token.split(":")[0];
	Ar.session_started = true;
	
	//Add session header to all requests from now on
	$.ajaxSetup({
		beforeSend: function (req) {
			req.setRequestHeader("X-Session-Token", Ar.user_session_token);
		}
	});
	
	$("#forms, #user_dashboard").toggleClass("hidden");
	
	$("#sb_username").text(Ar.current_username);
	
	$.ajax("api/v1/user/notes", {
		dataType: "json"
	});
}

/**
* Deletes all session data stored in the browser
*/
function flushSession() {
	localStorage.removeItem("user_session_token");
	Ar.current_username = undefined;
	Ar.user_session_token = undefined;
	Ar.session_started = false;
	$("#forms, #user_dashboard").toggleClass("hidden");
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

	if((Ar.user_session_token = localStorage.getItem("user_session_token")) && $("#sidebar").is(":visible")) {
		initSession();
	}

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

	$("#login-button").on("click", function() {
		attemptLogin($("#login-username").val(), $("#login-password").val());
	})

});