.echo on
.bail on

BEGIN TRANSACTION;

	CREATE TABLE floors (
		id INTEGER PRIMARY KEY UNIQUE NOT NULL,
		name TEXT UNIQUE NOT NULL
	);

	CREATE TABLE queues (
		id INTEGER PRIMARY KEY UNIQUE NOT NULL,
		name TEXT UNIQUE NOT NULL
	);

	CREATE TABLE places (
		id INTEGER PRIMARY KEY UNIQUE NOT NULL,
		floor INTEGER NOT NULL REFERENCES floor(id) ON DELETE CASCADE,
		name TEXT NOT NULL
	);

	CREATE TABLE states (
		id INTEGER PRIMARY KEY UNIQUE NOT NULL,
		name TEXT NOT NULL,
		next INTEGER REFERENCES states(id) ON DELETE SET NULL,
		next_name TEXT REFERENCES states(name) ON DELETE SET NULL
	);

	CREATE TABLE orders (
		id	INTEGER PRIMARY KEY UNIQUE NOT NULL,
		timestamp DATETIME NOT NULL DEFAULT(datetime('now')),
		queue INTEGER NOT NULL REFERENCES queues(id) ON DELETE CASCADE,
		place INTEGER NOT NULL REFERENCES places(id) ON DELETE CASCADE,
		name TEXT,
		state INTEGER NOT NULL REFERENCES states(id)
	);

	CREATE TABLE place_queues (
		queue INTEGER NOT NULL REFERENCES queues(id) ON DELETE CASCADE,
		place INTEGER NOT NULL REFERENCES places(id) ON DELETE CASCADE
	);

	CREATE VIEW places_floors AS
		SELECT floors.id AS floor_id,
			floors.name AS floor_name,
			places.id AS place_id,
			places.name AS place_name
			FROM places JOIN floors ON (places.floor = floors.id)
	;

	CREATE VIEW overview AS
		SELECT orders.id AS id,
			orders.name AS name,
			orders.timestamp AS timestamp,
			floor_id,
			floor_name,
			place_id,
			place_name,
			queues.id AS queue_id,
			queues.name AS queue_name,
			states.name AS state_name,
			states.next AS next_id,
			states.next_name AS next_name
		FROM orders
			JOIN places_floors ON (orders.place = places_floors.place_id)
			JOIN queues ON (orders.queue = queues.id)
			JOIN states ON (orders.state = states.id)
	;

COMMIT;
