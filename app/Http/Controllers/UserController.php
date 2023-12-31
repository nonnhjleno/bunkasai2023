<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Log::info('成功');
        // $result = User::find(1);
        // return response()->json($result);

        $users = User::orderBy('score', 'desc')->select('id','username', 'score')->get();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $username = $request->input('username');

        // 同じユーザー名が既に存在するか確認
        $existingUser = User::where('username', $username)->first();

        // 同じユーザー名が存在する場合はエラーレスポンスを返す
        if ($existingUser) {
            return response()->json([
                "error" => "既に存在するユーザーネームです"
            ], 400);
        }

        // 新しいユーザーを作成
        $user = new User();
        $user->username = $username;
        $user->score = 0;
        $user->save();

        // 作成されたユーザー情報を返す
        return response()->json($user, 201); // 201は作成成功を示すHTTPステータスコードです
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $query = $request->input('username');
        $query = str_replace('　', ' ', $query);
        $keywords = explode(' ', $query);
    
        $users = User::select('id', 'username', 'score');
    
        foreach ($keywords as $keyword) {
            $users->orWhere('username', 'like', '%' . $keyword . '%');
        }
    
        $users->orWhere('username', 'like', '%' . $query . '%');
    
        $result = $users->get();
    
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // リクエストから得点を取得
        $newScore = (int) $request->input('score');

        // ユーザーをデータベースから取得
        $user = User::find($id);
        Log::info($user['score']);

        if($newScore < $user['score']){
            $newScore = $user['score'];
        }

        // ユーザーが存在しない場合はエラーレスポンスを返す
        if (!$user) {
            return response()->json(['message' => 'ユーザーが見つかりません'], 404);
        }

        // 得点を更新
        $user->score = $newScore;
        $user->save();

        $user = User::find($id);

        // 更新されたユーザー情報を返す
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::find($id);

        if ($request->input('option') === 'score') {
            $user->score = 0;
            $user->save();
            return response()->json(['message' => 'id = ' . $user['id'] . 'の点数が0点になりました。'], 200, [], JSON_UNESCAPED_UNICODE);
        } else if ($request->input('option') === 'all') {

            if (!$user) {
                return response()->json(['message' => 'ユーザーが見つかりません'], 404, [], JSON_UNESCAPED_UNICODE);
            }

            try {
                $user->delete();
                return response()->json(['message' => 'id = ' . $user['id'] . 'のデータが削除されました。'], 200, [], JSON_UNESCAPED_UNICODE);
            } catch (\Exception $e) {
                return response()->json(['message' => '削除中にエラーが発生しました'], 500, [], JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
