/* requires config.js, common.js */

var places = {

	getFloor: function() {
		var self = this;
		ajax.asyncGet(config.url + "floors/?id=" + this.floor, function(xhr) {
			var overview = JSON.parse(xhr.response);
			if (overview.status == 200 && overview.floors.length > 0) {
				var h = document.getElementById("headline");
				h.textContent = "Floor " + overview.floors[0].name;
			}
		});
	},
	refreshPlaces: function() {
		var self = this;
		ajax.asyncGet(config.url + "places/?floor=" + this.floor, function(xhr) {
			var overview = JSON.parse(xhr.response);

			if (overview.status == 200) {
				self.places = overview.places;
				self.buildPlacesTable();
				msgBoard.add({level:"info", content:"Places geholt (" + overview.places.length + ")"});
			} else {
				msgBoard.add(overview.error);
			}
		});
	},
	showInput: function(elem, place) {
		elem.innerHTML = "";
		var self = this;
		var input = document.createElement('input');
		input.setAttribute('id', 'inputName' + place.id);
		input.setAttribute('type', 'text');
		input.setAttribute('value', place.name);
		input.addEventListener('change', function() {
			self.update(place);
		});
		elem.appendChild(input);
	},
	buildPlacesTable: function() {
		var table = document.getElementById("placesTable");
		var self = this;

		table.innerHTML = "";

		this.places.forEach(function(place) {
			var tr = document.createElement('tr');

			tr.appendChild(gui.createColumn(place.id, "id"));
			var name = gui.createColumn(place.name);

			var func = function() {
				name.removeEventListener('click', func);
				self.showInput(name, place);
			}

			name.addEventListener('click', func);

			tr.appendChild(name);
			var col = document.createElement("td");
			col.appendChild(gui.createButton("Löschen", self.delete, [place], self));
			tr.appendChild(col);

			table.appendChild(tr);
		});

		var tr = document.createElement('tr');
		tr.appendChild(gui.createColumn(""));
		var col = document.createElement('td');
		var inputName = document.createElement('input');
		inputName.setAttribute('type', 'text');
		inputName.setAttribute('placeholder', 'Name');
		inputName.setAttribute('id', 'inputName');
		col.appendChild(inputName);
		tr.appendChild(col);

		col = document.createElement('td');

		col.appendChild(gui.createButton("Hinzufügen", self.add, [], self));
		tr.appendChild(col);

		table.appendChild(tr);
	},
	update: function(old) {
		console.log(old);
		var self = this;
		var obj = {
			id: old.id,
			name: document.getElementById('inputName' + old.id).value
		};

		ajax.asyncPut(config.url + "places/", JSON.stringify(obj), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " +obj.id + " geändert von " + old.name + " zu " + obj.name)) {
				self.refreshPlaces();
			}
		});
	},
	add: function() {
		var self = this;
		var obj = {
			name: document.getElementById('inputName').value
		};

		ajax.asyncPost(config.url + "places/", JSON.stringify(obj), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " + obj.name + " hinzugefügt.")) {
				self.refreshPlaces();
			}
		});
	},
	delete: function(place) {
		var self = this;
		ajax.asyncDelete(config.url + "places/", JSON.stringify(place), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " + place.name + " gelöscht.")) {
				self.refreshPlaces();
			}
		});
	},
	init: function() {
		console.log("Hello World :)");
		if (!window.location.search) {
			msgBoard.add({level: "error", content:"Kein Floor ausgewählt"});
			return;
		}
		var search = window.location.search;
		this.floor = search.match("floor=([0-9]*)")[1];
		console.log(this.floor);

		if (!this.floor) {
			msgBoard.add({level: "error", content:"Kein Floor ausgewählt"});
			return;
		}
		this.getFloor();
		this.refreshPlaces();
	}
};
