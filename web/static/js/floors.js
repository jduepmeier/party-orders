/* requires config.js, common.js */

var floors = {
	refreshFloors: function() {
		var self = this;
		ajax.asyncGet(config.url + "floors/", function(xhr) {
			var overview = JSON.parse(xhr.response);

			if (overview.status == 200) {
				self.floors = overview.floors;
				self.buildFloorsTable();
				msgBoard.add({level:"info", content:"Floors geholt (" + overview.floors.length + ")"});
			} else {
				msgBoard.add(overview.error);
			}
		});
	},
	showInput: function(elem, floor) {
		elem.innerHTML = "";
		var self = this;
		var input = document.createElement('input');
		input.setAttribute('id', 'inputName' + floor.id);
		input.setAttribute('type', 'text');
		input.setAttribute('value', floor.name);
		input.addEventListener('change', function() {
			self.update(floor);
		});
		elem.appendChild(input);
	},
	buildFloorsTable: function() {
		var table = document.getElementById("floorsTable");
		var self = this;

		table.innerHTML = "";

		this.floors.forEach(function(floor) {
			var tr = document.createElement('tr');

			tr.appendChild(gui.createColumn(floor.id, "id"));
			var name = gui.createColumn(floor.name);

			var func = function() {
				name.removeEventListener('click', func);
				self.showInput(name, floor);
			}

			name.addEventListener('click', func);

			tr.appendChild(name);
			var col = document.createElement("td");
			col.appendChild(gui.createButton("Löschen", self.delete, [floor], self));
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

		ajax.asyncPut(config.url + "floors/", JSON.stringify(obj), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " +obj.id + " geändert von " + old.name + " zu " + obj.name)) {
				self.refreshFloors();
			}
		});
	},
	add: function() {
		var self = this;
		var obj = {
			name: document.getElementById('inputName').value
		};

		ajax.asyncPost(config.url + "floors/", JSON.stringify(obj), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " + obj.name + " hinzugefügt.")) {
				self.refreshFloors();
			}
		});
	},
	delete: function(floor) {
		var self = this;
		ajax.asyncDelete(config.url + "floors/", JSON.stringify(floor), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " + floor.name + " gelöscht.")) {
				self.refreshFloors();
			}
		});
	},
	init: function() {
		console.log("Hello World :)");
		this.refreshFloors();
	}
};
