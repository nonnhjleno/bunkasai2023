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

        $users = User::orderBy('score', 'desc')->select('username', 'score')->get();

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

        // 全角空白を半角スペースに変換
        $query = str_replace('　', ' ', $query);

        // スペースでキーワードを分割
        $keywords = explode(' ', $query);

        if (count($keywords) === 1) {
            // キーワードが1つの場合、完全一致検索を行う
            $users = User::where('username', $query)->get();
        } else {
            // キーワードが複数ある場合、AND検索を行う
            $users = User::where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('username', 'like', '%' . $keyword . '%');
                }
            })->get();
        }

        if ($users->isEmpty()) {
            // 部分一致で検索
            $users = User::where('username', 'like', '%' . $query . '%')->get();
        }

        return response()->json($users);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // リクエストから得点を取得
        $newScore = (int) $request->input('score');
        Log::info($request->all());

        // ユーザーをデータベースから取得
        $user = User::find($id);

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
    public function destroy(string $id)
    {
        //
    }
}
