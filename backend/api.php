<?php

require_once("output.php");
require_once("pdo_sqlite.php");

//$dbpath = "host=localhost;port=5432;dbname=unifest_counters;user=unifest_api;password=unifestTest"; # postgres
$dbpath = "/var/db/unifest-orders.db3"; # sqlite

class Controller {

	private static $controller;
	private $db;
	private $output;
	private $user;
	private $module;

	/**
	 * $module = management module
	 */
	function __construct($module) {
		$this->module = $module;
	}

	public function init() {

		// init
		$this->output = Output::getInstance();
		$this->db = new DB($this->output);

		// db connection
		if (!$this->db->connect()) {
			header("WWW-Authenticate: Basic realm=\"Unifest Order System" . $_SERVER['PHP_AUTH_USER'] . ")\"");
			header("HTTP/1.0 401 Unauthorized");

			die();
		}

		$http_raw = file_get_contents("php://input");
		$obj = json_decode($http_raw, true);

		if (isset($_SERVER["PHP_AUTH_USER"]) && !empty($_SERVER["PHP_AUTH_USER"])) {
			$obj["user"] = $_SERVER["PHP_AUTH_USER"];
		}

		if (isset($_SERVER["PHP_AUTH_PW"]) && !empty($_SERVER["PHP_AUTH_PW"])) {
			$obj["password"] = $_SERVER["PHP_AUTH_PW"];
		}

		$this->module->handle($this->db, $this->output, $obj);

		$this->output->write();
	}
}
