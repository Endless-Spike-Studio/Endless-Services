<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use Innocenzi\Vite\Vite;

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        $hash = Cache::get('git_commit_hash', static function () {
            $hash = exec('git log --pretty="%h" -n1 HEAD');
            Cache::set('git_commit_hash', $hash);

            return $hash;
        });

        return array_merge(parent::share($request), [
            'gdcn' => [
                'user' => $request->user()
                    ?->select(['id', 'name'])
                    ?->firstOrFail()
            ],
            'gdcs' => [
                'account' => $request->user('gdcs')
                    ?->select(['id', 'name'])
                    ?->firstOrFail(),
                'user' => $request->user('gdcs')
                    ?->select(['id', 'name'])
                    ?->with('user:id,uuid,name')
                    ?->firstOrFail()
                    ?->getRelation('user')
            ],
            'messages' => Session::pull('messages', []),
            'versions' => [
                'php' => PHP_VERSION,
                'laravel' => App::version(),
                'git' => $hash
            ]
        ]);
    }

    public function version(Request $request): ?string
    {
        return app(Vite::class)->getHash();
    }
}
