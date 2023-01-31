<?php

namespace App\Services\Game;

use App\Enums\Response;
use App\Exceptions\NewGroundsProxyException;
use App\Exceptions\ResponseException;
use App\Models\NGProxy\Song;
use App\Services\ProxyService;
use Illuminate\Support\Arr;

class SongService
{
    /**
     * @throws NewGroundsProxyException
     */
    public function find(int $id): Song
    {
        $song = Song::query()
            ->where('song_id', $id)
            ->first();

        if (!empty($song)) {
            if ($song->disabled) {
                throw (new NewGroundsProxyException(
                    __('gdcn.song.error.fetch_failed_disabled'),
                    httpCode: 403,
                    gameResponse: Response::GAME_SONG_FETCH_FAILED_DISABLED->value
                ))->setSong($song);
            }

            return $song;
        }

        $disabled = false;
        $proxy = ProxyService::instance()
            ->asForm()
            ->withUserAgent(null);

        try {
            $response = $proxy->post(config('gdcn.proxy.base') . '/getGJSongInfo.php', [
                'songID' => $id,
                'secret' => 'Wmfd2893gb7',
            ])->body();

            ResponseService::check($response);
            $songObject = ObjectService::split($response, '~|~');
        } catch (ResponseException) {
            if (!empty($response) && $response === '-2') {
                $disabled = true;
            }

            $response = $proxy->post(config('gdcn.proxy.base') . '/getGJLevels21.php', [
                'song' => $id,
                'customSong' => true,
                'secret' => 'Wmfd2893gb7',
            ])->body();

            try {
                ResponseService::check($response);
            } catch (ResponseException) {
                throw new NewGroundsProxyException(
                    __('gdcn.song.error.fetch_failed'),
                    gameResponse: Response::GAME_SONG_FETCH_FAILED_NOT_FOUND->value
                );
            }

            $songObject = ObjectService::split(Arr::get(explode('#', $response), 2), '~|~');
        }

        if (!Arr::has($songObject, [1, 2, 3, 4, 5, 10])) {
            throw new NewGroundsProxyException(
                __('gdcn.song.error.fetch_failed_wrong_song_object'),
                logContext: [
                    'object' => $songObject
                ],
                gameResponse: Response::GAME_SONG_FETCH_FAILED_PROCESS_EXCEPTION->value
            );
        }

        return Song::query()
            ->create([
                'song_id' => $songObject[1],
                'name' => $songObject[2],
                'artist_id' => $songObject[3],
                'artist_name' => $songObject[4],
                'size' => $songObject[5],
                'disabled' => $disabled,
                'original_download_url' => $songObject[10],
            ]);
    }
}