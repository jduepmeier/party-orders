/* requires config.js, common.js */

var queues = {
	refreshQueues: function() {
		var self = this;
		ajax.asyncGet(config.url + "queues/", function(xhr) {
			var overview = JSON.parse(xhr.response);

			if (overview.status == 200) {
				self.queues = overview.queues;
				self.buildQueuesTable();
				msgBoard.add({level:"info", content:"Bestellungen geholt (" + overview.queues.length + ")"});
			} else {
				msgBoard.add(overview.error);
			}
		});
	},
	showInput: function(elem, queue) {
		elem.innerHTML = "";
		var self = this;
		var input = document.createElement('input');
		input.setAttribute('id', 'inputName' + queue.id);
		input.setAttribute('type', 'text');
		input.setAttribute('value', queue.name);
		input.addEventListener('change', function() {
			self.update(queue);
		});
		elem.appendChild(input);
	},
	buildQueuesTable: function() {
		var table = document.getElementById("queuesTable");
		var self = this;

		table.innerHTML = "";

		this.queues.forEach(function(queue) {
			var tr = document.createElement('tr');

			tr.appendChild(gui.createColumn(queue.id, "id"));
			var name = gui.createColumn(queue.name);

			var func = function() {
				name.removeEventListener('click', func);
				self.showInput(name, queue);
			}

			name.addEventListener('click', func);

			tr.appendChild(name);
			var col = document.createElement("td");
			col.appendChild(gui.createButton("Löschen", self.delete, [queue], self));
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

		ajax.asyncPut(config.url + "queues/", JSON.stringify(obj), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " +obj.id + " geändert von " + old.name + " zu " + obj.name)) {
				self.refreshQueues();
			}
		});
	},
	add: function() {
		var self = this;
		var obj = {
			name: document.getElementById('inputName').value
		};

		ajax.asyncPost(config.url + "queues/", JSON.stringify(obj), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " + obj.name + " hinzugefügt.")) {
				self.refreshQueues();
			}
		});
	},
	delete: function(queue) {
		var self = this;
		ajax.asyncDelete(config.url + "queues/", JSON.stringify(queue), function(xhr) {
			if (msgBoard.checkResponse(xhr, "Warteschlange " + queue.name + " gelöscht.")) {
				self.refreshQueues();
			}
		});
	},
	init: function() {
		console.log("Hello World :)");
		this.refreshQueues();
	}
};
