<?php

namespace App\Http\Controllers\Frontend;

use App\Events\ChatPersonal;
use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Owners;
use App\Models\SiteInfo;
use App\Models\SuperLike;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Start Chat',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/swiper/swiper-bundle.min.js',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
            'dist/assets/js/tinderSwiper.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/assets/js/dz.carousel.js',
            'scripts/frontend/siteinfo.init.js',
            'https://js.pusher.com/8.2.0/pusher.min.js',
            'scripts/frontend/chat.init.js',
        );
        return view('frontend.chat', compact('data'));
    }
    public function startChat($owner_id)
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        $iduserSesIdp = Auth::guard('owner')->user()->id;
        // get key chat for check
        $check = Chat::where(function ($query) use ($iduserSesIdp, $owner_id) {
            $query->where('from', $iduserSesIdp)
            ->where('to', $owner_id);
        })
        ->orWhere(function ($query) use ($iduserSesIdp, $owner_id) {
            $query->where('from', $owner_id)
            ->where('to', $iduserSesIdp);
        })
        ->first();
        $keyChat = md5(uniqid());
        if(!empty($check)){
            $keyChat = $check->key_chat;
        }
        // owner chat info
        $owner = Owners::whereId($owner_id)->first();
        $file_name = $owner->thumb;
        if($file_name==''){
            $owner->thumb_url = asset('dist/img/default-user-img.jpg');
        } else {
            if (!file_exists(public_path(). '/dist/img/users-img/'.$file_name)){
                $owner->thumb_url = asset('dist/img/default-user-img.jpg');
            }else{
                $owner->thumb_url = url('dist/img/users-img/'.$file_name);
            }
        }
        $owner->last_login = Shortcut::timeago($owner->last_login);
        //Data WebInfo
        $data = array(
            'title' => 'Chat',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession,
            'owner_info' => $owner,
            'key_chat' => $keyChat,
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/swiper/swiper-bundle.min.js',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
            'dist/assets/js/tinderSwiper.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/assets/js/dz.carousel.js',
            'scripts/frontend/siteinfo.init.js',
            'https://js.pusher.com/8.2.0/pusher.min.js',
            'scripts/frontend/start_chat.init.js',
        );
        return view('frontend.start_chat', compact('data'));
    }
    public function ChatLove()
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $getRow =  SuperLike::select(
            'owners.id',
            'owners.name',
            'owners.thumb',
            'owners.last_login',
        )
        ->join('owners', 'owners.id', '=', 'super_like.fid_owner')
        ->whereNot('owners.id', $iduserSesIdp)->where('owners.is_active', 'Y')
        ->orderBy('owners.last_login', 'DESC')
        ->get();

        $allowChat = [];
        foreach($getRow as $row){
            $file_name = $row->thumb;
            if($file_name==''){
                $thumb_url = asset('dist/img/default-user-img.jpg');
            } else {
                if (!file_exists(public_path(). '/dist/img/users-img/'.$file_name)){
                    $thumb_url = asset('dist/img/default-user-img.jpg');
                }else{
                    $thumb_url = url('dist/img/users-img/'.$file_name);
                }
            }
            if($row->last_login < time() - (2 * 60)){
                $statusActive = 'Y';
            }else{
                $statusActive = 'N';
            }
            $allowChat[] = [
                'id' => $row->id,
                'name' => Str::limit($row->name, 10),
                'thumb_url' => $thumb_url,
                'is_active' => $statusActive
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $allowChat);
    }
    public function allMessage()
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $getRow = Chat::select(
            'owners.id',
            'owners.name',
            'owners.thumb',
            'owners.last_login',
            'chats.from',
            'chats.to',
            'chats.message',
            'chats.created_at',
        )
        ->orderBy('chats.created_at', 'DESC')
        ->join('owners', function ($join) use ($iduserSesIdp) {
            $join->on('owners.id', '=', 'chats.to')
                 ->where('owners.id', '!=', $iduserSesIdp)
                 ->orWhere('chats.from', '=', $iduserSesIdp);
        })
        ->where(function ($query) use ($iduserSesIdp) {
            $query->where('chats.from', '=', $iduserSesIdp)
                  ->orWhere('chats.to', '=', $iduserSesIdp);
        })
        ->whereNot('owners.id', $iduserSesIdp)
        ->where('owners.is_active', 'Y')
        ->groupBy('owners.id')
        ->get();
        
        $allMessage = [];
        foreach($getRow as $row){
            $file_name = $row->thumb;
            $statusRead = $row->is_read;
            // if ($row->from != $iduserSesIdp) {
            //     $user = Owners::whereId($row->from)->first();
            //     $file_name = $user->thumb;
            //     $row->name = $user->name;
            //     $statusRead = 'N';
            // }
            if($file_name==''){
                $thumb_url = asset('dist/img/default-user-img.jpg');
            } else {
                if (!file_exists(public_path(). '/dist/img/users-img/'.$file_name)){
                    $thumb_url = asset('dist/img/default-user-img.jpg');
                }else{
                    $thumb_url = url('dist/img/users-img/'.$file_name);
                }
            }
            // status login
            if($row->last_login < time() - (2 * 60)){
                $statusActive = 'Y';
            }else{
                $statusActive = 'N';
            }
            $allMessage[] = [
                'id' => $row->id,
                'name' => $row->name,
                'thumb_url' => $thumb_url,
                'message' => Str::limit($row->message, 20),
                'last_chat' => Shortcut::timeago($row->created_at),
                'is_active' => $statusActive,
                'is_read' => $statusRead,
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $allMessage);
    }
    public function personalChat(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $idpOtherOwner = $request->idp_owner;
        $getRow = Chat::where(function ($query) use ($iduserSesIdp, $idpOtherOwner) {
            $query->where('from', $iduserSesIdp)
                  ->where('to', $idpOtherOwner);
        })
        ->orWhere(function ($query) use ($iduserSesIdp, $idpOtherOwner) {
            $query->where('from', $idpOtherOwner)
                  ->where('to', $iduserSesIdp);
        })
        ->orderBy('created_at', 'ASC')
        ->get();
        $personalMessage = [];
        foreach($getRow as $row){
            $isMe = '';
            if($row->from == $iduserSesIdp){
                $isMe = 'user';
            }
            $personalMessage[] = [
                'id' => $row->id,
                'message' => $row->message,
                'timechat' => date("H:i", strtotime($row->created_at)),
                'is_me' => $isMe,
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $personalMessage);
    }
    public function sendMessage(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $idpOtherOwner = $request->idp_owner;
        DB::beginTransaction();
        try {
            // get key chat for check
            $check = Chat::where(function ($query) use ($iduserSesIdp, $idpOtherOwner) {
                $query->where('from', $iduserSesIdp)
                      ->where('to', $idpOtherOwner);
            })
            ->orWhere(function ($query) use ($iduserSesIdp, $idpOtherOwner) {
                $query->where('from', $idpOtherOwner)
                      ->where('to', $iduserSesIdp);
            })
            ->first();
            $keyChat = $request->key_chat;
            if(!empty($check)){
                $keyChat = $check->key_chat;
            }
            Chat::insertGetId([
                'from' => $iduserSesIdp,
                'to' => $idpOtherOwner,
                'message' => $request->message,
                'key_chat' => $keyChat,
                'created_at' => Carbon::now(),
            ]);
            DB::commit();
            event(new ChatPersonal(Auth::guard('owner')->user()->id, $idpOtherOwner, $keyChat, $request->message));
            return Shortcut::jsonResponse(true, 'Like or unlike successfully', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}