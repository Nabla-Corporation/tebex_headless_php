<?php

$webstoreIdent = "";

function SetWebstoreIdentifier($identifier) {
  global $webstoreIdent;

  $webstoreIdent = $identifier;
}

function Request($method, $route, $identifier, $path, $data = array()) {
  $baseUrl = "https://headless.tebex.io";
  $curl = curl_init();
  $url = $baseUrl . "/api/". $route. "/" . $identifier . "" . $path;
  echo $url;
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json",
    ),
  ));

  $response = json_decode(curl_exec($curl));
  curl_close($curl);
  return $response;
}

function GetCategories($includePackages = 1, $basketIdent = "", $ip_address = "") {
  global $webstoreIdent;
  return Request("GET", "accounts", $webstoreIdent, "/categories?". http_build_query(array(
    'includePackages' => $includePackages,
    'basketIdent' => $basketIdent,
    'ip_address' => $ip_address,
  )));
}


function GetCategory($category, $includePackages = 1, $basketIdent = "", $ip_address = "") {
  global $webstoreIdent;
  return Request("GET", "accounts", $webstoreIdent, "/categories/" . $category ."?". http_build_query(array(
    'includePackages' => $includePackages,
    'basketIdent' => $basketIdent,
    'ip_address' => $ip_address,
  )));
}

function Apply($basketIdent, $type, $data) {
  global $webstoreIdent;
  return Request("POST", "accounts", $webstoreIdent, "/baskets/" . $basketIdent ."/" . $type, $data);
}

function Remove($basketIdent, $type, $data) {
  global $webstoreIdent;
  return Request("POST", "accounts", $webstoreIdent, "/baskets/" . $basketIdent ."/" . $type ."/remove", $data);
}


function ApplyCoupon($basketIdent, $coupon_code) {
  return Apply($basketIdent, "coupons", array(
    "coupon_code" => $coupon_code,
  ));
}

function RemoveCoupon($basketIdent, $coupon_code) {
  return Remove($basketIdent, "coupons", array(
    "coupon_code" => $coupon_code,
  ));
}

function ApplyGiftCard($basketIdent, $card_number) {
  return Apply($basketIdent, "giftcards", array(
    "card_number" => $card_number,
  ));
}

function RemoveGiftCard($basketIdent, $card_number) {
  return Remove($basketIdent, "giftcards", array(
    "card_number" => $card_number,
  ));
}

function ApplyCreatorCode($basketIdent, $creator_code) {
  return Apply($basketIdent, "creator-codes", array(
    "creator_code" => $creator_code,
  ));
}

function RemoveCreatorCode($basketIdent, $creator_code) {
  return Remove($basketIdent, "creator-codes", array(
    "creator_code" => $creator_code,
  ));
}

function GetPackage($packageId, $basketIdent = "", $ip_address = "") {
  global $webstoreIdent;
  return Request("GET", "accounts", $webstoreIdent, "/packages/" . $packageId, array(
    'basketIdent' => $basketIdent,
    "ip_address" => $ip_address,
  ));
}

function GetPackages($basketIdent = "", $ip_address = "") {
  global $webstoreIdent;
  return Request("GET", "accounts", $webstoreIdent, "/packages", array(
    'basketIdent' => $basketIdent,
    "ip_address" => $ip_address,
  ));
}

function GetBasket($basketIdent) {
  global $webstoreIdent;
  return Request("GET", "accounts", $webstoreIdent, "/baskets/" . $basketIdent);
}

function CreateBasket($complete_url, $cancel_url, $custom, $complete_auto_redirect, $ip_address = "") {
  global $webstoreIdent;
  return Request("POST", "accounts", $webstoreIdent, "/baskets", array(
    "ip_address" => $ip_address,
    "complete_url" => $complete_url,
    "cancel_url" => $cancel_url,
    "custom" => $custom,
    "complete_auto_redirect" => $complete_auto_redirect,
  ));
}

function CreateMinecraftBasket($username, $complete_url, $cancel_url, $custom, $complete_auto_redirect) {
  global $webstoreIdent;
  return Request("POST", "accounts", $webstoreIdent, "/baskets", array(
    "username" => $username,
    "complete_url" => $complete_url,
    "cancel_url" => $cancel_url,
    "custom" => $custom,
    "complete_auto_redirect" => $complete_auto_redirect,
  ));
}

function GetBasketAuthURL($basketIdent, $returnUrl) {
  global $webstoreIdent;
  return Request("GET", "accounts", $webstoreIdent, "/baskets/" . $basketIdent . "/auth?" . http_build_query(array(
    "returnUrl" => $returnUrl,
  )));
}

function AddPackage($basketIdent, $package_id, $quantity = 1, $type = "single", $variable_data = array()) {
  return Request("POST", "baskets", $basketIdent, "/packages", array(
    "package_id" => $package_id,
    "quantity" => $quantity,
    "type" => $type,
    "variable_data" => $variable_data,
  ));
}

function GiftPackage($basketIdent, $package_id, $target_username_id) {
  return Request("POST", "baskets", $basketIdent, "/packages", array(
    "package_id" => $package_id,
    "target_username_id" => $target_username_id,
  ));
}

function RemovePackage($basketIdent, $package_id, $quantity = 1, $type = "single", $variable_data = array()) {
  return Request("POST", "baskets", $basketIdent, "/packages/remove", array(
    "package_id" => $package_id,
    "quantity" => $quantity,
    "type" => $type,
    "variable_data" => $variable_data,
  ));
}

function UpdateQuantity($basketIdent, $package_id, $quantity) {
  return Request("PUT", "baskets", $basketIdent, "/packages/" . $package_id, array(
    "quantity" => $quantity,
  ));
}

?>