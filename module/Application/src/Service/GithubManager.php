<?php
/**
 * Created by PhpStorm.
 * User: localklaus
 * Date: 01.10.18
 * Time: 22:28
 */

namespace Application\Service;


class GithubManager
{
    public function __construct()
    {
    }


    public function getMyRepos()
    {
        $user = 'klausberlin';
        $url = 'https://api.github.com/users/klausberlin/repos';
        $cInit = curl_init();

        curl_setopt($cInit, CURLOPT_URL, $url);
        curl_setopt($cInit, CURLOPT_RETURNTRANSFER, 1); // 1 = TRUE
        curl_setopt($cInit, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($cInit, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($cInit, CURLOPT_USERPWD, $user);

        $output = curl_exec($cInit);


        // close curl resource to free up system resources
        curl_close($cInit);


        return $output;
    }


}