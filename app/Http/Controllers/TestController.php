<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AddressController;
use App\Models\SaleCare;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Group;
use App\Models\SrcPage;
use DateTime;
use PHPUnit\TextUI\Help;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\assertFalse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
// setlocale(LC_TIME, 'vi_VN.utf8');
// setlocale(LC_TIME, "vi_VN");

use Illuminate\Support\Facades\DB;
class TestController extends Controller
{
  use WithoutMiddleware;

  public function hieu()
  {
    $pageOfHieu = SrcPage::where('user_digital', 117)->where('type', 'pc')->get();
    $group = Group::find(10); //npk
    foreach ($pageOfHieu as $page) {
      if ($page->type == 'pc') {
        $this->crawlerPancakePage($page, $group);
      }
    }
  }
  public function nga()
  {
    $page = 21; // Page number
    $perPage = 10; // Records per page
    $products = DB::table('tbl_product')
      ->join('tbl_category_product', 'tbl_product.catID', '=', 'tbl_category_product.category_id')
      // ->limit(5)
      ->select('tbl_product.*','tbl_category_product.category_name as category_name')
      ->paginate($perPage, ['*'], 'page', $page);
    // dd($products);
    $dataExport[] = [
      // 'Tên' , 'mã', 'Đã đăng', 'còn hàng', 'Gía bán thường', 'Mô tả ngắn', 'Mô tả', 'Hình ảnh'
      'post_title','sku', 'Categories', 'post_content','post_excerpt', 'regular_price','stock_status','type',
    ];
      
      // dd($products);
    foreach ($products as $product)
    {
      // $listImg = '';
      // $listImg = 'https://ngathinkpad.com/wp-content/uploads/2025/' . $product->image;
      // // $listImg .= 'http://localhost/ngaWP/wp-content/uploads/2025/' . $product->image;
      // $productImgs = DB::table('tbl_images')->where('productID', $product->productID)->get();
      // if ($productImgs->count() > 0) {
      //   foreach ($productImgs as $img) {
      //     $listImg .= ',https://ngathinkpad.com/wp-content/uploads/2025/05/' . $img->imgName;
      //   }
      // }

      $dataExport[] = [
        $product->productName,
        $product->sku,
        'Laptop,' . $product->category_name,
        $product->product_desc,
        $product->moTaNgan,
        $product->price,
        'instock',
        'simple',
        // $listImg
      ];
    }

    // dd($dataExport);

  // print_r($dataExport);
  // dd($dataExport);
  return Excel::download(new UsersExport($dataExport), 'nga.csv');

  }
  
  public function tele() 
  {
    // echo 'hi';
    $strEncode = "Th\u00f4ng b\u00e1o d\u1eef li\u1ec7u t\u1eeb LadiPage\nname : Li\nphone : 0912523644\nform_item3209 : T\u00f4i mu\u1ed1n b\u00e1o gi\u00e1 qua \u0111i\u1ec7n tho\u1ea1i\nNgu\u1ed3n t\u1eeb: https:\/\/www.nongnghiepsachvn.net\/mua4-tang2?utm_source=120208585133120157&utm_campaign=120208585133100157&fbclid=IwAR0rlPJKCCmKp3bQjpV78Qju_3OLfoOK_VfYJ-jXDCOM_jbyLbhnUKmFxgA_aem_AY8k3fYevsitPWBGbMAfIikjN8cDkS4itppXbjvUmJ1u-HGgzpspTx9GCQnQlm_VGYUxmwSF6Wx75UPqSqsNJNQ-\n\u0110\u1ecba ch\u1ec9 IP: 14.160.234.108";
    $str = "Th\u00f4ng b\u00e1o d\u1eef li\u1ec7u t\u1eeb LadiPage\nname : dinh khanh dat\nphone : 0912523644\nform_item3209 : T\u00f4i mu\u1ed1n b\u00e1o gi\u00e1 qua \u0111i\u1ec7n tho\u1ea1i\nNgu\u1ed3n t\u1eeb: https:\/\/www.nongnghiepsachvn.net\/mua4-tang2?utm_source=120208585133120157&utm_campaign=120208585133100157&fbclid=IwAR0rlPJKCCmKp3bQjpV78Qju_3OLfoOK_VfYJ-jXDCOM_jbyLbhnUKmFxgA_aem_AY8k3fYevsitPWBGbMAfIikjN8cDkS4itppXbjvUmJ1u-HGgzpspTx9GCQnQlm_VGYUxmwSF6Wx75UPqSqsNJNQ-\n\u0110\u1ecba ch\u1ec9 IP: 14.160.234.108";
    // $strEncode = "<pre>Thông báo dữ liệu từ LadiPage
    // name : Li
    // phone : 0912523644
    // form_item3209 : Tôi muốn báo giá qua điện thoại
    // Nguồn từ: https://www.nongnghiepsachvn.net/mua4-tang2?utm_source=120208585133120157&utm_campaign=120208585133100157&fbclid=IwAR0rlPJKCCmKp3bQjpV78Qju_3OLfoOK_VfYJ-jXDCOM_jbyLbhnUKmFxgA_aem_AY8k3fYevsitPWBGbMAfIikjN8cDkS4itppXbjvUmJ1u-HGgzpspTx9GCQnQlm_VGYUxmwSF6Wx75UPqSqsNJNQ-
    // Địa chỉ IP: 14.160.234.108</pre>";

    $name = $phone = $mess = $src = '';
    $array = preg_split('/\r\n|\r|\n/', $str);
    
    foreach ($array as $item) {
      $arrItem = explode(":", $item);
      // dd($arrItem);
      if (count($arrItem) > 1) {
        // echo('> 1 ' . $arrItem[0] . '<br>');
        // $arrItem[0] = 'name';
        $strSw = preg_replace('/\s+/', '', $arrItem[0]);
        switch ($strSw) {
          case "name":
            // echo('name' . $arrItem[1] .'<br>');
            $name = $arrItem[1];
            break;
          case 'phone':
            // echo('phone' . $arrItem[1] . '<br>');
            $phone = $arrItem[1];
            break;
          case 'form_item3209':
            // echo('form_item3209' . $arrItem[1] . '<br>');
            $mess = $arrItem[1];
            break;
          case 'form_item3209':
            // echo('form_item3209' . $arrItem[1] . '<br>');
            $name = $arrItem[1];
            break;
          default:
            if (count($arrItem) == 3) {
              // echo('src ' . $arrItem[2] . '<br>');
              $src = $arrItem[2];
            }
            break;
        }

        
      
        // echo "<pre>";
        // print_r($arrItem);
        // echo "</pre>";
      }
    }
    // $name = $phone = $mess = $src ='';
    echo 'name: ' . $name . '<br>';
    echo 'phone: ' . $phone . '<br>';;
    echo 'mess: ' . $mess . '<br>';
    echo 'src: ' . $src . '<br>';
  }
  public function testTelephone() 
  {
    // Kiểm tra các số điện thoại mẫu
    $testNumbers = [
      "+84973409613",
      "0912345678", // đúng
      "0312345678", // đúng
      "07123456789", // sai (nhiều hơn 10 chữ số)
      "02123456789", // đúng (số cố định)
      "051234567", // sai (ít hơn 10 chữ số)
    ];

    foreach ($testNumbers as $number) {
      if ($this->isValidVietnamPhoneNumber($number)) {
          echo "$number là số điện thoại hợp lệ.\n";
          
      } else {
          echo "$number không phải là số điện thoại hợp lệ.\n";
      }

      echo "<br>";
    }
  }

  public function isValidVietnamPhoneNumber($phone) {
    // Biểu thức chính quy cho số điện thoại di động
    $mobilePattern = "/^(9|3|7|5|8|09|03|07|08|05)\d{8}$/";
    // Biểu thức chính quy cho số điện thoại cố định
    $landlinePattern = "/^(02|03|04|05|06|07|08|09|84)\d{7,8}$/";
    
    // Biểu thức chính quy cho số điện thoại di động với mã quốc gia
    $mobilePatternWithCountryCode = "/^(\+84|0084)(9|3|7|8|5)\d{8}$/";
    // Biểu thức chính quy cho số điện thoại cố định với mã quốc gia
    $landlinePatternWithCountryCode = "/^(\+84|0084)(2|3|4|5|6|7|8|9)\d{7,8}$/";
    // $customlinePattern = "/^(+84|84)\d{7,8}$/";
    if ( preg_match($mobilePatternWithCountryCode, $phone) || preg_match($mobilePatternWithCountryCode, $phone) || preg_match($mobilePattern, $phone) || preg_match($landlinePattern, $phone)) {
        return true;
    } else {
        return false;
    }
  }

  public function updateStatusOrderGHN() 
  {
    // $orders = Orders::has('shippingOrder')->whereNotIn('status', [0,3])->get();
    $orders = Orders::join('shipping_order', 'shipping_order.order_id', '=', 'orders.id')
      ->whereNotIn('orders.status', [0,3])
      ->where('shipping_order.vendor_ship', 'GHN')
      ->get('orders.*');

    foreach ($orders as $order) {
      $endpoint = "https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail" ;
      $response = Http::withHeaders(['token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55'])
        ->post($endpoint, [
          'order_code' => $order->shippingOrder->order_code,
          'token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55',
        ]);
    
      if ($response->status() == 200) {
        $content  = json_decode($response->body());
        $data     = $content->data;
        switch ($data->status) {
          case 'ready_to_pick':
            $order->status = 1;
          case 'picking':
            #chờ lây hàng
            $order->status = 1;
            break;
            
          case 'delivered':
            #hoàn tât
            $order->status = 3;
            break;

          case 'return':
            $order->status = 0;
          case 'cancel':
            $order->status = 0;
          case 'returned':
            #hoàn/huỷ
            $order->status = 0;
            break;
          
          default:
            # đang giao
            $order->status = 2;
            break;
        }
        
        $order->save();
        
        /** ko gửi thông báo nếu đơn chỉ có sp paulo */
        $notHasPaulo = Helper::hasAllPaulo($order->id_product);

        //check đơn này đã có data chưa
        $issetOrder = Helper::checkOrderSaleCare($order->id);

        // echo "$order->status $notHasPaulo";
       
        // status = 'hoàn tất', tạo data tác nghiệp sale
        if ($order->status == 3 && $notHasPaulo) {

          $orderTricho = $order->saleCare;
          $groupId = '';
          if (!empty($orderTricho->group_id) && $orderTricho->group_id == 'tricho') {
            // $assgin_user = Helper::getSaleTricho()->id;
            $assgin_user = $order->saleCare->assign_user;
            $groupId = 'tricho';
            // echo 'case 1';
          } else {
            // $assignCSKH = Helper::getAssignCSKH();
            // echo 'case 2';
            // if ($assignCSKH) {
            //   $assgin_user = $assignCSKH->id;
            //    echo 'case 2.1';
            // } else {
            //   $assgin_user = $order->assign_user;
            //   echo 'case 2.2';
            // }
            $assgin_user = 50;
          }
          
          // echo 'sisis';
         
        

          $sale = new SaleController();
          $data = [
            'id_order' => $order->id,
            'sex' => $order->sex,
            'name' => $order->name,
            'phone' => $order->phone,
            'address' => $order->address,
            'assgin' => $assgin_user,
            'group_id' => $groupId,
          ];

          if ($issetOrder || $order->id) {
            $data['old_customer'] = 1;
          }

          $request = new \Illuminate\Http\Request();
          $request->replace($data);
          $sale->save($request);
        }
      }
    }
  }

  public function crawlerPancakeTricho()
  {
    $pages = [
      'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI0MTlkYTE5Ny0xNzFkLTQyMjYtODFiMS0wNDA2OGQyZjA3NTMiLCJzZXNzaW9uX2lkIjoiUzBrQUx5UWtqVUJjcFhmcFJPMS9HUlUyT21jM0owVC9sYkFaR0pCUXdtVSIsIm5hbWUiOiJExrDGoW5nIFRodSIsImxvZ2luX3Nlc3Npb24iOm51bGwsImluZm8iOnsib3MiOm51bGwsImRldmljZV90eXBlIjozLCJjbGllbnRfaXAiOiIxNzEuMjUzLjI3LjIzOSIsImJyb3dzZXIiOjF9LCJpYXQiOjE3MTk5OTI4MTUsImZiX25hbWUiOiJExrDGoW5nIFRodSIsImZiX2lkIjoiMTM1MjI1ODA3NDIyOTMzIiwiZXhwIjoxNzI3NzY4ODE1LCJhcHBsaWNhdGlvbiI6MX0.lAn8-zAl6_GJhpmjj3Wx1305w62mSWj6fBUYY4um6Q4',
      'pages' => [
        [
          "name" => "Tricho Bacillus - 1Xô pha 10.000 lít nước",
          "link" => "https://www.facebook.com/trichobacillus",
          "id"   => "389136690940452",
          "group" => 'tricho'
        ],
        [
          "name" => "Tricho Basilus - 1 Lít Pha 1000 Lít Nước - 0986987791",
          "link" => "https://www.facebook.com/profile.php?id=61561817156259",
          "id"   => "378087158713964",
          "group" => 'tricho'
        ],
        [
          "name" => "Trichoderma Basilus - 1 Xô Pha 10.000 Lít Nước",
          "link" => "https://www.facebook.com/profile.php?id=61562087439362",
          "id"   => "381180601741468",
          "group" => 'tricho'
        ]
      ]
    ];

    // dd('hi');
    $token  = $pages['token'];

      foreach ($pages['pages'] as $key => $val) {
        $pIdPan   = $val['id'];
        $namePage = $val['name'];
        $linkPage = $val['link'];
        $endpoint = "https://pancake.vn/api/v1/pages/$pIdPan/conversations";
        $today    = strtotime(date("Y/m/d H:i"));
        $before = strtotime ( '-5 hour' , strtotime ( date("Y/m/d H:i") ) ) ;
        $before = date ( 'Y/m/d H:i' , $before );
        $before = strtotime($before);

        $endpoint = "$endpoint?type=PHONE,DATE:$before+-+$today&access_token=$token";
        $response = Http::withHeaders(['access_token' => $token])->get($endpoint);
    
        if ($response->status() == 200) {
          $content  = json_decode($response->body());
          if ($content->success) {
            $data     = $content->conversations;
            // dd($data);
            foreach ($data as $item) {
              $recentPhoneNumbers = $item->recent_phone_numbers[0];
              $mId      = $recentPhoneNumbers->m_id;
              $phone    = isset($recentPhoneNumbers) ? $recentPhoneNumbers->phone_number : '';
              $name     = isset($item->customers[0]) ? $item->customers[0]->name : '';
              $messages = isset($recentPhoneNumbers) ? $recentPhoneNumbers->m_content : '';

              $assgin_user = 0;
              // $assgin_user = Helper::getSaleTricho()->id;
              $is_duplicate = false;
              $phone = Helper::getCustomPhoneNum($phone);
              $checkSaleCareOld = Helper::checkOrderSaleCarebyPhonePageTricho($phone, $mId, $is_duplicate, $assgin_user);

              if ($name && $checkSaleCareOld) {  
                if ($assgin_user == 0) {
                  $assignSale = Helper::getSaleTricho();
                  $assgin_user = $assignSale->id;
                }

                $is_duplicate = ($is_duplicate) ? 1 : 0;
                $sale = new SaleController();
                $data = [
                  'page_link' => $linkPage,
                  'page_name' => $namePage,
                  'sex'       => 0,
                  'old_customer' => 0,
                  'address'   => '',
                  'messages'  => $messages,
                  'name'      => $name,
                  'phone'     => $phone,
                  'page_id'   => $pIdPan,
                  'text'      => 'Page ' . $namePage,
                  'chat_id'   => 'id_VUI_tricho',
                  'm_id'      => $mId,
                  'assgin'    => $assgin_user,
                  'is_duplicate' => $is_duplicate,
                  'group_id' => 'tricho'
                ];

                $request = new \Illuminate\Http\Request();
                $request->replace($data);
                $sale->save($request);
              }
            }
        }
      }

    }
  }

  public function crawlerGroup()
  {
    $groups = Group::where('status', 1);
    
    foreach ($groups->get() as $group) {

        // if($group->id != 5) {
        //     continue;
        // }
      $pages = $group->srcs;

      foreach ($pages as $page) {
        //  if ($page->id_page != 425922557281500) {
        //      continue;
        //  }
        if ($page->type == 'pc' ) {
          $this->crawlerPancakePage($page, $group);
        }
      }
    }
  }
  public function crawlerPancakePage($page, $group)
  { 
    $srcId = $page->id;
    $pIdPan = $page->id_page;
    $token  = $page->token;
    $namePage = $page->name;
    $linkPage = $page->link;
    $chatId = $group->tele_hot_data;
    echo $namePage . '<br>';

    if ( $pIdPan != '' && $token != '' && $namePage != '' && $linkPage != '' && $chatId != '') {

      $endpoint = "https://pancake.vn/api/v1/pages/$pIdPan/conversations";
      $today    = strtotime(date("Y/m/d H:i"));
      $before   = strtotime ( '-5 hour' , strtotime ( date("Y/m/d H:i") ) ) ;
      $before   = date ( 'Y/m/d H:i' , $before );
      $before   = strtotime($before);

      $endpoint = "$endpoint?type=PHONE,DATE:$before+-+$today&access_token=$token";
      $response = Http::withHeaders(['access_token' => $token])->get($endpoint);

      if ($response->status() == 200) {
        $content  = json_decode($response->body());
        if ($content->success) {
          $data     = $content->conversations;
          foreach ($data as $item) {

            try {
              $recentPhoneNumbers = $item->recent_phone_numbers[0];
              $mId      = $recentPhoneNumbers->m_id;
              
              $phone    = isset($recentPhoneNumbers) ? $recentPhoneNumbers->phone_number : '';
              $name     = isset($item->customers[0]) ? $item->customers[0]->name : '';
              $messages = isset($recentPhoneNumbers) ? $recentPhoneNumbers->m_content : '';
              $phone = Helper::getCustomPhoneNum($phone);
              $is_duplicate = $hasOldOrder = $isOldCustomer = $assgin_user = 0;
              $checkSaleCareOld = Helper::checkOrderSaleCarebyPhoneV5($phone, $mId, $is_duplicate, $hasOldOrder);
              $typeCSKH = 1;
           
              /** kiểm tra thời gian insert tin nhắn => lâu hơn 3 ngày ko nhận lại */
              $inputTime = strtotime($item->inserted_at);
              $now = time();
              $secondsIn3Days = 3 * 24 * 60 * 60;

              if ($now - $inputTime >= $secondsIn3Days) {
                //   echo "Đã quá 3 ngày";
                  continue;
              } 
              
               if (Helper::isSeeding($phone) || $phone == '0108769765') {
                // Log::channel('ladi')->info('Số điện thoại đã nằm trong danh sách spam/seeding kernel.. ' . $phone);
                continue;
              } else if ($checkSaleCareOld) {
                  // Log::channel('ladi')->info('Số điện thoại.. ' . $phone);
              }

              if ($name && $checkSaleCareOld) {
                $assignSale = Helper::assignSaleFB($hasOldOrder, $group, $phone, $typeCSKH, $isOldCustomer);
                if (!$assignSale) {
                  continue;
                }

                if ($isOldCustomer == 1) {
                  $chatId = $group->tele_cskh_data;
                }

                $assgin_user = $assignSale->id;
                $is_duplicate = ($is_duplicate) ? 1 : 0;
                $sale = new SaleController();
                $data = [
                  'page_link' => $linkPage,
                  'page_name' => $namePage,
                  'sex'       => 0,
                  'old_customer' => $isOldCustomer,
                  'address'   => '',
                  'messages'  => $messages,
                  'name'      => $name,
                  'phone'     => $phone,
                  'page_id'   => $pIdPan,
                  'text'      => 'Page ' . $namePage,
                  'chat_id'   => $chatId,
                  'm_id'      => $mId,
                  'assgin'    => $assgin_user,
                  'is_duplicate' => $is_duplicate,
                  'group_id'  => $group->id,
                  'has_old_order'  => $hasOldOrder,
                  'src_id'  => $srcId,
                  'type_TN' => $typeCSKH, 
                ];
                
                $request = new \Illuminate\Http\Request();
                $request->replace($data);
                $sale->save($request);
              }
            
            } catch (\Exception $e) {
              // return $e;
              // echo '$phone: ' . $phone;
              // dd($e);
              // return redirect()->route('home');
            }
          }
        }
      }           
    }
  }

  public function updateStatusOrderGHTK() 
  {
    $orders = Orders::join('shipping_order', 'shipping_order.order_id', '=', 'orders.id')
      ->whereNotIn('orders.status', [0,3])
      ->where('shipping_order.vendor_ship', 'GHTK')
      ->get('orders.*');

    foreach ($orders as $order) {

      $endpoint = "https://services.giaohangtietkiem.vn/services/shipment/v2/" . $order->shippingOrder->order_code;
      $token = '1L0DDGVPfiJwazxVW0s7AQiUhRH1hb7E1s63rtd';
      $response = Http::withHeaders(['token' => $token])->get($endpoint);
      $response = $response->json();

      if ($response['success']) {
        $data     = $response['order'];
        // dd($data);
        switch ($data['status']) {
          #chờ lây hàng
          case 1:
          case 2:
          case 7:
          case 12:
          case 8:
            $order->status = 1;
            break;
          #chờ lây hàng
            

          # đang giao
          case 3:
          case 10:
          case 4:
          case 9:
            $order->status = 2;       
            break;
          # đang giao
    
          #thành công
          case 5:
          // case 6:
            $order->status = 3;
            break;

          #hoàn/huỷ
          case 20:
          case 21:
          case 11:
          case -1:
            $order->status = 0;
            break;
          
          default:
            # đang giao
            $order->status = 2;
            break;
        }
        
        $order->save();
        
        /** ko gửi thông báo nếu đơn chỉ có sp paulo */
        $notHasPaulo = Helper::hasAllPaulo($order->id_product);

        //check đơn này đã có data chưa
        $issetOrder = Helper::checkOrderSaleCare($order->id);

        //getOriginal lấy trực tiếp field từ db
        // status = 3 = 'hoàn tất', tạo data tác nghiệp sale
        if ($order->getOriginal('status') == 3) {

          $orderTricho = $order->saleCare;
          $chatId = $groupId = '';
          $saleCare = $order->saleCare;

          /** dành cho những data TN và đơn hàng khi chưa nhóm group */
          if ($order->saleCare && $saleCare->group) {

            $group = $saleCare->group;
            $chatId = $group->tele_cskh_data;
            $groupId = $group->id;
            /** có tick chia đều team cskh thì chạy tìm người để phát data cskh
             *  ngược lại ko tick thì đơn của sale nào người đó care
             * nếu chọn chia đều team CSKH thì mặc định luôn có sale nhận data
             */

            // dd($group);
            if ($group->is_share_data_cskh) {
              
              $assgin_user = Helper::getAssignCskhByGroup($group, 'cskh')->id_user;
            } else {
              $assgin_user = $order->saleCare->assign_user;
              $user = $order->saleCare->user;

              //tài khoản đã khoá hoặc chặn nhận data => tìm sale khác trong nhóm
              if (!$user->is_receive_data || !$user->status) {
                $assgin_user = Helper::getAssignSaleByGroup($group, 'cskh')->id_user;
              }
            }

          } else if (!empty($orderTricho->group_id) && $orderTricho->group_id == 'tricho') {
            $groupId = 'tricho';
            
            //id_CSKH_tricho 4234584362
            $chatId = '-4286962864'; 
            $assgin_user = $order->assign_user;
          } else {
            $assgin_user = 50;
            //cskh 4128471334
            $chatId = '-4558910780';
            // $chatId = '-4128471334';
          }
          

          $typeCSKH = Helper::getTypeCSKH($order);
          $pageName = $order->saleCare->page_name;
          $pageId = $order->saleCare->page_id;
          $pageLink = $order->saleCare->page_link;

          $sale = new SaleController();
          $data = [
            'id_order' => $order->id,
            'sex' => $order->sex,
            'name' => $order->name,
            'phone' => $order->phone,
            'address' => $order->address,
            'assgin' => $assgin_user,
            'page_name' => $pageName,
            'page_id' => $pageId,
            'page_link' => $pageLink,
            'group_id' => $groupId,
            'chat_id' => $chatId,
            'type_TN' => $typeCSKH, 
            // 'old_customer' => 1
          ];

          if ($order->saleCare->src_id) {
            $data['src_id'] = $order->saleCare->src_id;
          } else if ($order->saleCare->type != 'ladi') {
            $pageSrc = SrcPage::where('id_page', $order->saleCare->page_id)->first();
            if ($pageSrc) {
              $data['src_id'] = $pageSrc->id;
            }
          }

          // dd($data);

          if ($issetOrder || $order->id) {
            $data['old_customer'] = 1;
          }

          $request = new \Illuminate\Http\Request();
          $request->replace($data);
          $sale->save($request);
        }
      }
    }
  }

   public function updateStatusOrderGhnV2() 
  {
    $orders = Orders::has('shippingOrder')->whereNotIn('status', [0,3])->get();

    foreach ($orders as $order) {

      // if ($order->id != 3304) {
      //   continue;
      // }

      $endpoint = "https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail" ;
      $response = Http::withHeaders(['token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55'])
        ->post($endpoint, [
          'order_code' => $order->shippingOrder->order_code,
          'token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55',
        ]);
    
      if ($response->status() == 200) {
        $content  = json_decode($response->body());
        $data     = $content->data;
        switch ($data->status) {
          case 'ready_to_pick':
            $order->status = 1;
          case 'picking':
            #chờ lây hàng
            $order->status = 1;
            break;
            
          case 'delivered':
            #hoàn tât
            $order->status = 3;
            break;

          case 'return':
            $order->status = 0;
          case 'cancel':
            $order->status = 0;
          case 'returned':
            #hoàn/huỷ
            $order->status = 0;
            break;
          
          default:
            # đang giao
            $order->status = 2;
            break;
        }
        
        $order->save();
        
        /** ko gửi thông báo nếu đơn chỉ có sp paulo */
        $notHasPaulo = Helper::hasAllPaulo($order->id_product);

        //check đơn này đã có data chưa
        $issetOrder = Helper::checkOrderSaleCare($order->id);

        // status = 3 = 'hoàn tất', tạo data tác nghiệp sale
        if ($order->status == 3 && $notHasPaulo) {

          $orderTricho = $order->saleCare;
          $chatId = $groupId = '';
          $saleCare = $order->saleCare;

          /** dành cho những data TN và đơn hàng khi chưa nhóm group */
          if ($order->saleCare && $saleCare->group) {

            $group = $saleCare->group;
            $chatId = $group->tele_cskh_data;
            $groupId = $group->id;
            /** có tick chia đều team cskh thì chạy tìm người để phát data cskh
             *  ngược lại ko tick thì đơn của sale nào người đó care
             * nếu chọn chia đều team CSKH thì mặc định luôn có sale nhận data
             */

            // dd($group);
            if ($group->is_share_data_cskh) {
              
              $assgin_user = Helper::getAssignCskhByGroup($group, 'cskh')->id_user;
            } else {
              $assgin_user = $order->saleCare->assign_user;
              $user = $order->saleCare->user;

              //tài khoản đã khoá hoặc chặn nhận data => tìm sale khác trong nhóm
              if (!$user->is_receive_data || !$user->status) {
                $assgin_user = Helper::getAssignSaleByGroup($group, 'cskh')->id_user;
              }
            }

          } else if (!empty($orderTricho->group_id) && $orderTricho->group_id == 'tricho') {
            $groupId = 'tricho';
            
            //id_CSKH_tricho 4234584362
            $chatId = '-4286962864'; 
            $assgin_user = $order->assign_user;
          } else {
            $assgin_user = 50;
            //cskh 4128471334
            $chatId = '-4558910780';
            // $chatId = '-4128471334';
          }
          

          $typeCSKH = Helper::getTypeCSKH($order);
          $pageName = $order->saleCare->page_name;
          $pageId = $order->saleCare->page_id;
          $pageLink = $order->saleCare->page_link;

          $sale = new SaleController();
          $data = [
            'id_order' => $order->id,
            'sex' => $order->sex,
            'name' => $order->name,
            'phone' => $order->phone,
            'address' => $order->address,
            'assgin' => $assgin_user,
            'page_name' => $pageName,
            'page_id' => $pageId,
            'page_link' => $pageLink,
            'group_id' => $groupId,
            'chat_id' => $chatId,
            'type_TN' => $typeCSKH, 
            // 'old_customer' => 1
          ];

          if ($order->saleCare->src_id) {
            $data['src_id'] = $order->saleCare->src_id;
          } else if ($order->saleCare->type != 'ladi') {
            $pageSrc = SrcPage::where('id_page', $order->saleCare->page_id)->first();
            if ($pageSrc) {
              $data['src_id'] = $pageSrc->id;
            }
          }

          // dd($data);

          if ($issetOrder || $order->id) {
            $data['old_customer'] = 1;
          }

          $request = new \Illuminate\Http\Request();
          $request->replace($data);
          $sale->save($request);
        }
      }
    }
  }

  public function parseProductString($str) 
  {
    // dd($str);
    $products = [];
    
    // Tách ra theo dấu +
    $parts = preg_split('/\s*\+\s*/', $str);

    // Kiểm tra có xN ở cuối không (hệ số nhân)
    $multi = 1;
    if (preg_match('/x(\d+)$/i', trim($str), $m)) {
        $multi = (int) $m[1];
    }

    foreach ($parts as $item) {
        // Loại bỏ hệ số nhân cuối mỗi item nếu có
        $cleanItem = preg_replace('/x\d+$/i', '', trim($item));

        // Lấy số lượng và tên sản phẩm
        if (preg_match('/^(\d+)(kg|l|)?\s*(.+)$/iu', $cleanItem, $matches)) {
            $qty = (int) $matches[1];
            $name = strtolower(trim($matches[3])); // chuẩn hóa tên sản phẩm
            $totalQty = $qty * $multi;

            // Cộng dồn nếu sản phẩm trùng
            if (isset($products[$name])) {
                $products[$name] += $totalQty;
            } else {
                $products[$name] = $totalQty;
            }
        }
    }

    $newProduct = [];
    // foreach ($products as $k => $product) {
    //   echo $k . '<br>';
    //   if ($k == '')
    // }
    // dd($products);
    return $products;
  }

  public function listProductTmp()
  {
    $list = [
      
      'xô tricho 10kg' => [
        'price' => 1440000,
        'unit' => 'Xô',
        'real_name' => 'Phân bón VL Vinakom Bomix - Tricho Bacillus Xô 10Kg'
      ],
      'xô tricho' => [
        'price' => 1440000,
        'unit' => 'Xô',
        'real_name' => 'Phân bón VL Vinakom Bomix - Tricho Bacillus Xô 10Kg'
      ],
      'tricho 10kg' => [
        'price' => 1440000,
        'unit' => 'Xô',
        'real_name' => 'Phân bón VL Vinakom Bomix - Tricho Bacillus Xô 10Kg'
      ],
      'Đạm tôm 20l' => [
        'price' => 1500000,
        'unit' => 'Can',
        'real_name' => 'Dung dịch đạm hữu cơ 20l'
      ],
      'đạm tôm 20l' => [
        'price' => 1500000,
        'unit' => 'Can',
        'real_name' => 'Dung dịch đạm hữu cơ 20l'
      ],
      
      'humic' => [
        'price' => 120000,
        'unit' => 'Gói',
        'real_name' => 'Phân bón Ogranic AB03- Humic Acid Powder Usa 1Kg (Hàng tặng không thu tiền)'
      ],
      'Siêu lớn trái' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Lớn Trái 500ml (Hàng tặng không thu tiền)'
      ],
      'siêu lớn trái' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Lớn Trái 500ml (Hàng tặng không thu tiền)'
      ],
      
      'siêu kích hoa' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Kích Hoa 500ml (Hàng tặng không thu tiền)'
      ],
      'vọt đọt' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Vọt Đọt 500ml (Hàng tặng không thu tiền)'
      ],
      'canxibo' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Canxibo 500ml (Hàng tặng không thu tiền)'
      ],
      'Canxibo 500ml' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Canxibo 500ml (Hàng tặng không thu tiền)'
      ],
      'A plus' => [
        'price' => 1350000,
        'unit' => 'Can',
        'real_name' => 'Phân bón Agroplus organic E can 5kg'
      ],
      'a plus' => [
        'price' => 1350000,
        'unit' => 'Can',
        'real_name' => 'Phân bón Agroplus organic E can 5kg'
      ],
      'xô aplus' => [
        'price' => 1350000,
        'unit' => 'Can',
        'real_name' => 'Phân bón Agroplus organic E can 5kg'
      ],
      'aplus' => [
        'price' => 1350000,
        'unit' => 'Can',
        'real_name' => 'Phân bón Agroplus organic E can 5kg'
      ],
    ];
    return $list;
  }


  public function phoneGHTK()
  {
    $phones_array = '0816547879
0345555917
0985977165
0393796914
0964250534
0835413959
0949321232
0383267183
0974467312
0823867567
0976656961
0382289810
0975939343
0986302701
0984438509
0347173317
0868025315
0384534569
0347309065
0333008205
0359471299
0918518361
0968703467
0939455434
0388349672
0979201337
0908666574
0918493423
0968761460
0366037521
0983810833
0947882467
0377265011
0986303058
0985224204
0349269689
0377088606
0941856479
0833576779
0965368107
0938482347
0325538212
0984493320
0375111096
0354133780
0975033257
0869752771
0792136002
0919180510
0977397392
0329929934
0977195255
0764418280
0916428073
0932854322
0336360857
0912396242
0915639315
0785562672
0336059231
0977166855
0817175432
0976539727
0334205208
0386860662
0334477112
0985044171
0382801420
0974330809
0967354199
0987705399
0816547879
0867585062
0352810925
0984864379
0947035757
0947490060
0987730501
0372761994
0967084747
0966588157
0988119994
0909522052
0833999391
0837781525
0942923447
0766807148
0975867617
0908655402
0867094679
0987566535
0934325074
0766981050
0339736731
0984834335
0817001736
0382482117
0985883081
0373947302
0909970289
0908502535
0344664508
0948339024
0354115364
0987994239
0937179456
0985292253
0865005148
0347501194
0354699139
0987199212
0327734228
0362057066
0833466877
0858905499
0392999210
0389062564
0366540302
0902556732
0988175480
0343085891
0922381363
0915688035
0326290072
0337205078
0325453773
0935355847
0375655833
0932724038
0822126127
0935355847
0963427533
0375034855
0909720717
0333898351
0334345057
0918183015
0977048761
0343067002
0393424591
0769822899
0797065898
0969550800
0913657837
0703739350
0982336355
0983373567
0918616434
0786752913
0383272127
0985685873
0905480977
0375159070
0879093456
0918163427
0987699188
0987964046
0935959492
0905379134
0374326873
0907152025
0941070077
0942258312
0988666962
0976397315
0387479704
0792732723
0939388763
0368058224
0968076266
0798842929
0914231223
0938243376
0343642725
0345628448
0949521669
0965838171
0981979215
0932810856
0985358358
0909584247
0366827399
0985633069
0938723266
0905176975
0377784194
0949375359
0943757079
0843506266
0931609096
0354888950
0913685377
0903332489
0984892557
0856714951
0966137779
0388360720
0985763536
0336830658
0335599016
0387986344
0906796299
0388255189
0358558126
0329608015
0336253351
0886043457
0985165325
0903766252
0987245231
0398643239
0975462477
0765922573
0346599652
0344072804
0818639632
0909695549
0974828299
0988806589
0933854134
0385658678
0937701678
0344233226
0986595595
0383189132
0388360720
0327007644
0984136762
0975033025
0973076128
0968034179
0762946748
0985717174
0976008327
0973131023
0917461216
0973303124
0972181353
0974779102
0904830036
0982645297
0374412469
0943065223
0986327345
0978863019
0866033132
0786002328
0986342221
0393446935
0978522698
0907091147
0358504264
0327040499
0917188746
0975837786
0968324385
0385838633
0398702039
0342376937
0389761739
0976418620
0369688507
0978038707
0914216316
0973034978
0981813904
0976402433
0338187607
0377554178
0385127968
0362287332
0983471585
0974455911
0911664734
0848332029
0847929979
0382525739
0376774155
0394527785
0905334389
0916442476
0392235959
0388103046
0985976493
0971458675
0386015859
0367282586
0363250497
0376365084
0985720958
0362256218
0978353536
0979663104
0914937961
0977905678
0982602842
0865555074
0868119649
0397053483
0905379134
0919419448
0975273172
0988077990
0919516656
0908223229
0837483986
0969360016
0982612333
0987940007
0937634351
0352177356
0344368062
0986115535
0917282429
0977195255
0934776752
0987170902
0915738810
0984899819
0363341618
0867585062
0343361202
0353846684
0914413279
0333908094
0973254942
0949249999
0944291128
0798932067
0834309691
0972526250
0912435309
0919236440
0388682024
0847309020
0974105401
0818677699
0973808948
0939887293
0395443113
0973877074
0978576448
0795457285
0766918443
0337724869
0975761447
0523729489
0335211979
0979184379
0333580809
0386000598
0935881976
0932861285
0905149219
0369734923
0916081904
0988931939
0903700777
0382482117
0352344252
0988448785
0334125870
0779889888
0913151227
0905334389
0933908204
0392358588
0919674860
0368032574
0336360857
0972231302
0985262602
0354678872
0364435103
0965894744
0942164711
0339890490
0972654179
0969020313
0845766766
0388723531
0975146189
0973330047
0948329427
0867492955
0919321829
0787848769
0868215292
0973865149
0989665879
0366783484
0974509132
0338056639
0984016722
0783921921
0917221122
0378524439
0364358256
0973859397
0917162612
0989900180
0938878665
0384111058
0988212763
0919912702
0328951217
0982321963
0385649989
0333388881
0965022228
0387216345
0344122079
0354266659
0905916944
0846505606
0348708986
0988351864
0978621452
0905022148
0981114308
0383804301
0913962465
0917373239
0385741743
0913166082
0916900097
0988791157
0345010249
0868457060
0976176679
0984794095
0919436356
0346705175
0396530356
0942674881
0355276956
0397406636
0909003022
0385201619
0918594025
0824790009
0353295164
0978717880
0982106066
0334927929
0975676867
0909777071
0903357194
0327646247
0973410526
0847265979
0988660075
0915667953
0812395979
0703342147
0775507811
0393406898
0344334343
0944992977
0905568835
0879231646
0367183425
0989726738
0987536246
0939803704
0393784026
0364553929
0865005148
0903198977
0977835599
0986062847
0382411227
0389134197
0354561422
0977144748
0763201859
0942730176
0792114423
0899323086
0971498701
0986302244
0375856661
0859436879
0944793429
0972550214
0947528699
0905026942
0905379134
0385838633
0971990307
0392281852
0976413274
0978771735
0363523422
0918499231
0975967395
0359471299
0933798081
0918761068
0983632950
0975521576
0984757565
0385522069
0328501329
0986803667
0368333552
0916236342
0978771735
0978667806
0917001399
0979566711
0354464552
0979722805
0935609709
0367000603
0819222183
0386249212
0852285479
0338095527
0986803667
0966374489
0974198923
0857252242
0383011304
0346769134
0356436313
0909584247
0888374894
0941336377
0972166586
0907373678
0349977095
0388107889
0961984723
0939657885
0911197141
0976675121
0348540709
0972152160
0914144128
0358426242
0971789972
0913786424
0904525419
0907954150
0987482331
0799507733
0353404677
0365305309
0941162510
0368889797
0329631689
0865005148
0932943976
0969877281
0981345327
0967534538
0847500377
0964676710
0338548128
0987954548
0978787135
0357868779
0919819583
0337821779
0868457179
0946006417
0977620203
0968808289
0977721033
0932121268
0911019252
0916675558
0917343361
0902795443
0912545226
0936358886
0363410879
0855945799
0974907771
0812082242
0966875752
0968683771
0366031640
0983687818
0832448586
0977088337
0962876976
0385559109
0787801039
0989205148
0907513032
0934737401
0974429611
0949567385
0939533323
0941321240
0986876574
0349799339
0989874360
0354847158
0326777456
0908568346
0986239243
0338187607
0834979702
0972211474
0384413928
0868887755
0984222708
0934370725
0376589485
0385265786
0868045322
0976011682
0338571790
0935909686
0935090386
0977788654
0378336630
0356381785
0344358719
0972353911
0358246769
0388326095
0979193167
0987536246
0978546063
0939883142
0343233870
0375490027
0373067031
0967602522
0988312781
0342299384
0393911200
0987801188
0918448254
0905962807
0949322852
0979106236
0983192049
0385523188
0385895162
0981749419
0868087746
0837781525
0967062535
0349182024
0979990764
0822940918
0903077677
0938878665
0768885668
0784235847
0767721176
0349269689
0947528699
0836011368
0942147123
0984997746
0349974011
0335450319
0907432491
0939549810
0972775588
0918555593
0948670767
0965394467
0918100003
0355597098
0849566599
0385171719
0378723475
0834639379
0769463506
0813103169
093531118908545
0984493226
0975830456
0389238288
0917069891
0362286161
0969150323
0764911886
0335980506
0399600710
0909624170
0934832737
0938066555
0988289149
0357809221
0972100555
0962306389
0889174015
0794815073
0908689511
0387267181
0777880049
0978667806
0988456356
0869447912
0382819339
0348218372
0912652261
0822915911
0336691267
0342852859
0354452530
0978313816
0939074623
0971166070
0339968828
0931631286
0985099797
0965029985
0938683908
0325956039
0388328824
0972535483
0375179299
0399600710
0963920069
0794523991
0866727922
0902644748
0779889888
0382877597
0847405805
0387254500
0329970721
0937082482
0944745712
0388002092
0983015461
0777793146
0336691267
0912245054
0978093529
0334188741
0395442794
0785512373
0971882748
0818055265
0933197717
0348268346
0706377112
0704477572
0919131783
0986165184
0943784385
0966972931
0982000125
0356401480
0933961902
0388068119
0978676582
0372665204
0907811237
0987072299
0986354654
0789529012
0344880422
0792131875
0971724878
0388670072
0354517245
0836264112
0988115141
0984500271
0935741205
0909970289
0978758084
0783571879
0359619109
0339426245
0366439779
0886534147
0789662877
0919436746
0984899579
0986527261
0357229395
0366659054
0909777071
0985396432
0345447593
0384277265
0987600553
0395252692
0909049190
0353174468
0909379616
0918012132
0964091392
0933313977
0944677673
0942730176
0783870349
0362213579
0396996748
0903501811
0985132716
0911846363
0365853796
0845522278
0362329499
0819997793
0918887164
0931241091
0374711715

';

      return $phones_array;
  }
  public function getPhoneArray($phones_array)
  {
      // Loại bỏ khoảng trắng thừa trong từng dòng
    
    $phones_array = preg_split("/\r\n|\n|\r/", trim($phones_array));
    $phones_array = array_map(function($phone) {
      return preg_replace('/\s+/', '', $phone); // Xoá tất cả khoảng trắng
    }, $phones_array);
    return $phones_array;
  }

    public function parseProductComboTrichoAplus($productName)
  {

    $arr = explode("+", $productName);

    $newName = '';
    foreach ($arr as $el) {
      if ($newName != '') {
        $newName .= ' + ';
      }

      // if (strpos($el, '3 xô tricho 10kg tặng 1 xô tricho 10kg') > -1) {
      //   $name = '4 xô tricho 10kg';
      //   $newName .= $name;
      // } else {
      //   $newName .= $el;
      // }

      if (strpos($el, '3 xô tricho 10kg tặng 1 aplus') > -1 || strpos($el, '3 xô tricho 10kg tặng 1 Aplus') > -1) {
        $name = '3 xô tricho 10kg + 1 aplus';
        $newName .= $name;
      } else {
        $newName .= $el;
      }
      
    }

    return $newName;
  }


  public function parseProductComboTricho($productName)
  {
    $arr = explode("+", $productName);
    // dd($arr);
    $newName = '';
    foreach ($arr as $el) {
      if ($newName != '') {
        $newName .= ' + ';
      }

      if (strpos($el, '3 xô tricho 10kg tặng 1 xô tricho 10kg') > -1) {
        $name = '4 xô tricho 10kg ';
        $newName .= $name;
      } else {
        $newName .= $el;
      }

      // if (strpos($el, 'xô tricho 10kg tặng 1 aplus') > -1) {
      //   $name = '1 xô tricho 10kg + 1 aplus';
      //   $newName .= $name;
      // } else {
      //   $newName .= $el;
      // }
       

    }
    // dd($newName);
    return $newName;
  }

  public function phoneNhattin()
  {
    $arr = '0328497759
    0933191925
    0847155548
    0983343399
    0377230045
    0969641741
    0366132040
    0935959779
    0349201087
    0979960507
    0985888116
    0375104112
    0918289092
    0981790809
    0843159160
    0355437336
    0869875745
    0862402166
    0374328002
    0827291357
    0336313256
    0973473470
    0978091862
    0382643829
    0569526668
    0916994648
    0393421925
    0399099516
    0989648985
    0915231500
    0982131324
    0963945297
    0985749088
    0357774807
    0918866955
    0387890190
    0974825119
    0375090281
    0918639478
    0962773071
    0986579887
    0987379197
    0969844815
    0903368809
    0359721749
    0978717880
    0334278533
    0986618630
    0979714851
    0376531927
    0859965479
    0889433053
    0908923882
    0374999761
    0984432117
    0375797862
    0984427264
    0368778567
    0988311139
    0375797862
    0983315215
    0986579887
    0919819583
    0964450638
    0563094450
    0989945631
    0364025373
    0965827627
    0383063942
    0349760339
    0985663546
    0988077443
    0354608289
    0979854881
    0916354870
    0394778283
    0383255969
    0947918979
    0918776096
    0326037115
    0986377912
    0966365139
    0396873222
    0369538264
    0915979010
    0865480884
    0942447474
    0935842419
    0335784214
    0917610905
    0961245886
    0985217486
    0357226593
    0862196719
    0949243213
    0842779555
    0967988838
    0384687653
    0963101683
    0372285803
    0398606216
    0333428780
    0987665860
    0985846752
    ';
    return $arr;
  }

  public function exportTaxV2()
    {
    $sale     = new OrdersController();
    $time = ['01/08/2025', '09/08/2025'];

    $timeBegin  = str_replace('/', '-', $time[0]);
    $timeEnd    = str_replace('/', '-', $time[1]);
    $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
    $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

    $list = Orders::join('shipping_order', 'shipping_order.order_id', '=', 'orders.id')
      ->where('shipping_order.vendor_ship', 'GHN')
      ->where('orders.status', 3)
      ->whereDate('orders.created_at', '>=', $dateBegin)
      ->whereDate('orders.created_at', '<=', $dateEnd);
      // ->where('orders.id', '11785');

    $dataExport[] = [
      'Số thứ tự hóa đơn (*)' , 'Ngày hóa đơn', 'Tên đơn vị mua hàng', 'Mã khách hàng', 'Địa chỉ', 'Mã số thuế', 'Người mua hàng',
      'Email', 'Hình thức thanh toán', 'Loại tiền', 'Tỷ giá', 'Tỷ lệ CK(%)', 'Tiền CK', 'Tên hàng hóa/dịch vụ (*)', 'Mã hàng', 
      'ĐVT', 'Số lượng', 'Đơn giá', 'Tỷ lệ CK (%)', 'Tiền CK', '% thuế GTGT', 'Tiền thuế GTGT', 'Thành tiền(*)'
    ];

    $i = 1;
    $orderTmp = [];
    $list = $list->get();

    foreach ($list as $data) {
      $orderTmp[] = $data->id;
      $listProduct = json_decode($data->id_product,true);

      /**
       * 1/ 1 Đạm tôm 20l
       *    3kg humic
       * 2/ 1 Đạm tôm 20l + 3kg humic
       * 3/ 1 Đạm tôm 20l + 3kg humic
       *    1kg humic
       */

       //trường hợp đơn chỉ cho 1 sp
      $percenTax = '5';
      $totalGTGT = '';
      if (count($listProduct) == 1) {
        $item = $listProduct[0];
        $product = getProductByIdHelper($item['id']);
        
        $total = $data->total;
        if (!$product) {
          continue;
        }

        $productName = $product->name;
        $k = $i;

        //check trường hợp sản phẩm cb và sản phẩm lẻ
        // có dấu + là sản phẩm combo
        if (strpos($productName, '+') !== false) {

          $tmp = [];
          if (strpos($productName, '3 xô tricho 10kg tặng 1 xô tricho 10kg') !== false) {
            $productName = $this->parseProductComboTricho($productName);
          }

          $items = $this->parseProductString($productName);
          $productTmp = [];
          $l = 0;
          // dd($items);
          foreach ($items as $key => $val)
          {
            $list = $this->listProductTmp();
            // if ($key == 'xô tricho 10kg tặng 1 xô tricho 10kg') {
            // }
            if (!isset($list[$key])) {
              continue;
            }

            $productTmp = $list[$key];
            $total = 0;

            if (!$productTmp) {
              continue;
            }

            $totalOrder = $data->total;
            $productPrice = $productTmp['price'];

            $qty = $item['val'];
            $qty = $val * $qty;
    
            if (strpos($productTmp['real_name'], "Hàng tặng") !== false ) {
              $percenTax = '../..';
              $totalGTGT = '../..';
            } else {
              /* tổng tiền bao gồm VAT 5%: 3.150.000
                số lượng: 2 sản phẩm
                thuế VAT: 5%
                b1: tổng tiền chưa VAT = 3150000/ 1.05 = 3000000 (3tr)
                b2: tính giá chưa VAT mỗi sp: 3tr /2 = 1tr5
              */
              $taxBeforeTotal = $totalOrder / 1.05;
              $taxbeforeProduct = $taxBeforeTotal / $qty;
              $productPrice = $taxbeforeProduct;
              $totalGTGT = 0.05 * $taxBeforeTotal;
              $total = $totalOrder;
            }

            if ($l == 0) {
               $total = $totalOrder;
            }
            $l++;

            if ($k != $i) {
              $tmp = [
                '',//Số thứ tự hóa đơn (*)
                '', // Ngày hóa đơn
                '',// Tên đơn vị mua hàng
                '',// Mã khách hàng
                '',// Địa chỉ
                '',// Mã số thuế
                '',// Người mua hàng
                '',// Email
                '',// Hình thức thanh toán
                '',// Loại tiền
                '',// Tỷ giá
                '',// Tỷ lệ CK(%)
                '',// Tiền CK
                $productTmp['real_name'],// Tên hàng hóa/dịch vụ (*)
                '',// Mã hàng
                $productTmp['unit'],// 'ĐVT',
                $qty,//  'Số lượng', 
                $productTmp['price'],//  'Đơn giá', 
                '',//  'Tỷ lệ CK (%)', 
                '',//  'Tiền CK',
                $percenTax, // '% thuế GTGT',
                $totalGTGT, //  'Tiền thuế GTGT',
                $total,   // 'Thành tiền(*)'
              ];
            } else {
              $tmp = [
                $i,//Số thứ tự hóa đơn (*)
                date_format($data->created_at,"d-m-Y "), // Ngày hóa đơn
                '',// Tên đơn vị mua hàng
                '',// Mã khách hàng
                $data->address,// Địa chỉ
                '',// Mã số thuế
                $data->name,// Người mua hàng
                '',// Email
                '',// Hình thức thanh toán
                '',// Loại tiền
                '',// Tỷ giá
                '',// Tỷ lệ CK(%)
                '',// Tiền CK
                $productTmp['real_name'],// Tên hàng hóa/dịch vụ (*)
                '',// Mã hàng
                $productTmp['unit'],// 'ĐVT',
                $qty,//  'Số lượng', 
                $productPrice,//  'Đơn giá', 
                '',//  'Tỷ lệ CK (%)', 
                '',//  'Tiền CK',
                $percenTax, // '% thuế GTGT',
                $totalGTGT, //  'Tiền thuế GTGT',
                $total,   // 'Thành tiền(*)'
              ];
            }
            $dataExport[] = $tmp;
            $k++;
          }
        } else {

          $totalBefore = $product->price;
          // if (strpos($product->name, "Dung dịch đạm hữu cơ") !== false
          //   || strpos($product->name, "30.10.10") !== false  
          // || strpos($product->name, "tôm") !== false) {
            // $percenTax = '5';
            if (strpos($product->name, "Hàng tặng") !== false ) {
              $percenTax = '../..';
              $totalGTGT = '../..';
            } else {
              $totalGTGT = 5 * $product->price / 100;
              $totalBefore = $total - $totalGTGT;
              $tmp = [];
            }
          // }
          // dd($total);
          if ($k != $i) {
            $tmp = [
              '',//Số thứ tự hóa đơn (*)
              '', // Ngày hóa đơn
              '',// Tên đơn vị mua hàng
              '',// Mã khách hàng
              '',// Địa chỉ
              '',// Mã số thuế
              '',// Người mua hàng
              '',// Email
              '',// Hình thức thanh toán
              '',// Loại tiền
              '',// Tỷ giá
              '',// Tỷ lệ CK(%)
              '',// Tiền CK
              $product->name,// Tên hàng hóa/dịch vụ (*)
              '',// Mã hàng
              $product->unit,// 'ĐVT',
              $item->val,//  'Số lượng', 
              $totalBefore,//  'Đơn giá', 
              '',//  'Tỷ lệ CK (%)', 
              '',//  'Tiền CK',
              $percenTax, // '% thuế GTGT',
              $totalGTGT, //  'Tiền thuế GTGT',
              $total,   // 'Thành tiền(*)'
            ];  
          } else {
            $tmp = [
            $i,//Số thứ tự hóa đơn (*)
            date_format($data->created_at,"d-m-Y "), // Ngày hóa đơn
            '',// Tên đơn vị mua hàng
              '',// Mã khách hàng
              $data->address,// Địa chỉ
              '',// Mã số thuế
              $data->name,// Người mua hàng
              '',// Email
              '',// Hình thức thanh toán
              '',// Loại tiền
              '',// Tỷ giá
              '',// Tỷ lệ CK(%)
              '',// Tiền CK
              $product->name,// Tên hàng hóa/dịch vụ (*)
              '',// Mã hàng
              $product->unit,// 'ĐVT',
              $item['val'],//  'Số lượng', 
              $totalBefore,//  'Đơn giá', 
              '',//  'Tỷ lệ CK (%)', 
              '',//  'Tiền CK',
              $percenTax, // '% thuế GTGT',
              $totalGTGT, //  'Tiền thuế GTGT',
              $total,   // 'Thành tiền(*)'
            ];
          }
          
          $dataExport[] = $tmp;
          $k++;
        }

        /** số tổng sản phẩm lớn hơn 1 */
      } else {
        $j = $i;
        $percenTax = '5';
        $totalGTGT = '';
        // dd($listProduct);
        $qtyNPK = 0;
        $isNPK = false;
        foreach ($listProduct as $item) {
          $product = getProductByIdHelper($item['id']);
          $productName = $product->name;
          $total = 0;
          $tmp = [];
          if (!$product) {
            continue;
          }
          if ($isNPK) {
            continue;
          }

          //npk       
          if ($product->id == 83) {
            $isNPK = true;
          } 
          
          if (strpos($productName, '+') !== false) {
            if (strpos($productName, '3 xô tricho 10kg tặng 1 xô tricho 10kg') !== false ) {
              $productName = $this->parseProductComboTricho($productName);
            }
            if (strpos($productName, '3 xô tricho 10kg tặng 1 aplus') !== false || strpos($productName, '3 xô tricho 10kg tặng 1 Aplus') !== false ) {
              $productName = $this->parseProductComboTrichoAplus($productName);
            }
            
            $items = $this->parseProductString($productName);
            $productTmp = [];
            $l = 0;
            $percenTax = '5';
            $totalGTGT = '';
            foreach ($items as $key => $val)
            {
              if ($key == 'xô tricho 10kg tặng 1 aplus')
                {
                  // echo $data->id . '-' . $productName;
                  dd($listProduct);
                  dd($productName);
                  dd($items);
                }
              
              $list = $this->listProductTmp();
              $productTmp = $list[$key];
              $total = 0;
              $totalOrder = $data->total;
              
              if (!$productTmp) {
                continue;
              }
              $productPrice = $productTmp['price'];
              $qty = $item['val'];
              $qty = $val * $qty;
              // if (strpos($productTmp['real_name'], "Dung dịch đạm hữu cơ") !== false || strpos($productTmp['real_name'], "tôm") !== false) {
              //   $percenTax = '5';

                /* tổng tiền bao gồm VAT 5%: 3.150.000
                  số lượng: 2 sản phẩm
                  thuế VAT: 5%
                  b1: tổng tiền chưa VAT = 3150000/ 1.05 = 3000000 (3tr)
                  b2: tính giá chưa VAT mỗi sp: 3tr /2 = 1tr5
                */

              if (strpos($productTmp['real_name'], "Hàng tặng") !== false ) {
                $percenTax = '../..';
                $totalGTGT = '../..';
              } else {
                $taxBeforeTotal = $totalOrder / 1.05;
                $taxbeforeProduct = $taxBeforeTotal / $qty;
                $productPrice = $taxbeforeProduct;
                $totalGTGT = 0.05 * $taxBeforeTotal;
                $total = $totalOrder;
              }
              // } 

              if ($l == 0) {
                $total = $totalOrder;
              }
              $l++;

              if ($j != $i) {
                $tmp = [
                  '',//Số thứ tự hóa đơn (*)
                  '', // Ngày hóa đơn
                  '',// Tên đơn vị mua hàng
                  '',// Mã khách hàng
                  '',// Địa chỉ
                  '',// Mã số thuế
                  '',// Người mua hàng
                  '',// Email
                  '',// Hình thức thanh toán
                  '',// Loại tiền
                  '',// Tỷ giá
                  '',// Tỷ lệ CK(%)
                  '',// Tiền CK
                  $productTmp['real_name'],// Tên hàng hóa/dịch vụ (*)
                  '',// Mã hàng
                  $productTmp['unit'],// 'ĐVT',
                  $qty,//  'Số lượng', 
                  $productPrice,//  'Đơn giá', 
                  '',//  'Tỷ lệ CK (%)', 
                  '',//  'Tiền CK',
                  $percenTax, // '% thuế GTGT',
                  $totalGTGT, //  'Tiền thuế GTGT',
                  $total,   // 'Thành tiền(*)'
                ];
              } else {
                $tmp = [
                  $i,//Số thứ tự hóa đơn (*)
                  date_format($data->created_at,"d-m-Y "), // Ngày hóa đơn
                  '',// Tên đơn vị mua hàng
                  '',// Mã khách hàng
                  $data->address,// Địa chỉ
                  '',// Mã số thuế
                  $data->name,// Người mua hàng
                  '',// Email
                  '',// Hình thức thanh toán
                  '',// Loại tiền
                  '',// Tỷ giá
                  '',// Tỷ lệ CK(%)
                  '',// Tiền CK
                  $productTmp['real_name'],// Tên hàng hóa/dịch vụ (*)
                  '',// Mã hàng
                  $productTmp['unit'],// 'ĐVT',
                  $qty,//  'Số lượng', 
                  $productPrice,//  'Đơn giá', 
                  '',//  'Tỷ lệ CK (%)', 
                  '',//  'Tiền CK',
                  $percenTax, // '% thuế GTGT',
                  $totalGTGT, //  'Tiền thuế GTGT',
                  $total,   // 'Thành tiền(*)'
                ];
              }
  
              $dataExport[] = $tmp;
              $j++;
            }  
          } else {
              $totalOrder = $data->total;
              $productPrice = $product->price;
              $qty = $item['val'];

              $percenTax = '5';
              $totalGTGT = '';
              if (strpos($product->name, "Hàng tặng") !== false || strpos($product->name, "Áo mưa") !== false ) {
                $percenTax = '../..';
                $totalGTGT = '../..';
              } else {
                $taxBeforeTotal = $totalOrder / 1.05;
                $taxbeforeProduct = $taxBeforeTotal / $qty;
                $productPrice = $taxbeforeProduct;
                $totalGTGT = 0.05 * $taxBeforeTotal;
                $total = $totalOrder;
              }
              // dd($totalGTGT);
              // } else 
              
            
              if ($j != $i) {
                $tmp = ['', '', '', '', '', '',  '', '','', '', '','', '', $product->name,'', $product->unit, $qty, $productPrice,
                  '', '', $percenTax, $totalGTGT, $total,   
                ];  
              } else {
                  // dd($product->name);
                $tmp = [
                $i,//Số thứ tự hóa đơn (*)
                date_format($data->created_at,"d-m-Y "), // Ngày hóa đơn
                '',// Tên đơn vị mua hàng
                  '',// Mã khách hàng
                  $data->address,// Địa chỉ
                  '',// Mã số thuế
                  $data->name,// Người mua hàng
                  '',// Email
                  '',// Hình thức thanh toán
                  '',// Loại tiền
                  '',// Tỷ giá
                  '',// Tỷ lệ CK(%)
                  '',// Tiền CK
                  $product->name,// Tên hàng hóa/dịch vụ (*)
                  '',// Mã hàng
                  $product->unit,// 'ĐVT',
                  $qty,//  'Số lượng', 
                  $productPrice,//  'Đơn giá', 
                  '',//  'Tỷ lệ CK (%)', 
                  '',//  'Tiền CK',
                  $percenTax, // '% thuế GTGT',
                  $totalGTGT, //  'Tiền thuế GTGT',
                  $total,   // 'Thành tiền(*)'
                ];
              }
              
              $dataExport[] = $tmp;
              $j++;
          }
        }
      }
      $i++;
    }

    return Excel::download(new UsersExport($dataExport), 'GHN-thang-06-2025.xlsx');
  }
  public function export()
  {
    $sale     = new SaleController();
    $req = new Request();
    $req['daterange'] = ['30/06/2025', '11/08/2025'];
    $req['sale'] = '97';
    // $req['typeDate'] = '2';
    // $sales = ['50','74'];

    $list =  $sale->getListSalesByPermisson(Auth::user(), $req);
    $list->whereNull('id_order_new');
    // $list->whereNull('id_order');
    $list->where('old_customer', 1);
    $list->where('is_duplicate', 0);
    $list->where('group_id', '5');
    // $list->paginate(300, ['*'], 'page', 3);
    // $list->whereIn('assign_user', $sales);
    // dd($list->count());
    $dataExport[] = [
      'Tên' , 'Số điện thoại', 'Tin nhắn khách để lại', 'Note TN trước đó', 'Ngày nhận'
    ];

    foreach ($list->get() as $data) {

      $tnCan = $data->TN_can;
      // if ($data->listHistory) {
      //   foreach ($data->listHistory as $his) {
      //     $tnCan .= date_format($his->created_at,"d-m-Y ") . ': ' . $his->note . ', ';
      //   }
      // }
      $dataExport[] = [
        $data->full_name,
        $data->phone,
        $data->messages,
        $tnCan,
        date_format($data->created_at,"d-m-Y "),
      ];
    }

    return Excel::download(new UsersExport($dataExport), 'file1.xlsx');
  }

  public function fix()
  {
    $from = date('2024-07-01');
    $to = date('2024-07-31');
    // $list = Orders::whereNotExists(function ($query) {
    //   $query->select(\DB::raw('*'))
    //       ->from('sale_care')
    //       ->where('sale_care.id', 'orders.sale_care')
    //       ->where('old_customer', 0)
    //       ;
    //   })
    //   ->where('status', 3)
    //   ->whereBetween('created_at', [$from, $to])
    //   ->get();

    $list = \DB::select("SELECT *
FROM   orders
WHERE  NOT EXISTS
  (SELECT *
   FROM   sale_care
   WHERE  
   sale_care.id = orders.sale_care and sale_care.old_customer = 0 
   
   ) AND orders.created_at BETWEEN '2024-07-01' 
                     AND '2024-07-31 23:59:59.993' ORDER BY `id` ASC;");


      // dd($list);
    // echo "<pre>";
    // print_r($list);
    // echo "</pre>";
    //   die();
      // 
    foreach ($list as $item) {
      // dd($item->id);
      $saleCare = SaleCare::
        where('phone', 'like', '%' . $item->phone . '%')
        ->where('old_customer', 0)
        ->first();

        // dd('hi');
        // dd($saleCare);
      // trường hợp có data TN nhưng chưa map => update map
      if (!$saleCare) {
        echo $item->phone . "<br>";
        $sale = new SaleController();
        $data = [
          'page_link' => '',
          'page_name' => '',
          'sex'       => 0,
          'old_customer' => 0,
          'address'   => $item->address,
          'messages'  => '',
          'name'      => $item->name,
          'phone'     => $item->phone,
          'page_id'   => '',
          'text'      => '',
          // 'chat_id'   => $chatId,
          'm_id'      => '',
          'assgin'    => $item->assign_user,
          'is_duplicate' => 0,
          'id_order_new' => $item->id,
          'created_at'  => $item->created_at
        ];

        $request = new \Illuminate\Http\Request();
        $request->replace($data);
        $sale->save($request);

      } else {
        echo $item->phone . "<br>";
        $order = Orders::find($item->id);
        if ($order) {
          $order->sale_care = $saleCare->id;
          $order->save();
        }
       
      }
      // dd($saleCare);
      //trường hợp có đơn hàng nhưng chưa có data TN => create data và map
    }
        // dd($list);
    
  }

  public function wakeUp()
  {
    // $listSc = SaleCare::whereNotNull('result_call')
    //   ->whereNotNull('type_TN')
    //   ->where('result_call', '!=', 0)
    //   ->where('result_call', '!=', -1)
    //   ->where('has_TN', 1)
    //   ->where('created_at', '>' , '2025-04-30')
    //   ->get();

      
  $listSc = SaleCare::where('phone', '0979410529')->get();
    foreach ($listSc as $sc) {

      // if ($sc->id != '15967') {
      //   continue;
      // }
      
      $call = $sc->call;
      if (empty($call->time)) {
        continue;
      }

      $time = $call->time;
      $nameCall   = $call->callResult->name;
      $updatedAt  = $sc->time_update_TN;
      $isRunjob   = $sc->is_runjob;
      $TNcan   = $sc->TN_can;
      $saleAssign   = $sc->user->real_name;

      if (!$sc->user->status || !$sc->user->is_receive_data) {
        continue;
      }
      if ($sc->listHistory->count()) {
        $sc->listHistory;
        $TNcan = $sc->listHistory[0]->note;
      }
      
      if (!$call || !$time || !$updatedAt || $isRunjob || !$saleAssign) {
        continue;
      }

      //cộng ngày update và time cuộc gọi
      if ($sc->time_wakeup_TN) {
        $newDate = strtotime($sc->time_wakeup_TN);
      } else {
        $newDate = strtotime("+$time hours", strtotime($updatedAt));
      }

      if ($newDate <= time()) {

        $nextTN = $call->thenCall;

        if (!$nextTN) {
          continue;
        }

        $chatId         = '-4286962864';
        $tokenGroupChat = '7127456973:AAGyw4O4p3B4Xe2YLFMHqPuthQRdexkEmeo';
        $group = $sc->group;

        if ($group) {
          $chatId = $group->tele_nhac_TN;
          $tokenGroupChat =  $group->tele_bot_token;

          if ($sc->old_customer && $sc->old_customer == 1 && $group->tele_nhac_TN_CSKH) {
            $chatId = $group->tele_nhac_TN_CSKH;
          }
        }

        //set lần gọi tiếp theo
        if ($sc->type_TN != $nextTN->id) {
          $sc->result_call = 0;
        }

        // 24 id: nhắc lại
        if ($nextTN->id != 24) {
          $sc->type_TN = $nextTN->id;
        }
        
        $sc->has_TN = 0;
        $sc->is_runjob = 1;
        $sc->save();

        //gửi thông báo qua telegram
        $endpoint       = "https://api.telegram.org/bot$tokenGroupChat/sendMessage";
        $client         = new \GuzzleHttp\Client();

        $notiText       = "Khách hàng $sc->full_name sđt $sc->phone"
          . "\nĐã tới thời gian tác nghiệp."
          . "\nKết quả gọi trước đó: $nameCall"
          . "\nGhi chú trước: $TNcan"
          . "\nSale tác nghiệp: $saleAssign"; 

        if ($chatId) {
          $client->request('GET', $endpoint, ['query' => [
            'chat_id' => $chatId, 
            'text' => $notiText,
          ]]);
        }
        
      }
    }
  }

    
}




