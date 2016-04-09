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

		if (isset($obj) && isset($obj['id']) && isset($obj['next'])) {
			$this->changeStatus($obj);
		}

		if (isset($_GET["id"])) {
			$this->getById($_GET["id"]);
		} else {
			$this->getAll($obj);
		}
	}

	public function changeStatus($obj) {
		$sql = "UPDATE orders SET state = :next WHERE id = :id";

		$params = [
			":id" => $obj["id"],
			":next" => $obj["next"]
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

		$this->output->add("orders", $arr);
	}

	public function getAll($obj) {
		$sql = "SELECT * FROM orders";

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
