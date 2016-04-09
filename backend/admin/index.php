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

		if (isset($_GET["queues"])) {
			$this->getQueues($obj);
		} else if (isset($_GET["floors"])) {
			$this->getFloors($obj);
		} else if (isset($_GET["places"])) {
			$this->getPlaces($obj);
		}
	}

	public function getQueues($obj) {
		$sql = "SELECT * FROM queues";

		$arr = $this->db->query($sql, [], DB::F_ARRAY);

		$this->output->add("queues", $arr);
	}

	public function getFloors($obj) {
		$sql = "SELECT * FROM floors";

		$arr = $this->db->query($sql, [], DB::F_ARRAY);

		$this->output->add("floors", $arr);
	}

	public function getPlaces($obj) {
		$sql = "SELECT * FROM places";

		$arr = $this->db->query($sql, [], DB::F_ARRAY);

		$this->output->add("places", $arr);
	}

}

$admin = new Admin();
$c = new Controller($admin);
$c->init();

?>
