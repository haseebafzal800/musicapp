<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\GeniusResource;
use Symfony\Component\DomCrawler\Crawler;
// use Symfony\Component\DomCrawler\Crawler;

class GeniusController extends Controller
{
    public function search(Request $request)
    {
        if($request->term){
            $accessToken = $this->getAccessToken();
            if($accessToken){
                $searchResults = $this->performSearch($request->term, $accessToken);
                if($searchResults && $searchResults['meta']['status']==200){
                    $songs = $searchResults['response']['hits'];
                    if($songs){
                        $this->data = GeniusResource::collection($songs);
                        if($this->data){
                            $this->responsee(true);                        
                        }else{
                            $this->responsee(false, $this->w_err);                        
                        }
                    }else{
                        $this->d_err = 'Data not found';
                        $this->responsee(false, $this->d_err);
                    }
                }else{
                    $this->d_err = 'Data not found';
                    $this->responsee(false, $this->d_err);
                }
            }else{
                $this->d_err = 'Access Token error';
                $this->responsee(false, $this->d_err);
            }
        }else{
            $this->d_err = 'Search Keywords not found';
            $this->responsee(false, $this->d_err);
        }
        return json_response($this->resp, $this->httpCode);
    }

    private function getAccessToken()
    {
        $clientId = 'imiR9fILXJYUGQF-PGcdJOOm3zQIUcLQRhB8FajugWNWpWDANzqIU1a7fTQzV1vT'; //test
        $clientSecret = 'ULjKFi71L7qZ5gDrqs7H7Ng1ss2bJQ2VzZWNWh2u_JYBhubXilxKCREx3yET2lP7wqjE4e1mvPTPTCajrZGoaw'; //test
        $clientId = 'NRL70ffj8D1Lp3CKnga8xmNDY6YaPov6ZdOsOWWNJprZ_UolYQHVhDmao9M9AeLu'; //prod
        $clientSecret = 'Ac0zrNvLHNnaACJ3fglnzrwrH_x2T_LWCmX0MdzQnDjtt4lyn4IKdHMYr2xqGdmn87JiMo6G2yFCTvSitVO6Mg'; //prod

        // Make a request to obtain the access token
        $response = Http::asForm()->post('https://api.genius.com/oauth/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'client_credentials',
        ]);
        if($response){
            $accessToken = $response->json('access_token');
            if($accessToken)
                return $accessToken;
            else
                return false;
        }else
            return false;
    }

    private function performSearch($term, $accessToken)
    {
        // Make a GET request to the search endpoint with the access token
        $response = Http::withToken($accessToken)->get('https://api.genius.com/search', [
            'q' => $term,
        ]);
        if($response){
            $searchResults = $response->json();
            if($searchResults)
                return $searchResults;
            else
                return false;
        }
        else
            return false;
    }

    public function getSingleGeniusSong($songId)
    {
        if($songId){
            $this->data = $this->getLyrics("https://genius.com/".$songId);
            if($this->data){
                $this->responsee(true);                        
            }else{
                $this->d_err = 'SOng Not found';
                $this->responsee(false, $this->d_err);                        
            }
        }else{
            $this->d_err = 'Input data error';
            $this->responsee(false, $this->d_err);
        }
        return json_response($this->resp, $this->httpCode);
    }

    private function getLyrics($songUrl=null)
    {
        if($songUrl){
            $htmlContent = file_get_contents($songUrl);
            if($htmlContent){
                $lyrics = $this->parseLyrics($htmlContent);
                if($lyrics){
                    return $lyrics; 
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    private function parseLyrics($htmlContent=null)
    {
        if($htmlContent){
            $crawler = new Crawler($htmlContent);
            $lyrics = '';
            $lyricsNodes = $crawler->filter('.Lyrics__Container-sc-1ynbvzw-1');
            if($lyricsNodes){
                $lyricsNodes->each(function (Crawler $node) use (&$lyrics) {
                    $lyrics .= $node->html();
                });
                if($lyrics != ''){
                    $lyricsar = explode('<br>', $lyrics);
                    $arrayWithoutHtml = array_map('strip_tags', $lyricsar);
                    // $arrayWithoutHtmlq = str_replace('"",', '<br>', $arrayWithoutHtml);
                    return $arrayWithoutHtml;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}

