<?php

require_once("../api.php");

class Admin {

	private $db;
	private $output;
	private $limit;
	private $orderBy;

	public function __construct() {
	}

	public function handle($db, $output, $obj) {
		$this->db = $db;
		$this->output = $output;
		$this->limit = 0;
		$this->orderBy = NULL;

		if (isset($_GET["limit"])) {
			$this->limit = $_GET["limit"];
		}

		if (isset($_GET["order"]) && !empty($_GET["order"])) {
			$this->orderBy = $_GET["order"];
		}

		if (isset($_GET["id"])) {
			$this->getById($_GET["id"]);
		} else if (isset($_GET["name"])) {
			$this->getByName($_GET["name"]);
		} else {
			$this->getAll($obj);
		}
	}

	public function getById($id) {
		$sql = "SELECT * FROM overview WHERE id = :id";

		$params = [
			":id" => $id
		];
		$arr = $this->db->query($sql, $params, DB::F_ARRAY);

		$this->output->add("orders", $arr);
	}

	public function getByName($name) {
		$sql = "SELECT * FROM overview WHERE name ILIKE :name";

		$params = [
			":name" => $name
		];
		$arr = $this->db->query($sql, $params, DB::F_ARRAY);

		$this->output->add("orders", $arr);
	}

	public function getAll($obj) {
		$sql = "SELECT * FROM overview";

		$params = [];

		if ($this->orderBy != NULL) {
			$sql .= " ORDER BY :group";
			$params[":group"] = $this->orderBy;
		}

		if ($this->limit) {
			$sql .= " LIMIT :limit";
			$params[":limit"] = $this->limit;
		}

		$arr = $this->db->query($sql, $params, DB::F_ARRAY);

		$this->output->add("orders", $arr);
	}

}

$admin = new Admin();
$c = new Controller($admin);
$c->init();

?>
