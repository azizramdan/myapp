<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private function success($data)
    {
        return response()->json([
            'server' => config('app.name'),
            'data' => $data
        ]);
    }

    private function appendAdditional(object $data): object
    {
        $data->name_char_length = strlen($data->name);
        $data->username = Str::snake($data->name);
        $data->accessed_at = now();

        return $data;
    }

    public function index(Request $request)
    {
        $users = User::query()->paginate($request->perpage ?? 500);

        /**
         * @var \Illuminate\Pagination\AbstractPaginator $users
         */
        $users->setCollection(
            $users->getCollection()
                ->transform(function ($item) {
                    $item = $this->appendAdditional($item);
        
                    return $item;
                })
        );

        return $this->success($users);
    }

    public function show(User $user)
    {
        $user = $this->appendAdditional($user);
        
        return $this->success($user);
    }
}
