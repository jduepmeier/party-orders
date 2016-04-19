<?php

require_once("../api.php");

class Places {

	private $db;
	private $output;

	public function __construct() {
	}

	public function handle($db, $output, $obj) {
		$this->db = $db;
		$this->output = $output;

		switch (strtolower($_SERVER['REQUEST_METHOD'])) {
			case 'get':
				if (isset($_GET["id"])) {
					$this->getById($_GET['id']);
				} else if (isset($_GET["floor"])) {
					$this->getByFloor($_GET["floor"]);
				} else {
					$this->getAll($obj);
				}
				break;
			case 'delete':
				if (isset($obj["id"])) {
					$this->deletePlace($obj);
				} else {
					$this->output->setError(400, "error", "Id not given.");
				}
				break;
			case 'post':
				if (isset($obj["name"]) && isset($obj["floor"])) {
					$this->addPlace($obj);
				} else {
					$this->output->setError(400, "error", "Name or floor not given.");
				}
				break;
			case 'put':
				if (isset($obj["name"]) && isset($obj["id"])) {
					$this->updatePlace($obj);
				} else {
					$this->output->setError(400, "error", "Name or Id not given.");
				}
				break;
		}
	}

	public function deletePlace($obj) {
		$sql = "DELETE FROM places WHERE id = :id";

		$params = [
			":id" => $obj["id"]
		];

		try {
			$this->db->insert($sql, [$params]);
		} catch (PDOException $e) {
			$this->output->setError(400, "error", $e->getMessage());
		}
	}

	public function updatePlace($obj) {
		$sql = "UPDATE places SET name = :name WHERE id = :id";

		$params = [
			":name" => $obj["name"],
			":id" => $obj["id"]
		];

		try {
			$this->db->insert($sql, [$params]);
		} catch (PDOException $e) {
			$this->output->setError(400, "error", $e->getMessage());
		}

	}

	public function addPlace($obj) {
		$sql = "INSERT INTO places (name, floor) VALUES (:name, :floor)";

		$params = [
			":name" => $obj["name"],
			":floor" => $obj["floor"]
		];

		try {
			$this->db->insert($sql, [$params]);
		} catch (PDOException $e) {
			$this->output->setError(400, "error", $e->getMessage());
		}
	}

	public function getById($id) {
		$sql = "SELECT * FROM places WHERE id = :id";

		$params = [
			":id" => $id
		];
		$arr = $this->db->query($sql, $params, DB::F_ARRAY);

		$this->output->add("places", $arr);
	}

	private function checkFloor($floor) {
		$sql = "SELECT * FROM floors WHERE id = :id";

		$params = [
			":id" => $floor
		];

		return count($this->db->query($sql, $params, DB::F_ARRAY)) > 0;
	}

	public function getByFloor($floor) {

		if (!$this->checkFloor($floor)) {
			$this->output->setError(400, "error", "Floor with id " . $floor . " not exist.");
			return;
		}

		$sql = "SELECT * FROM places WHERE floor = :floor";

		$params = [
			":floor" => $floor
		];

		$arr = $this->db->query($sql, $params, DB::F_ARRAY);
		$this->output->add("places", $arr);
	}

	public function getAll($obj) {
		$sql = "SELECT * FROM places";

		$params = [];

		$arr = $this->db->query($sql, $params, DB::F_ARRAY);

		$this->output->add("places", $arr);
	}

}

$places = new Places();
$c = new Controller($places);
$c->init();

?>
