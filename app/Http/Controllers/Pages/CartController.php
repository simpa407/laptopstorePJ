<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\ProductDetail;
use App\Models\Cart;
use App\Models\Advertise;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderDetail;
use App\NL_Checkout;

//controller giỏ hàng
class CartController extends Controller
{
  //thêm sản phẩm vào giỏ hàng
  public function addCart(Request $request) {

    $product = ProductDetail::where('id',$request->id)
    ->with(['product' => function($query) {
      $query->select('id', 'name', 'image', 'sku_code');
    }])->select('id', 'product_id', 'color', 'quantity', 'sale_price', 'promotion_price', 'promotion_start_date', 'promotion_end_date')->first();

    if(!$product) {
      $data['msg'] = 'Product Not Found!';
      return response()->json($data, 404);
    }

    $oldCart = Session::has('cart') ? Session::get('cart') : NULL;
    $cart = new Cart($oldCart);
    if(!$cart->add($product, $product->id, $request->qty)) {
      $data['msg'] = 'Số lượng sản phẩm trong giỏ vượt quá số lượng sản phẩm trong kho!';
      return response()->json($data, 412);
    }
    Session::put('cart', $cart);

    $data['msg'] = "Thêm giỏ hàng thành công";
    $data['url'] = route('home_page');
    $data['response'] = Session::get('cart');

    return response()->json($data, 200);
  }

  //xóa sản phẩm khỏi giỏ hàng
  public function removeCart(Request $request) {

    $oldCart = Session::has('cart') ? Session::get('cart') : NULL;
    $cart = new Cart($oldCart);

    if(!$cart->remove($request->id)) {
      $data['msg'] = 'Sản Phẩm không tồn tại!';
      return response()->json($data, 404);
    } else {
      Session::put('cart', $cart);

      $data['msg'] = "Xóa sản phẩm thành công";
      $data['url'] = route('home_page');
      $data['response'] = Session::get('cart');

      return response()->json($data, 200);
    }
  }

  //cập nhật giỏ hàng
  public function updateCart(Request $request) {
    $oldCart = Session::has('cart') ? Session::get('cart') : NULL;
    $cart = new Cart($oldCart);
    if(!$cart->updateItem($request->id, $request->qty)) {
      $data['msg'] = 'Số lượng sản phẩm trong giỏ vượt quá số lượng sản phẩm trong kho!';
      return response()->json($data, 412);
    }
    Session::put('cart', $cart);

    $response = array(
      'id' => $request->id, //id
      'qty' => $cart->items[$request->id]['qty'], //số lượng mua
      'price' => $cart->items[$request->id]['price'], //giá ban đầu
      'salePrice' => $cart->items[$request->id]['item']->sale_price, //giá đã giảm
      'totalPrice' => $cart->totalPrice, //tổng cộng
      'totalQty' => $cart->totalQty, //tổng số lượng
      'maxQty'  =>  $cart->items[$request->id]['item']->quantity //số lượng lớn nhất
    );
    $data['response'] = $response;
    return response()->json($data, 200);
  }

  //cập nhật giao diện giỏ hàng mini trên trang chủ
  public function updateMiniCart(Request $request) {
    $oldCart = Session::has('cart') ? Session::get('cart') : NULL;
    $cart = new Cart($oldCart);
    if(!$cart->updateItem($request->id, $request->qty)) {
      $data['msg'] = 'Số lượng sản phẩm trong giỏ vượt quá số lượng sản phẩm trong kho!';
      return response()->json($data, 412);
    }
    Session::put('cart', $cart);

    $response = array(
      'id' => $request->id,
      'qty' => $cart->items[$request->id]['qty'],
      'price' => $cart->items[$request->id]['price'],
      'totalPrice' => $cart->totalPrice,
      'totalQty' => $cart->totalQty,
      'maxQty'  =>  $cart->items[$request->id]['item']->quantity
    );
    $data['response'] = $response;
    return response()->json($data, 200);
  }

  //hiển thị toàn bộ giỏ hàng
  public function showCart() {

    $advertises = Advertise::where([
      ['start_date', '<=', date('Y-m-d')],
      ['end_date', '>=', date('Y-m-d')],
      ['at_home_page', '=', false]
    ])->latest()->limit(5)->get(['id', 'title', 'image']);

    $oldCart = Session::has('cart') ? Session::get('cart') : NULL;
    $cart = new Cart($oldCart);

    return view('pages.cart')->with(['cart' => $cart, 'advertises' => $advertises]);
  }

  //hiển thị thanh toán
  public function showCheckout(Request $request)
  {
    if(Auth::check() && !Auth::user()->admin) {
      if($request->has('type') && $request->type == 'buy_now') {
        $payment_methods = PaymentMethod::select('id', 'name', 'describe')->get();
        $product = ProductDetail::where('id',$request->id)
          ->with(['product' => function($query) {
            $query->select('id', 'name', 'image', 'sku_code');
          }])->select('id', 'product_id', 'color', 'quantity', 'sale_price', 'promotion_price', 'promotion_start_date', 'promotion_end_date')->first();
        $cart = new Cart(NULL);
        if(!$cart->add($product, $product->id, $request->qty)) {
          return back()->with(['alert' => [
              'type' => 'warning',
              'title' => 'Thông Báo',
              'content' => 'Số lượng sản phẩm trong giỏ vượt quá số lượng sản phẩm trong kho!'
          ]]);
        }
        return view('pages.checkout')->with(['cart' => $cart, 'payment_methods' => $payment_methods, 'buy_method' =>$request->type]);
      } elseif($request->has('type') && $request->type == 'buy_cart') {

        $payment_methods = PaymentMethod::select('id', 'name', 'describe')->get();
        $oldCart = Session::has('cart') ? Session::get('cart') : NULL;
        $cart = new Cart($oldCart);
        $cart->update();
        Session::put('cart', $cart);
        return view('pages.checkout')->with(['cart' => $cart, 'payment_methods' => $payment_methods, 'buy_method' =>$request->type]);
      }
    } elseif(Auth::check() && Auth::user()->admin) {
      return redirect()->route('home_page')->with(['alert' => [
        'type' => 'error',
        'title' => 'Thông Báo',
        'content' => 'Bạn không có quyền truy cập vào trang này!'
      ]]);
    } else {
      return redirect()->route('login')->with(['alert' => [
        'type' => 'info',
        'title' => 'Thông Báo',
        'content' => 'Bạn hãy đăng nhập để mua hàng!'
      ]]);
    }
  }

  //tiến hành thanh toán
  public function payment(Request $request) {
    $payment_method = PaymentMethod::select('id', 'name')->where('id', $request->payment_method)->first();
    if($request->buy_method == 'buy_now'){
      $order = new Order;
      $order->user_id = Auth::user()->id;
      $order->payment_method_id = $request->payment_method;
      $order->order_code = 'PSO'.str_pad(rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT);
      $order->name = $request->name;
      $order->email = $request->email;
      $order->phone = $request->phone;
      $order->address = $request->address;
      $order->status = 1;
      $order->save();

      $order_details = new OrderDetail;
      $order_details->order_id = $order->id;
      $order_details->product_detail_id = $request->product_id;
      $order_details->quantity = $request->totalQty;
      $order_details->price = $request->price;
      $order_details->save();

      $product = ProductDetail::find($request->product_id);
      $product->quantity = $product->quantity - $request->totalQty;
      $product->save();

      return redirect()->route('home_page')->with(['alert' => [
        'type' => 'success',
        'title' => 'Mua hàng thành công',
        'content' => 'Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi. Sản phẩm của bạn sẽ được chuyển đến trong thời gian sớm nhất.'
      ]]);
    } elseif ($request->buy_method == 'buy_cart') {
      $cart = Session::get('cart');

      $order = new Order;
      $order->user_id = Auth::user()->id;
      $order->payment_method_id = $request->payment_method;
      $order->order_code = 'PSO'.str_pad(rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT);
      $order->name = $request->name;
      $order->email = $request->email;
      $order->phone = $request->phone;
      $order->address = $request->address;
      $order->status = 1;
      $order->save();

      foreach ($cart->items as $key => $item) {
        $order_details = new OrderDetail;
        $order_details->order_id = $order->id;
        $order_details->product_detail_id = $item['item']->id;
        $order_details->quantity = $item['qty'];
        $order_details->price = $item['price'];
        $order_details->save();

        $product = ProductDetail::find($item['item']->id);
        $product->quantity = $product->quantity - $item['qty'];
        $product->save();
      }
      Session::forget('cart');
      return redirect()->route('home_page')->with(['alert' => [
        'type' => 'success',
        'title' => 'Mua hàng thành công',
        'content' => 'Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi. Sản phẩm của bạn sẽ được chuyển đến trong thời gian sớm nhất.'
      ]]);
    }
  }
}