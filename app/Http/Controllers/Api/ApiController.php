<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Validator;

class ApiController extends Controller
{


    public function __construct()
    {
        set_time_limit(8000000);
    }

    public function parentCategories()
    {

        try {
            $url = 'https://banwaoghar.com/wp-json/wc/v3/products/categories?per_page=100';

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch); // if you need
            curl_close($ch);
            //return $response;

            $data = json_decode($response);

            $categories = array();

            foreach ($data as $category_data) {
                if ($category_data->parent == 0 && $category_data->id != 865 && $category_data->id != 995 && $category_data->id != 1063) {
                    $categories[] = array(
                        "category_id" => $category_data->id,
                        "category_name" => $category_data->name,
                    );
                }

            }
            return response()->json(['categories' => $categories], 200);

        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }

    }

    public function registerCustomer(Request $r)
    {

        try {

            $url = 'https://banwaoghar.com/wp-json/wc/v3/customers';

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $data = array(
                'email' => $r->email,
                'first_name' => $r->first_name,
                'last_name' => $r->last_name,
                'role' => 'customer',
                'username' => $r->username,
                'password' => $r->password,
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch); // if you need
            curl_close($ch);
            //return $response;

            return response()->json(['customer' => json_decode($response)], 200);

        } catch (\Illuminate\Database\QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 304);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }

    }

    public function getChildCategories($category_id)
    {
        try {
            $url = 'https://banwaoghar.com/wp-json/wc/v3/products/categories?per_page=100';

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch); // if you need
            curl_close($ch);
            //return $response;

            $data = json_decode($response);

            $categories = array();

            foreach ($data as $category_data) {
                if ($category_data->parent == $category_id) {
                    $categories[] = array(
                        "category_id" => $category_data->id,
                        "category_name" => $category_data->name,
                    );
                }

            }
            return response()->json(['categories' => $categories], 200);

        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function getHomeData()
    {
        try {

            set_time_limit(0);
            //deals of the day data start

            $deals_of_the_day_url = 'https://banwaoghar.com/wp-json/wc/v3/products?category=865';

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $deals_of_the_day_ch = curl_init();
            curl_setopt($deals_of_the_day_ch, CURLOPT_URL, $deals_of_the_day_url);
            curl_setopt($deals_of_the_day_ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($deals_of_the_day_ch, CURLOPT_POST, 0);
            curl_setopt($deals_of_the_day_ch, CURLOPT_RETURNTRANSFER, true);

            $deals_of_the_day_response = curl_exec($deals_of_the_day_ch);
            $err = curl_error($deals_of_the_day_ch); // if you need
            curl_close($deals_of_the_day_ch);

            $deals_of_the_day_data = json_decode($deals_of_the_day_response);

            $deals_of_the_day_data_array = array();

            foreach ($deals_of_the_day_data as $deal_of_the_day_data) {

                $deals_of_the_day_data_array[] = array(
                    "category_id" => 865,
                    "product_id" => $deal_of_the_day_data->id,
                    "product_name" => $deal_of_the_day_data->name,
                    "product_image" => $deal_of_the_day_data->images[0]->src,
                    "price" => (int) $deal_of_the_day_data->price,
                    "regular_price" => (int) $deal_of_the_day_data->regular_price,
                    "sale_price" => (int) $deal_of_the_day_data->sale_price,
                );
            }

            //deals of the day data end

            //cement data start

            $cement_url = 'https://banwaoghar.com/wp-json/wc/v3/products?category=205';

            $cement_ch = curl_init();
            curl_setopt($cement_ch, CURLOPT_URL, $cement_url);
            curl_setopt($cement_ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($cement_ch, CURLOPT_POST, 0);
            curl_setopt($cement_ch, CURLOPT_RETURNTRANSFER, true);

            $cement_response = curl_exec($cement_ch);
            $err = curl_error($cement_ch); // if you need
            curl_close($cement_ch);

            $cement_data = json_decode($cement_response);

            $cement_array = array();

            foreach ($cement_data as $cement) {

                $cement_array[] = array(
                    "category_id" => 205,
                    "product_id" => $cement->id,
                    "product_name" => $cement->name,
                    "product_image" => $cement->images[0]->src,
                    "price" => (int) $cement->price,
                    "regular_price" => (int) $cement->regular_price,
                    "sale_price" => (int) $cement->sale_price,
                );
            }

            //cement data end

            //bricks & blocks data start

            $bricks_url = 'https://banwaoghar.com/wp-json/wc/v3/products?category=207';

            $bricks_ch = curl_init();
            curl_setopt($bricks_ch, CURLOPT_URL, $bricks_url);
            curl_setopt($bricks_ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($bricks_ch, CURLOPT_POST, 0);
            curl_setopt($bricks_ch, CURLOPT_RETURNTRANSFER, true);

            $bricks_response = curl_exec($bricks_ch);
            $err = curl_error($bricks_ch); // if you need
            curl_close($bricks_ch);

            $bricks_data = json_decode($bricks_response);

            $bricks_array = array();

            foreach ($bricks_data as $brick) {

                $bricks_array[] = array(
                    "category_id" => 207,
                    "product_id" => $brick->id,
                    "product_name" => $brick->name,
                    "product_image" => $brick->images[0]->src,
                    "price" => (int) $brick->price,
                    "regular_price" => (int) $brick->regular_price,
                    "sale_price" => (int) $brick->sale_price,
                );
            }

            //bricks & blocks data end

            //paints data start

            $paint_url = 'https://banwaoghar.com/wp-json/wc/v3/products?category=208';

            $paint_ch = curl_init();
            curl_setopt($paint_ch, CURLOPT_URL, $paint_url);
            curl_setopt($paint_ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($paint_ch, CURLOPT_POST, 0);
            curl_setopt($paint_ch, CURLOPT_RETURNTRANSFER, true);

            $paint_response = curl_exec($paint_ch);
            $err = curl_error($paint_ch); // if you need
            curl_close($paint_ch);

            $paint_data = json_decode($paint_response);

            $paint_array = array();

            foreach ($paint_data as $paint) {

                $paint_array[] = array(
                    "category_id" => 208,
                    "product_id" => $paint->id,
                    "product_name" => $paint->name,
                    "product_image" => $paint->images[0]->src,
                    "price" => (int) $paint->price,
                    "regular_price" => (int) $paint->regular_price,
                    "sale_price" => (int) $paint->sale_price,
                );
            }

            //paints data end

            //home decoration data start

            $home_decoration_url = 'https://banwaoghar.com/wp-json/wc/v3/products?category=321';

            $home_decoration_ch = curl_init();
            curl_setopt($home_decoration_ch, CURLOPT_URL, $home_decoration_url);
            curl_setopt($home_decoration_ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($home_decoration_ch, CURLOPT_POST, 0);
            curl_setopt($home_decoration_ch, CURLOPT_RETURNTRANSFER, true);

            $home_decoration_response = curl_exec($home_decoration_ch);
            $err = curl_error($home_decoration_ch); // if you need
            curl_close($home_decoration_ch);

            $home_decoration_data = json_decode($home_decoration_response);

            $home_decoration_array = array();

            foreach ($home_decoration_data as $home_decoration) {

                $home_decoration_array[] = array(
                    "category_id" => 321,
                    "product_id" => $home_decoration->id,
                    "product_name" => $home_decoration->name,
                    "product_image" => $home_decoration->images[0]->src,
                    "price" => (int) $home_decoration->price,
                    "regular_price" => (int) $home_decoration->regular_price,
                    "sale_price" => (int) $home_decoration->sale_price,
                );
            }

            //home decoration data end

            $final_data = array();
            $final_data[] = array("category_name" => "Deals Of the Day", "products_data" => $deals_of_the_day_data_array);
            $final_data[] = array("category_name" => "Cement", "products_data" => $cement_array);
            $final_data[] = array("category_name" => "Bricks and Blocks", "products_data" => $bricks_array);
            $final_data[] = array("category_name" => "Paints", "products_data" => $paint_array);
            $final_data[] = array("category_name" => "Home Decoration", "products_data" => $home_decoration_array);

            return response()->json(["data" => $final_data], 200);

        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function getSpecificProduct($id)
    {

        // "description" => strip_tags($deal_of_the_day_data->description),

        try {

            //deals of the day data start

            $url = 'https://banwaoghar.com/wp-json/wc/v3/products/' . $id;

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch); // if you need
            curl_close($ch);

            $product_data = json_decode($response);

            $product["product_id"] = $product_data->id;
            $product["product_name"] = $product_data->name;
            $product["product_image"] = $product_data->images;
            $product["description"] = $product_data->description;
            $product["price"] = (int) $product_data->price;
            $product["regular_price"] = (int) $product_data->regular_price;
            $product["sale_price"] = (int) $product_data->sale_price;
            $product["stock_status"] = (int) $product_data->stock_status;

            return response()->json($product, 200);

        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function getCategoryProducts($id)
    {
        try {

            $url = 'https://banwaoghar.com/wp-json/wc/v3/products?category=' . $id;

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch); // if you need
            curl_close($ch);

            $product_data = json_decode($response);

            $product_array = array();

            foreach ($product_data as $product) {

                $product_array[] = array(
                    "category_id" => (int) $id,
                    "product_id" => $product->id,
                    "product_name" => $product->name,
                    "product_image" => $product->images[0]->src,
                    "price" => (int) $product->price,
                    "regular_price" => (int) $product->regular_price,
                    "sale_price" => (int) $product->sale_price,
                );
            }

            return response()->json(["data" => $product_array], 200);

        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function order(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'products' => 'required',
            'customer_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        try{

            $items = json_decode($request->products);

            $data["customer_id"] = $request->customer_id;
            $data["payment_method"] = "cod";
            $data["payment_method_title"] = "Cash on delivery";
            $data["set_paid"] = false;
            $data["billing"]["first_name"] = $request->first_name;
            $data["billing"]["last_name"] = $request->last_name;
            $data["billing"]["address_1"] = $request->address_1;
            $data["billing"]["city"] = $request->city;
            $data["billing"]["email"] = $request->email;
            $data["billing"]["phone"] = $request->phone;
            $data["shipping"]["first_name"] = $request->first_name;
            $data["shipping"]["last_name"] = $request->last_name;
            $data["shipping"]["address_1"] = $request->address_1;
            $data["shipping"]["city"] = $request->city;
            $data["shipping"]["email"] = $request->email;
            $data["shipping"]["phone"] = $request->phone;
            $data["line_items"] = $items;

            $url = 'https://banwaoghar.com/wp-json/wc/v3/orders';

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch); // if you need
            curl_close($ch);

            $order_data = json_decode($response);

            return response()->json(["data" => $order_data], 200);
           
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }

        // foreach (json_decode($request->products) as $area) {
        //     print_r($area); // this is your area from json response
        // }
        //return response()->json(json_decode($request->products, true));
        //print_r($request->products["line_items"][0]);
    }

    public function getAllOrders($customer_id)
    {
        try {

            $url = 'https://banwaoghar.com/wp-json/wc/v3/orders?customer=' . $customer_id;

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch); // if you need
            curl_close($ch);

            $order_data = json_decode($response);

            $order_array = array();

            foreach ($order_data as $order) {

                $order_array[] = array(
                    "order_id" => $order->id,
                    "status" => $order->status,
                    "total" => $order->total
                );
            }

            return response()->json(["data" => $order_array], 200);

        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function getOrderById($order_id)
    {
        try {

            $url = 'https://banwaoghar.com/wp-json/wc/v3/orders/' . $order_id;

            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode("ck_4f21b11f63a06d963572d390a23fd6e4bcc7ac9a:cs_ee77ee18db97a0977c74a4e9e614f4af9014776e"),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch); // if you need
            curl_close($ch);

            $order_data = json_decode($response);

            $order_array = array();

                $order_array = array(
                    "order_id" => $order_data->id,
                    "status" => $order_data->status,
                    "total" => $order_data->total,
                    "line_items" => $order_data->line_items
                );
           

            return response()->json($order_array, 200);

        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

}
