<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function getListUserCache()
    {
        $cached = Redis::get('list_user_redis_');

        if(isset($cached)) {
            $data_user = json_decode($cached, FALSE);
            
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from redis',
                'data' => $data_user,
            ]);
        }else {
            $data_user = User::all();
            Redis::set('list_user_redis_', json_encode([
                $data_user
            ]), 'EX', 60);
      
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $data_user,
            ]);
        }
    }

    public function getUser($user)
    {
        $cached = Redis::get('user_redis_' . $user);

        if(isset($cached)) {
            $data_user = json_decode($cached, FALSE);
      
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from redis',
                'data' => $data_user,
            ]);
        }else {
            $data_user = User::where("name", $user)->first();
            Redis::set('user_redis_' . $user, $data_user, 'EX', 60);
      
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $data_user,
            ]);
        }
    }

    public function getListUserQuery()
    {
        $query = User::all();
        foreach ($query as $q) {
            echo "<li>{$q->name}</li>";
        }
    }
}
