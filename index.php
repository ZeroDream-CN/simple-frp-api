<?php
//error_reporting(0);
Header("Content-type: text/plain");
$conn = mysqli_connect("localhost", "root", "123456789", "sfrp") or die("Database error");
define("API_TOKEN", "12345667890");

// 输出禁止错误 Header
function ServerForbidden($msg) {
	Header("HTTP/1.1 403 {$msg}");
	echo json_encode(Array(
		'status' => 403,
		'message' => $msg
	), JSON_UNESCAPED_UNICODE);
	exit;
}

// 输出未找到错误 Header
function ServerNotFound($msg) {
	Header("HTTP/1.1 404 {$msg}");
	echo json_encode(Array(
		'status' => 404,
		'message' => $msg
	), JSON_UNESCAPED_UNICODE);
	exit;
}

// 输出未找到错误 Header
function ServerBadRequest($msg) {
	Header("HTTP/1.1 400 {$msg}");
	echo json_encode(Array(
		'status' => 400,
		'message' => $msg
	), JSON_UNESCAPED_UNICODE);
	exit;
}

// 输出正常消息
function LoginSuccessful($msg) {
	Header("Content-type: text/plain", true, 200);
	echo json_encode(Array(
		'status' => 200,
		'success' => true,
		'message' => $msg
	), JSON_UNESCAPED_UNICODE);
	exit;
}

// 输出正常消息
function CheckSuccessful($msg) {
	Header("Content-type: text/plain", true, 200);
	echo json_encode(Array(
		'status' => 200,
		'success' => true,
		'message' => $msg
	), JSON_UNESCAPED_UNICODE);
	exit;
}

// Json 格式消息输出
function Println($data) {
	Header("Content-type: text/plain", true, 200);
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit;
}

function getBoolean($str) {
	return $str == "true";
}

// 服务端 API 部分
// 先进行 Frps 鉴权
if(isset($_GET['apitoken']) && $_GET['apitoken'] == API_TOKEN) {
	if(isset($_GET['action'])) {
		switch($_GET['action']) {
			
			// 检查客户端是否合法
			case "checktoken":
				if(isset($_GET['user'])) {
					if(preg_match("/^[A-Za-z0-9]{1,32}$/", $_GET['user'])) {
						$userToken = mysqli_real_escape_string($conn, $_GET['user']);
						$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tokens` WHERE `token`='{$userToken}'"));
						if($rs) {
							LoginSuccessful("Login successful, welcome!");
						} else {
							ServerForbidden("Login failed");
						}
					} else {
						ServerForbidden("Invalid username");
					}
				} else {
					ServerForbidden("Username cannot be empty");
				}
				break;
			
			// 检查隧道是否合法
			case "checkproxy":
				if(isset($_GET['user'])) {
					if(preg_match("/^[A-Za-z0-9]{1,32}$/", $_GET['user'])) {
						$proxyName    = str_replace("{$_GET['user']}.", "", $_GET['proxy_name']);
						$proxyType    = $_GET['proxy_type'] ?? "tcp";
						$remotePort   = Intval($_GET['remote_port']) ?? "";
						$userToken    = mysqli_real_escape_string($conn, $_GET['user']);
						$rs           = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tokens` WHERE `token`='{$userToken}'"));
						if($rs) {
							if($proxyType == "tcp" || $proxyType == "udp" || $proxyType == "stcp" || $proxyType == "xtcp") {
								if(isset($remotePort) && preg_match("/^[0-9]{1,5}$/", $remotePort)) {
									$username = mysqli_real_escape_string($conn, $rs['username']);
									// 这里只对远程端口做限制，可根据自己的需要修改
									$rs = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `proxies` WHERE `username`='{$username}' AND `remote_port`='{$remotePort}' AND `proxy_type`='{$proxyType}'"));
									if($rs) {
										if($rs['status'] == "1") {
											ServerForbidden("Proxy banned");
										}
										CheckSuccessful("Proxy exist");
									} else {
										ServerNotFound("Proxy not found");
									}
								} else {
									ServerBadRequest("Invalid request");
								}
							} elseif($proxyType == "http" || $proxyType == "https") {
								if(isset($_GET['domain']) || isset($_GET['subdomain'])) {
									// 目前只验证域名和子域名
									$domain    = $_GET['domain'] ?? "null";
									$subdomain = $_GET['subdomain'] ?? "null";
									$username  = mysqli_real_escape_string($conn, $rs['username']);
									$domain    = mysqli_real_escape_string($conn, $domain);
									$subdomain = mysqli_real_escape_string($conn, $subdomain);
									$domainsql = (isset($_GET['domain']) && !empty($_GET['domain'])) ? "`domain`='{$domain}'" : "`subdomain`='{$subdomain}'";
									$rs        = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `proxies` WHERE `username`='{$username}' AND {$domainsql} AND `proxy_type`='{$proxyType}'"));
									if($rs) {
										if($rs['status'] == "1") {
											ServerForbidden("Proxy banned");
										}
										CheckSuccessful("Proxy exist");
									} else {
										ServerNotFound("Proxy not found");
									}
								} else {
									ServerBadRequest("Invalid request");
								}
							} else {
								ServerBadRequest("Invalid request");
							}
						} else {
							ServerNotFound("User not found");
						}
					} else {
						ServerBadRequest("Invalid request");
					}
				} else {
					ServerForbidden("Invalid username");
				}
				break;
			default:
				ServerNotFound("Undefined action");
		}
	} else {
		ServerNotFound("Invalid request");
	}
} else {
	ServerNotFound("Invalid request");
}
