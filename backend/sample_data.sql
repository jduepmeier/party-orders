BEGIN TRANSACTION;

INSERT INTO "states" VALUES(1,'neu',2,'unterwegs');
INSERT INTO "states" VALUES(2,'unterwegs',3,'angekommen');
INSERT INTO "states" VALUES(3,'angekommen',NULL,NULL);

INSERT INTO "floors" VALUES(1,'Audimax');
INSERT INTO "floors" VALUES(2,'AKK Halle');
INSERT INTO "floors" VALUES(3,'Lernzentrum');
INSERT INTO "floors" VALUES(4,'Festsaal');

INSERT INTO "queues" VALUES(1,'Spülkuche');
INSERT INTO "queues" VALUES(2,'Zentrale');
INSERT INTO "queues" VALUES(3,'Technik');
INSERT INTO "queues" VALUES(4,'Strom');

INSERT INTO "places" VALUES(1,1,'Bierstand 1');
INSERT INTO "places" VALUES(2,1,'Bierstand 2');
INSERT INTO "places" VALUES(3,1,'Cocktailstand 1');
INSERT INTO "places" VALUES(4,1,'Cocktailstand 2');
INSERT INTO "places" VALUES(5,2,'Cocktailstand');
INSERT INTO "places" VALUES(6,2,'Bierstand');
INSERT INTO "places" VALUES(7,3,'Bierstand');
INSERT INTO "places" VALUES(8,3,'Cocktailstand');
INSERT INTO "places" VALUES(9,4,'Cocktailstand');
INSERT INTO "places" VALUES(10,4,'Bierstand');

INSERT INTO "place_queues" VALUES(1,1);
INSERT INTO "place_queues" VALUES(1,2);
INSERT INTO "place_queues" VALUES(1,3);
INSERT INTO "place_queues" VALUES(1,4);
INSERT INTO "place_queues" VALUES(1,5);
INSERT INTO "place_queues" VALUES(1,6);
INSERT INTO "place_queues" VALUES(1,7);
INSERT INTO "place_queues" VALUES(1,8);
INSERT INTO "place_queues" VALUES(1,9);
INSERT INTO "place_queues" VALUES(1,10);

INSERT INTO "orders" VALUES(1,datetime('now'),1,3,'1x Becher',1);
COMMIT;
