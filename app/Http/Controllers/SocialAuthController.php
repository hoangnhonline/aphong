<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use App\Models\Customer;
use Helper, File, Session, Auth;
use App;

class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function fbLogin(Request $request)
    {
        $fb_token = $request->token;

        $fb            = App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');
        $response      = $fb->get('/me?fields=id,name,email,picture.width(200).height(200)', $fb_token);
        $facebook_user = $response->getGraphUser();

        $facebook['email'] = $facebook_user['email'];
        $facebook['id']    = $facebook_user['id'];
        $facebook['name']  = $facebook_user['name'];
        $facebook['avatar']= $facebook_user['picture']['url'];

        $getCustomer = Customer::where('email', $facebook['email'])->first();

        if(is_null($getCustomer)) {
            Session::put('fb_id', $facebook['id']);

            if(!$facebook['name']) {
                Session::put('fb_name',  $facebook['name']);
            }

            if(!$facebook['email']) {
                Session::put('fb_email',  $facebook['email']);
            }

            $customer = new Customer;
            $customer->fullname    =  $facebook['name'];
            $customer->email        =  $facebook['email'];
            $customer->facebook_id  =  $facebook['id'];
            $customer->image_url    =  $facebook['avatar'];
            $customer->save();

            Session::flash('register', 'true');
            Session::put('login', true);
            Session::put('userId', $customer->id);
            Session::put('facebook_id', $customer->facebook_id);
            Session::put('username', $customer->fullname);       
            Session::put('validfrom', $customer->valid_from);
            Session::put('validto', $customer->valid_to);     
            return response()->json([
                'sucess' => 1
            ]);


        } else {

            if(!$getCustomer->image_url) {
                $getCustomer->image_url = $facebook['avatar'];
                $getCustomer->save();
            }

            Session::put('login', true);
            Session::put('userId', $getCustomer->id);
            Session::put('facebook_id', $getCustomer->facebook_id);
            Session::put('username', $getCustomer->fullname);
            Session::put('avatar', $getCustomer->image_url);
            Session::put('validfrom', $getCustomer->valid_from);
            Session::put('validto', $getCustomer->valid_to);
            return response()->json([
                'sucess' => 0
            ]);
        }

        return response()->json(['fb_token' => $fb_token, 'fbUser' => $facebook]);
    }

}
