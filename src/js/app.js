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


var components = {
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
};

function saveNote() {
		var	params = {
			'content': components.content,
			'title'  : components.title,
			'private': Number(components.private)				
		},
		url = "api/v1/note/",
		ajaxSettings = {
			method: '',
			dataType: 'json'
		};
	
		//Check if there is an UID defined
		if(components.UID == '') {
			ajaxSettings.method = "POST";
		}else {
			ajaxSettings.method = "PUT";
			params.UID = components.UID;
			params.key = components.key;
			//Need to add the key and UID to the url if we're gonna update the ntoe
			url = url + params.UID + "/" + params.key;
		}
		
		ajaxSettings.data = params;
		
		ajaxSettings.success = function (data) {
			/** Set the UID and Key the server returns */
			components.UID = data.note.UID;
			if(data.note.key) {
				components.key = data.note.key;
				
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
			components.title = data.note.title;
			components.content = data.note.content;
			components.private = data.note.private;
			document.title = data.note.title + " | Argón";
		};
		
		$.ajax(url, ajaxSettings);
}

$(document).ready(function(){
	var title = $("#title").get(0),
	textarea = $("#text-area").get(0),
	private = $("#private-chk").get(0);
	
	/**
	 * Link the elements on the page to the components object
	 */
	components
	.link(title, "title")
	.link(textarea, "content")
	.link(private, "private", "checked");
	
	$("#save-btn").on("click", function() {
		saveNote();
	});
	
	$("#delete-link").on("click", function() {
		if(confirm("¿Estás segura(o) de eliminar la nota?")) {
			var url = "api/v1/note/" + components.UID + "/" + components.key;
			$.ajax(url, {
				method: 'DELETE',
				success: function (){
					location.href = "";
				}
			});
		}
	});
});