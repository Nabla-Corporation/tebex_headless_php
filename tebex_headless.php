<?php
/** @file tebex_headless.php
 *  @brief Functions to interact with the Tebex Headless API
 *
 *  @author Blaise Lebreton
 */


/** Global webstore indentifier */
$webstoreIdent = "";

/** @brief Sets the webstore identifer
 *  @param "$identifier" The webstore identifier (https://creator.tebex.io/developers/api-keys)
 *  @return
 */
function SetWebstoreIdentifier($identifier)
{
  global $webstoreIdent;

  $webstoreIdent = $identifier;
}

/** @brief Helper function to perform requests (internal use only)
 *  @param "$method" HTTP method (PUT/POST/GET/DELETE/...)
 *  @param "$route" Route of the Tebex Headless API
 *  @param "$identifier" Identifier of the route
 *  @param "$path" Path of the request
 *  @param "$data" Data passed as post fields
 *  @return array decoded from Tebex's API
 */
function Request(string $method, string $route, string $identifier, string $path, array $data = array())
{
  $baseUrl = "https://headless.tebex.io";
  $curl = curl_init();
  $url = $baseUrl . "/api/" . $route . "/" . $identifier . "/" . $path;
  curl_setopt_array(
    $curl,
    array(
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
    )
  );

  $json = curl_exec($curl);
  $response = json_decode($json, true);
  curl_close($curl);
  return $response;
}

/** @brief Get the categories from the webstore
 *  @param "$includePackages" Set to 1 to include packages within each category.
 *  @param "$basketIdent" Provide the basket identifier if a basket has been created. This allows us to show categories to the customer that other customers may be unable to see.
 *  @param "$ip_address" An IP address can be provided with authenticated requests
 *  @return array decoded from Tebex's API
 */
function GetCategories(bool $includePackages = true, string $basketIdent = "", string $ip_address = "")
{
  global $webstoreIdent;
  return Request(
    "GET",
    "accounts",
    $webstoreIdent,
    "categories?" . http_build_query(
      array(
        'includePackages' => $includePackages,
        'basketIdent' => $basketIdent,
        'ip_address' => $ip_address,
      )
    )
  );
}

/** @brief Get a category from the webstore
 *  @param "$category" ID of the desired category
 *  @param "$includePackages" Set to 1 to include packages within each category.
 *  @param "$basketIdent" Provide the basket identifier if a basket has been created. This allows us to show categories to the customer that other customers may be unable to see.
 *  @param "$ip_address" An IP address can be provided with authenticated requests
 *  @return array decoded from Tebex's API
 */
function GetCategory(int $category, bool $includePackages = true, string $basketIdent = "", string $ip_address = "")
{
  global $webstoreIdent;
  return Request(
    "GET",
    "accounts",
    $webstoreIdent,
    "categories/" . $category . "?" . http_build_query(
      array(
        'includePackages' => $includePackages,
        'basketIdent' => $basketIdent,
        'ip_address' => $ip_address,
      )
    )
  );
}

/** @brief Helper function to apply a code (internal use only)
 *  @param "$basketIdent" Basket indentifier on which to apply the code
 *  @param "$type" Type of code
 *  @param "$data" Additional data
 *  @return array decoded from Tebex's API
 */
function Apply(string $basketIdent, string $type, array $data)
{
  global $webstoreIdent;
  return Request(
    "POST",
    "accounts",
    $webstoreIdent,
    "baskets/" . $basketIdent . "/" . $type,
    $data
  );
}

/** @brief Helper function to remove a code (internal use only)
 *  @param "$basketIdent" Basket indentifier on which to remove the code
 *  @param "$type" Type of code
 *  @param "$data" Additional data
 *  @return array decoded from Tebex's API
 */
function Remove(string $basketIdent, $type, array $data)
{
  global $webstoreIdent;
  return Request(
    "POST",
    "accounts",
    $webstoreIdent,
    "baskets/" . $basketIdent . "/" . $type . "/remove",
    $data
  );
}

/** @brief Apply a coupon on the given basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$coupon_code" Code to apply
 *  @return array decoded from Tebex's API
 */
function ApplyCoupon(string $basketIdent, string $coupon_code)
{
  return Apply(
    $basketIdent,
    "coupons",
    array(
      "coupon_code" => $coupon_code,
    )
  );
}

/** @brief Remove a coupon from the given basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$coupon_code" Code to remove
 *  @return array decoded from Tebex's API
 */
function RemoveCoupon(string $basketIdent, string $coupon_code)
{
  return Remove(
    $basketIdent,
    "coupons",
    array(
      "coupon_code" => $coupon_code,
    )
  );
}

/** @brief Apply a gift card on the given basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$card_number" Code to apply
 *  @return array decoded from Tebex's API
 */
function ApplyGiftCard(string $basketIdent, string $card_number)
{
  return Apply(
    $basketIdent,
    "giftcards",
    array(
      "card_number" => $card_number,
    )
  );
}

/** @brief Remove a gift card from the given basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$card_number" Code to remove
 *  @return array decoded from Tebex's API
 */
function RemoveGiftCard(string $basketIdent, string $card_number)
{
  return Remove(
    $basketIdent,
    "giftcards",
    array(
      "card_number" => $card_number,
    )
  );
}

/** @brief Apply a creator code on the given basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$creator_code" Code to apply
 *  @return array decoded from Tebex's API
 */
function ApplyCreatorCode(string $basketIdent, string $creator_code)
{
  return Apply(
    $basketIdent,
    "creator-codes",
    array(
      "creator_code" => $creator_code,
    )
  );
}

/** @brief Remove a creator code from the given basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$creator_code" Code to remove
 *  @return array decoded from Tebex's API
 */
function RemoveCreatorCode(string $basketIdent, string $creator_code)
{
  return Remove(
    $basketIdent,
    "creator-codes",
    array(
      "creator_code" => $creator_code,
    )
  );
}

/** @brief Get informations on a package
 *  @param "$packageId" Package
 *  @param "$basketIdent" Basket indentifier
 *  @param "$ip_address" An IP address can be provided with authenticated requests
 *  @return array decoded from Tebex's API
 */
function GetPackage(string $packageId, string $basketIdent = "", string $ip_address = "")
{
  global $webstoreIdent;
  return Request(
    "GET",
    "accounts",
    $webstoreIdent,
    "packages/" . $packageId,
    array(
      'basketIdent' => $basketIdent,
      "ip_address" => $ip_address,
    )
  );
}

/** @brief Get informations on all packages
 *  @param "$basketIdent" Basket indentifier
 *  @param "$ip_address" An IP address can be provided with authenticated requests
 *  @return array decoded from Tebex's API
 */
function GetPackages(string $basketIdent = "", string $ip_address = "")
{
  global $webstoreIdent;
  return Request(
    "GET",
    "accounts",
    $webstoreIdent,
    "packages",
    array(
      'basketIdent' => $basketIdent,
      "ip_address" => $ip_address,
    )
  );
}

/** @brief Get basket data
 *  @param "$basketIdent" Basket indentifier
 *  @return array decoded from Tebex's API
 */
function GetBasket(string $basketIdent)
{
  global $webstoreIdent;
  return Request(
    "GET",
    "accounts",
    $webstoreIdent,
    "baskets/" . $basketIdent
  );
}

/** @brief Create a basket
 *  @param "$complete_url" URL where the client will be redirected after purchase
 *  @param "$cancel_url" URL where the client will be redirected after cancelling
 *  @param "$custom" Data passed to the urls
 *  @param "$complete_auto_redirect" Redirect to the complete_url on completion
 *  @param "$ip_address" An IP address can be provided with authenticated requests
 *  @return array decoded from Tebex's API
 */
function CreateBasket(string $complete_url, $cancel_url, array $custom, bool $complete_auto_redirect, string $ip_address = "")
{
  global $webstoreIdent;
  return Request(
    "POST",
    "accounts",
    $webstoreIdent,
    "baskets",
    array(
      "ip_address" => $ip_address,
      "complete_url" => $complete_url,
      "cancel_url" => $cancel_url,
      "custom" => $custom,
      "complete_auto_redirect" => $complete_auto_redirect,
    )
  );
}

/** @brief Create a minecraft basket
 *  @param "$username" URL where the client will be redirected after purchase
 *  @param "$complete_url" URL where the client will be redirected after purchase
 *  @param "$cancel_url" URL where the client will be redirected after cancelling
 *  @param "$custom" Data passed to the urls
 *  @param "$complete_auto_redirect" Redirect to the complete_url on completion
 *  @return array decoded from Tebex's API
 */
function CreateMinecraftBasket(string $username, string $complete_url, string $cancel_url, array $custom, bool $complete_auto_redirect)
{
  global $webstoreIdent;
  return Request(
    "POST",
    "accounts",
    $webstoreIdent,
    "baskets",
    array(
      "username" => $username,
      "complete_url" => $complete_url,
      "cancel_url" => $cancel_url,
      "custom" => $custom,
      "complete_auto_redirect" => $complete_auto_redirect,
    )
  );
}

/** @brief Get the basket authentication URL
 *  @param "$basketIdent" Basket indentifier
 *  @param "$returnUrl" URL you would like to redirect the user to after successful basket authentication
 *  @return array decoded from Tebex's API
 */
function GetBasketAuthURL(string $basketIdent, string $returnUrl)
{
  global $webstoreIdent;
  return Request(
    "GET",
    "accounts",
    $webstoreIdent,
    "baskets/" . $basketIdent . "/auth?" . http_build_query(
      array(
        "returnUrl" => $returnUrl,
      )
    )
  );
}

/** @brief Add a package to the specified basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$package_id" Package ID
 *  @param "$quantity" Quantity
 *  @param "$type" Type of package : single / subscription
 *  @param "$variable_data" Data needed by package (for variable products)
 *  @return array decoded from Tebex's API
 */
function AddPackage(string $basketIdent, string $package_id, int $quantity = 1, string $type = "single", array $variable_data = array())
{
  return Request(
    "POST",
    "baskets",
    $basketIdent,
    "packages",
    array(
      "package_id" => $package_id,
      "quantity" => $quantity,
      "type" => $type,
      "variable_data" => $variable_data,
    )
  );
}

/** @brief Add a package to the specified basket as a gift
 *  @param "$basketIdent" Basket indentifier
 *  @param "$package_id" Package ID
 *  @param "$target_username_id" Username of the player to give the package to
 *  @return array decoded from Tebex's API
 */
function GiftPackage(string $basketIdent, string $package_id, string $target_username_id)
{
  return Request(
    "POST",
    "baskets",
    $basketIdent,
    "packages",
    array(
      "package_id" => $package_id,
      "target_username_id" => $target_username_id,
    )
  );
}

/** @brief Remove a package from the specified basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$package_id" Package ID
 *  @return array decoded from Tebex's API
 */
function RemovePackage(string $basketIdent, string $package_id)
{
  return Request(
    "POST",
    "baskets",
    $basketIdent,
    "packages/remove",
    array(
      "package_id" => $package_id,
    )
  );
}

/** @brief Update the package quantity in the specified basket
 *  @param "$basketIdent" Basket indentifier
 *  @param "$package_id" Package ID
 *  @param "$quantity" New quantity
 *  @return array decoded from Tebex's API
 */
function UpdateQuantity(string $basketIdent, string $package_id, string $quantity)
{
  return Request(
    "PUT",
    "baskets",
    $basketIdent,
    "packages/" . $package_id,
    array(
      "quantity" => $quantity,
    )
  );
}

/** @brief Get webstore data
 *  @return array decoded from Tebex's API
 */
function GetWebstore()
{
  global $webstoreIdent;
  return Request(
    "GET",
    "baskets",
    $webstoreIdent,
    ""
  );
}