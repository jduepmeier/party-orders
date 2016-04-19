/* require config.js, common.js */

var overview = {
  orders: [],
  refreshOrders: function() {
      var self = this;
      ajax.asyncGet(config.url + "overview/", function(xhr) {
          var overview = JSON.parse(xhr.response);

          if (overview.status == 200) {
              self.orders = overview.orders;
          } else {
              msgBoard.add(overview.error);
          }
          self.buildOrderTable();
          msgBoard.add({level:"info", content:"Bestellungen geholt (" + overview.orders.length + ")"});
      });
  },
  buildOrderTable: function() {
      var table = document.getElementById("orderTable");
      var self = this;

      table.innerHTML = "";

      this.orders.forEach(function(order) {
          var tr = document.createElement('tr');

          tr.appendChild(gui.createColumn(order.id, "order_id"));
          tr.appendChild(gui.createColumn(order.timestamp));
          tr.appendChild(gui.createColumn(order.floor_name));
          tr.appendChild(gui.createColumn(order.place_name));
          tr.appendChild(gui.createColumn(order.queue_name));
          tr.appendChild(gui.createColumn(order.name));
          tr.appendChild(gui.createColumn(order.state_name));
          var col = document.createElement("td");
          if (order.next_id > 0) {
            col.appendChild(gui.createButton("-> " + order.next_name, self.nextStatus, [order], self));
          }
          tr.appendChild(col);

          table.appendChild(tr);
      });
  },
  nextStatus: function(order) {
    console.log(order);
      var obj = {
        id: order.id,
        next: order.next_id
      };

      var self = this;
      ajax.asyncPost(config.url + "orders/", JSON.stringify(obj), function(xhr) {
          var response = JSON.parse(xhr.response);
          if (response.status != 200) {
            msgBoard.add(response.error);
          } else {
            msgBoard.add(
              {level:"info", 
                content: "Status von Bestellung " + order.name + " geändert: " + order.state_name +" -> " + order.next_name
              });
          }
          self.refreshOrders();
      });
  },
  init: function() {
      console.log("Hello World :)");
      overview.refreshOrders();
  }
};
