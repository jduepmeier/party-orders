<?php

require_once("../api.php");

class Floors {

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
				} else {
					$this->getAll($obj);
				}
				break;
			case 'delete':
				if (isset($obj["id"])) {
					$this->deleteFloor($obj);
				} else {
					$this->output->setError(400, "error", "Id not given.");
				}
				break;
			case 'post':
				if (isset($obj["name"])) {
					$this->addFloor($obj);
				} else {
					$this->output->setError(400, "error", "Name not given.");
				}
				break;
			case 'put':
				if (isset($obj["name"]) && isset($obj["id"])) {
					$this->updateFloor($obj);
				} else {
					$this->output->setError(400, "error", "Name or Id not given.");
				}
				break;
		}
	}

	public function deleteFloor($obj) {
		$sql = "DELETE FROM floors WHERE id = :id";

		$params = [
			":id" => $obj["id"]
		];

		try {
			$this->db->insert($sql, [$params]);
		} catch (PDOException $e) {
			$this->output->setError(400, "error", $e->getMessage());
		}
	}

	public function updateFloor($obj) {
		$sql = "UPDATE floors SET name = :name WHERE id = :id";

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

	public function addFloor($obj) {
		$sql = "INSERT INTO floors (name) VALUES (:name)";

		$params = [
			":name" => $obj["name"]
		];

		try {
			$this->db->insert($sql, [$params]);
		} catch (PDOException $e) {
			$this->output->setError(400, "error", $e->getMessage());
		}
	}

	public function getById($id) {
		$sql = "SELECT * FROM orders WHERE id = :id";

		$params = [
			":id" => $id
		];
		$arr = $this->db->query($sql, $params, DB::F_ARRAY);

		$this->output->add("floors", $arr);
	}

	public function getAll($obj) {
		$sql = "SELECT * FROM floors";

		$params = [];

		$arr = $this->db->query($sql, $params, DB::F_ARRAY);

		$this->output->add("floors", $arr);
	}

}

$floors = new Floors();
$c = new Controller($floors);
$c->init();

?>
