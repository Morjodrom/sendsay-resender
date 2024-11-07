<?php

namespace lib;

use RuntimeException;

class SendSay
{
    public function __construct(
        #[\SensitiveParameter]
        protected string $token
    )
    {
    }


    /**
     * @param string $email
     * @param string $phone
     * @param array $data
     *
     * @return mixed
     * @throws \JsonException|\RuntimeException
     */
    function sendSubscriber(string $email, string $phone, array $data)
    {
        $query = [
            'action'         => 'member.set',
            'apikey'         => $this->token,
            'email'          => $email,
            'cellphone'      => $phone,
            'newbie.confirm' => '0',
            "datakey"        => $data
        ];

        $response = $this->sendsay(json_encode($query, JSON_THROW_ON_ERROR));

        if(isset($response['errors'])) {
            throw new RuntimeException($response['errors'][0]['explain']);
        }

        return $response;
    }


    protected function sendsay($data = '', $redirect = '')
    {
        if($redirect) {
            $baseurl = "https://api.sendsay.ru" . $redirect;
        } else {
            $baseurl = 'https://api.sendsay.ru/general/api/v100/json/x_172719918089036';
        }
        $result = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $baseurl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        $json = json_decode($result, TRUE);

        $info = curl_getinfo($ch);
        curl_close($ch);


        if(array_key_exists('REDIRECT', $json)) {
            return $this->sendsay($data, $json['REDIRECT']);
        }

        return $json;
    }
}
