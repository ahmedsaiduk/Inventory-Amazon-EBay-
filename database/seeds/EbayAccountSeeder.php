<?php

use Illuminate\Database\Seeder;

class EbayAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('integrations')->insert([
            'user_id' => 1,
            'marketPlace' => 'Ebay',
            'site' => 'Ebay-us',
            'siteID' => '0',
            'currency' => 'USD',
            'authToken' => 'AgAAAA**AQAAAA**aAAAAA**W7UBWQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFlIKhD5aBpwqdj6x9nY+seQ**3pADAA**AAMAAA**0RkV+0JwO51jGrD4kkB8qdWHo6CEAXBl1htIa6hW8ldWVBtVL2ZjCImG6Htezo2gMAxCj9453mMqkkSpmCQ6BUrgpmKbOg3WJGLO4pjZOpcYHknDYoilYcKOEf3cNYzZj6vAKhTbGBo9FWideoHWU7OFCvgDOoLu/l5svXuCk7nH4KRkp7tyaic9AqrHv8HpSKtrVuX7zPD6gd9nlX5mRdEgy1VVDmTSlsFZi9fIn+RWDaeRrcJjdE7nlpHiB1U1uE13TzT9wSAdComwaFxyR0DZe/F0HFXNkA2SvjTSw1G4xzUbmq0LkKZoE1xRNaWKwxIVaocPMGS90wjeI7l1ppLaeeo3DSCyYBgC/C0lukfJu9HVsBHbwefgVcBMOIhgZj6V7a922Gpza32TVHveqUfKzWiqEkDbzHT2IiQXbNbM4t0QS/UeAjLQENoQTvIFrqp7X+PLINIzV6I1ThXhmRkKlcSvJjLui0Q/v83p3Fnrpdz9cmih/rA+LPkKbhDA6d+eMthWh7eisGleCaks8SCkWpxDIDR14hvg9rsv4y1vsMvrOhcaZQaX9kD4oUIp+69ctSpksxDmBHu8E6uQkKcCswqlcj6DCmDkLlgJ5HH2GWSomzp1RnHNDgDMqw2KEncUs1lOgrdx3PMEHZ5wvNkXUZzoQsP7NT58dXdsGziSwOuYtepJGLP+boEGhltc63v+cUjxpFkyEwRtmHFlHPMPCxTbXkQFViNO3XUcudRQtc/q+w4cNasJZq8F3Mj2',
            'tokenEXP' => '2018-10-19',
            'qtySync' => true,
            'enabled' => true,
            'packingSlipURL' => 'https://payments.ebay.com/ws/eBayISAPI.dll?PrintPostage&itemId=&transactionId=',
            'country' => 'USA'
        ]);
    }
}