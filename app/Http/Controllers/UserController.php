<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = User::find(1);
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
