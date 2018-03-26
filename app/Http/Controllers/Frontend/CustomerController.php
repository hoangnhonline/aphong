<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\City;
use App\Models\Country;
use App\Models\CustomerNotification;
use Helper,
    File,
    Session,
    Auth,
    Hash,
    Validator;
use Mail;
use App\Models\CustomerAddress;

class CustomerController extends Controller
{

    public function update(Request $request)
    {
        $data = $request->all();

        $customer_id = Session::get('userId');

        $customer = Customer::find($customer_id)->update($data);

        if (Session::has('new-register')) {
            Session::forget('new-register');
        }

        if (Session::has('fb_name')) {
            Session::forget('fb_name');
        }

        if (Session::has('fb_email')) {
            Session::forget('fb_email');
        }

        if (Session::has('fb_id')) {
            Session::forget('fb_id');
        }

        Session::flash('message', 'Cập nhật thành công.');

        return redirect()->route('account-info');
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $email = $request->email;

        $customer = Customer::where('email', $email)->first();
        $fullname = $request->fullname;
        $password = $request->password;

        if (!is_null($customer)) {
            Session::flash('validate', 'Email đã tồn tại');
            return 0;
        }

        $data['password'] = bcrypt($data['password']);
        $data['status'] = 1;
        $customer = Customer::create($data);

        //set Session user for login here
        Session::put('login', true);
        Session::put('userId', $customer->id);
        Session::put('username', $customer->fullname);
        Session::put('new-register', true);
        return "1";
    }
        public function guilaiMatkhau(Request $request){
            $success = isset($request->success) ? 1 : 0;
             $seo['title'] = $seo['description'] = $seo['keywords'] = "Quên mật khẩu";
            return view('frontend.account.gui-lai-mat-khau', compact('seo', 'success'));
        }
    public function forgetPassword(Request $request)
    {
        $this->validate($request, [
            'email_reset' => 'bail|required|email|exists:customers,email',
                ], [
            'email_reset.required' => 'Vui lòng nhập email.',
            'email_reset.email' => 'Vui lòng nhập email hợp lệ.',
            'email_reset.exists' => 'Email không tồn tại trong hệ thống K Minimart & Kaffee',
        ]);
        $email = $request->email_reset;
        $key = md5($request->email_reset . time() . 'kshop247.vn');
        $customer = Customer::where('email', $email)->first();
        $customer->key_reset = $key;
        $customer->save();
        Mail::send('frontend.account.forgot', [
            'key' => $key
                ], function($message) use ($email) {
            $message->subject('Yêu cầu thay đổi mật khẩu');
            $message->to($email);
            $message->from('kkaffee.vn@gmail.com', 'K Minimart & Kaffee');
                    $message->sender('kkaffee.vn@gmail.com', 'K Minimart & Kaffee');
        });

        return redirect()->route('gui-lai-mk', "success=1");
    }

    public function resetPassword(Request $request)
    {
        $key = $request->key;
        $detailCustomer = Customer::where('key_reset', $key)->first();
        if (!$detailCustomer) {
            return redirect()->route('home');
        }
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Cập nhật mật khẩu mới";
        $lang = Session::get('locale') ? Session::get('locale') : 'vi';
        return view('frontend.account.reset-password', compact('seo', 'detailCustomer', 'lang'));
    }

    public function registerAjax(Request $request)
    {
        $data = $request->all();

        $email = $request->email;
        $customer = Customer::where('email', $email)->first();

        if (!is_null($customer)) {
            return response()->json(['error' => 'email']);
        }


        $data['password'] = bcrypt($data['password']);
        $data['status'] = 1;
        $customer = Customer::create($data);

        //set Session user for login here
        Session::put('login', true);
        Session::put('userId', $customer->id);
        Session::put('new-register', true);
        Session::put('username', $customer->fullname);
        return response()->json(['error' => 0]);
    }

    public function notification()
    {
        if (!Session::get('userId')) {
            return redirect()->route('home');
        }
        $notiSale = $notiOrder = [];
        $tmpArr = CustomerNotification::where(['customer_id' => Session::get('userId')])->get();
        if ($tmpArr) {
            foreach ($tmpArr as $tp) {
                if ($tp->type == 1) {
                    $notiSale[] = $tp->toArray();
                } else {
                    $notiOrder[] = $tp->toArray();
                }
            }
        }
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Thông báo của tôi";
        $lang = Session::get('locale') ? Session::get('locale') : 'vi';
        return view('frontend.account.notification', compact('notiSale', 'notiOrder', 'seo', 'lang'));
    }

    public function accountInfo()
    {
        if (!Session::get('userId')) {
            return redirect()->route('home');
        }
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Thông tin tài khoản";
        $customer_id = Session::get('userId');
        $customer = Customer::find($customer_id);
        $listCity = City::orderBy('display_order')->get();
        $listCountry = Country::orderBy('id')->get();
        return view('frontend.account.update-info', compact('seo', 'customer', 'listCity', 'listCountry'));
    }

    public function changePassword(Request $request)
    {
        if (!Session::get('userId')) {
            return redirect()->route('home');
        }
        $errors = $request->errors ? $request->errors : [];
        $customerDetail = Customer::find(Session::get('userId'));
        if ($customerDetail->facebook_id > 0) {
            return redirect()->route('home');
        }
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Đổi mật khẩu";
        $customer_id = Session::get('userId');
        $customer = Customer::find($customer_id);
        $lang = Session::get('locale') ? Session::get('locale') : 'vi';
        return view('frontend.account.change-password', compact('seo', 'customer', 'lang'));
    }

    public function saveNewPassword(Request $request)
    {
        if (!Session::get('userId')) {
            return redirect()->route('home');
        }
        $customerDetail = Customer::find(Session::get('userId'));
        $old_pass = $request->old_pass;
        $new_pass = $request->new_pass;
        $re_new_pass = $request->re_new_pass;
        $errors = [];
        if (!password_verify($old_pass, $customerDetail->password)) {
            $request->session()->flash('error', 'Mật khẩu cũ không đúng.');
            return redirect()->route('change-password');
        } else {
            if ($new_pass == $re_new_pass) {
                $password = bcrypt($new_pass);
                $customerDetail->password = $password;
                $customerDetail->save();
                $request->session()->flash('success', 'Đổi mật khẩu thành công.');
                return redirect()->route('change-password');
            } else {
                $request->session()->flash('error', 'Mật khẩu mới nhập lại không đúng.');
                return redirect()->route('change-password');
            }
        }
    }

    public function saveResetPassword(Request $request)
    {
        $email = $request->email;
        $customerDetail = Customer::where('email', $email)->first();
        $new_pass = $request->new_pass;
        $re_new_pass = $request->re_new_pass;
        $errors = [];

        if ($new_pass == $re_new_pass) {
            $password = bcrypt($new_pass);
            $customerDetail->password = $password;
            $customerDetail->save();
            $request->session()->flash('success', 'Đổi mật khẩu thành công.');
            return redirect()->back();
        } else {
            $request->session()->flash('error', 'Mật khẩu mới nhập lại không đúng.');
            return redirect()->back();
        }
    }

    public function isEmailExist(Request $request)
    {
        $email = $request->email;
        $customer = Customer::where('email', $email)->first();

        return is_null($customer) ? 0 : 1;
    }

    public function address()
    {
        if (!Session::get('userId')) {
            return redirect()->route('home');
        }
        
        $seo['title'] = $seo['description'] = $seo['keywords'] = 'Thông tin tài khoản';
        $customer_id = Session::get('userId');
        $customer = Customer::find($customer_id);

        $addressPrimary = CustomerAddress::where(['customer_id' => $customer_id, 'is_primary' => 1])->first();
        $listAddress = CustomerAddress::where(['customer_id' => $customer_id, 'is_primary' => 0])->get();

        return view('frontend.account.address', compact('seo', 'customer', 'addressPrimary', 'listAddress'));
    }

    public function createAddress(Request $request)
    {
        if (!Session::get('userId')) {
            return redirect()->route('home');
        }
        
        if (!$request->isMethod('post')) {
            $seo['title'] = $seo['description'] = $seo['keywords'] = 'Thông tin tài khoản';
            $customer_id = Session::get('userId');
            $customer = Customer::find($customer_id);

            $cityList = City::orderBy('display_order')->get();

            return view('frontend.account.address_create', compact('seo', 'customer', 'cityList'));
        } else {
            $this->validate($request, [
                'fullname' => 'required|max:100',
                'phone' => 'required|max:25',
                'email' => 'email',
                'city_id' => 'required',
                'district_id' => 'required',
                'ward_id' => 'required',
                'address' => 'required',
            ], [
                'fullname.required' => 'Vui lòng nhập họ tên.',
                'fullname.max' => 'Họ tên quá dài, vui lòng nhập lại.',
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'phone.max' => 'Số điện thoại quá dài, vui lòng nhập lại.',
                'email.email' => 'Emil không đúng, vui lòng nhập lại.',
                'city_id.required' => 'Vui lòng chọn tỉnh/thành phố.',
                'district_id.required' => 'Vui lòng chọn quận/huyện.',
                'ward_id.required' => 'Vui lòng chọn phường/xã.',
                'address.required' => 'Vui lòng nhập địa chỉ.',
            ]);
            
            if ($request->is_primary == 1) {
                CustomerAddress::where(['customer_id' => Session::get('userId')])->update(['is_primary' => 0]);
            }
            
            CustomerAddress::create([
                'customer_id' => Session::get('userId'),
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'email' => $request->email,
                'phone' => $request->phone,
                'fullname' => $request->fullname,
                'address' => $request->address,
                'is_primary' => $request->is_primary
            ]);
            
            Session::flash('message', 'Thêm mới thành công.');
            
            return redirect()->route('account-address');
        }
    }

    public function editAddress(Request $request, $id)
    {
        if (!Session::get('userId')) {
            return redirect()->route('home');
        }
        
        $customer_address = CustomerAddress::find($id);
        if (!$customer_address || $customer_address->customer_id != Session::get('userId')) {
            abort(404);
        }
        
        if (!$request->isMethod('post')) {
            $seo['title'] = $seo['description'] = $seo['keywords'] = 'Thông tin tài khoản';
            $customer_id = Session::get('userId');
            $customer = Customer::find($customer_id);

            $cityList = City::orderBy('display_order')->get();

            return view('frontend.account.address_edit', compact('seo', 'customer', 'customer_address', 'cityList'));
        } else {
            $this->validate($request, [
                'fullname' => 'required|max:100',
                'phone' => 'required|max:25',
                'email' => 'email',
                'city_id' => 'required',
                'district_id' => 'required',
                'ward_id' => 'required',
                'address' => 'required',
            ], [
                'fullname.required' => 'Vui lòng nhập họ tên.',
                'fullname.max' => 'Họ tên quá dài, vui lòng nhập lại.',
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'phone.max' => 'Số điện thoại quá dài, vui lòng nhập lại.',
                'email.email' => 'Emil không đúng, vui lòng nhập lại.',
                'city_id.required' => 'Vui lòng chọn tỉnh/thành phố.',
                'district_id.required' => 'Vui lòng chọn quận/huyện.',
                'ward_id.required' => 'Vui lòng chọn phường/xã.',
                'address.required' => 'Vui lòng nhập địa chỉ.',
            ]);
            
            if ($request->is_primary == 1 && $customer_address->is_primary == 0) {
                CustomerAddress::where(['customer_id' => Session::get('userId')])->update(['is_primary' => 0]);
            }
            
            $customer_address->update([
                'customer_id' => Session::get('userId'),
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'email' => $request->email,
                'phone' => $request->phone,
                'fullname' => $request->fullname,
                'address' => $request->address,
                'is_primary' => $request->is_primary ? 1 : 0
            ]);
            
            Session::flash('message', 'Cập nhật thành công.');
            
            return redirect()->route('account-address');
        }
    }

    public function destroyAddress($id)
    {
        $customer_address = CustomerAddress::find($id);
        
        if (!$customer_address || $customer_address->customer_id != Session::get('userId')) {
            abort(404);
        }
        
        $customer_address->delete();
        
        return response()->json(['error' => 0, 'message' => 'Xóa thành công.']);
    }

}
