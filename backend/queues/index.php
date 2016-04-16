<?php

require_once("../api.php");

class Admin {

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
					$this->deleteQueue($obj);
				} else {
					$this->output->setError(400, "error", "Id not given.");
				}
				break;
			case 'post':
				if (isset($obj["name"])) {
					$this->addQueue($obj);
				} else {
					$this->output->setError(400, "error", "Name not given.");
				}
				break;
			case 'put':
				if (isset($obj["name"]) && isset($obj["id"])) {
					$this->updateQueue($obj);
				} else {
					$this->output->setError(400, "error", "Name or Id not given.");
				}
				break;
		}
	}

	public function deleteQueue($obj) {
		$sql = "DELETE FROM queues WHERE id = :id";

		$params = [
			":id" => $obj["id"]
		];

		try {
			$this->db->insert($sql, [$params]);
		} catch (PDOException $e) {
			$this->output->setError(400, "error", $e->getMessage());
		}
	}

	public function updateQueue($obj) {
		$sql = "UPDATE queues SET name = :name WHERE id = :id";

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

	public function addQueue($obj) {
		$sql = "INSERT INTO queues (name) VALUES (:name)";

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

		$this->output->add("queues", $arr);
	}

	public function getAll($obj) {
		$sql = "SELECT * FROM queues";

		$params = [];

		$arr = $this->db->query($sql, $params, DB::F_ARRAY);

		$this->output->add("queues", $arr);
	}

}

$admin = new Admin();
$c = new Controller($admin);
$c->init();

?>
