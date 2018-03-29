<?php
/**
 * BtcTurk API wrapper class
 * @author Ömer Doğan <omer_dogan@outlook.com>
 * @company Coinfono <http://coinfono.com>
 */

class Client
{
    private $baseUrl;
    private $apiKey;
    private $apiSecret;

    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = 'https://www.btcturk.com/api/';
    }

    /**
     * Invoke API
     * @param string $method API method to call
     * @param array $params parameters
     * @param bool $apiKey  use apikey or not
     * @return object
     */

    private function get_call($method, $params = array(), $apiKey = false, $postMethod = false)
    {
        $uri = $this->baseUrl.$method;
        if ($apiKey == true) {
            $message = $this->apiKey.time();
            $signatureBytes = hash_hmac('sha256', $message, base64_decode($this->apiSecret), true);
            $signature = base64_encode($signatureBytes);
            $nonce = time();
            $headers = array(
                'X-PCK: '.$this->apiKey,
                'X-Stamp: '.$nonce,
                'X-Signature: '.$signature,
            );
        }

        if (!empty($params)) {
            if ($postMethod == true) {
                $post_data = http_build_query($params);
            } else {
                $uri .= '?'.http_build_query($params);
            }
        }

        $ch = curl_init($uri);
        if ($apiKey == true) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($postMethod == true) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        $answer = json_decode($result);

        return $answer;
    }

    /**
     * Get the current tick values for a market.
     * @param string $symbol	literal for the market (ex: BTCTRY)
     * @return array
     */

    public function getTicker($symbol)
    {
        $query = $this->get_call('ticker');
        foreach ($query as $key => $value) {
            if ($value->pair == $symbol) {
                return  $value;
            }
        }
    }


    /**
     * Get the current ticks values for a market.
     * @return object
     */

    public function getTickers()
    {
        return $this->get_call('ticker');
    }


    /**
     * Get the orderbook for a given market
     * @param string $symbol literal for the market (ex: BTCTRY)
     * @return object
     */
    public function getOrderBook($symbol)
    {
        return $this->get_call('orderbook', array('pairSymbol' => $symbol));
    }


    /**
     * Get the latest trades that have occured for a specific market
     * @param string $symbol literal for the market (ex: BTCTRY)
     * @return object
     */
    public function getTrades($symbol)
    {
        return $this->get_call('trades', array('pairSymbol' => $symbol));
    }

    /**
     * Daily BTCTRY displays candle opening closures
     * @return object
     */
    public function getOhcl()
    {
        return $this->get_call('ohlcdata');
    }

    /**
     * Retrieve your order history
     * @param integer $offset (optional) Skip that many transactions before beginning to return results. Default value is 0.
     * @param integer $limit (optional) Limit result to that many transactions. Default value is 25.
     * @param string $sort (optional) Results are sorted by date and time. Provide "asc" for ascending results, "desc" for descending results. Default value is "desc".
     * @return object
     */
    public function getUserTransactions($offset = 0, $limit = 25, $sort = 'desc')
    {
        return $this->get_call('userTransactions', array(
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort, ), true);
    }

    /**
     * Get all orders that you currently have opened. A specific market can be requested
     * @param string $symbol literal for the market (ex: BTCTRY)
     * @return object
     */
    public function getOpenOrders($symbol)
    {
        return $this->get_call('openOrders', array('pairSymbol' => $symbol), true);
        //getOpenOrders (BTCTRY)
    }

    /**
     * Retrieve the balance from your account for a specific currency
     * @param string $symbol literal for the currency (ex: BTC)
     * @return array
     */
    public function getBalances($symbol = '')
    {
        $query = $this->get_call('balancev2', '', true);
	    if($symbol){
		    foreach ($query->Data as $value) {
			    if ($value->currency == $symbol) {
				    return  $value;
			    }
		    }
	    } else {
		    return  $query->Data;
	    }


    }


    /**
     * Cancel a buy or sell order
     * @param integer $id id of sell or buy order
     * @return object
     */
    public function getCancelOrder($id)
    {
        return $this->get_call('cancelOrder', array('id' => $id), true, true);
        //getCancelOrder(xxx);
    }

    /**
     * Market Buy
     * @param string $symbol literal for the currency (ex: BTCTRY)
     * @param integer $total The total amount you will spend with this order. You will buy from different prices until your order is filled as described above
     * @param integer $totalPrecision Precision of the Total (.001)
     * @return object
     */
    public function getMarketBuy($symbol, $total, $totalPrecision)
    {
        return $this->get_call('exchange',
            array('OrderMethod' => 1,
                'OrderType' => 0,
                'total' => $total,
                'totalPrecision' => $totalPrecision,
                'PairSymbol' => $symbol,
                'amount' => 0,
                'amountPrecision' => 0,
                'price' => 0,
                'pricePrecision' => 00,
                'triggerPrice' => 0,
                'triggerPricePrecision' => 00,
                'DenominatorPrecision' => 2, ), true, true);
        //getMarketBuy("BTCTRY","200","00")
    }

    /**
     * Market Sell
     * @param string $symbol literal for the currency (ex: BTCTRY)
     * @param integer $amount Amount field will be ignored for buy market orders. The amount will be calculated according to the total value that you send.
     * @param integer $amountPrecision Precision of the amount (.001)
     * @return object
     */
    public function getMarketSell($symbol, $amount, $amountPrecision)
    {
        return $this->get_call('exchange',
            array('OrderMethod' => 1,
                'OrderType' => 1,
                'total' => 0,
                'totalPrecision' => 0,
                'PairSymbol' => $symbol,
                'amount' => $amount,
                'amountPrecision' => $amountPrecision,
                'price' => 0,
                'pricePrecision' => 00,
                'triggerPrice' => 0,
                'triggerPricePrecision' => 00,
                'DenominatorPrecision' => 2, ), true, true);
        //getMarketSell("BTCTRY","0","001")
    }

    /**
     * Limit Buy
     * @param string $symbol literal for the currency (ex: BTCTRY)
     * @param integer $total The total amount you will spend with this order. You will buy from different prices until your order is filled as described above
     * @param integer $totalPrecision Precision of the Total (.001)
     * @param integer $price Price field will be ignored for market orders. Market orders get filled with different prices until your order is completely filled.
     * There is a 5% limit on the difference between the first price and the last price.
     * İ.e. you can't buy at a price more than 5% higher than the best sell at the time of order submission
     * @param integer $pricePrecision  Precision of the price (.001)
     * @return object
     */
    public function getLimitBuy($symbol, $total, $totalPrecision, $price, $pricePrecision)
    {
        return $this->get_call('exchange',
            array('OrderMethod' => 0,
                'OrderType' => 0,
                'PairSymbol' => $symbol,
                'amount' => $total,
                'amountPrecision' => $totalPrecision,
                'price' => $price,
                'triggerPrice' => 0,
                'triggerPricePrecision' => 00,
                'pricePrecision' => $pricePrecision,
                'DenominatorPrecision' => 2, ), true, true);
        //getLimitBuy("BTCTRY","0","001","42000","00")
    }

    /**
     * Limit Sell
     * @param string $symbol literal for the currency (ex: BTCTRY)
     * @param integer $total The total amount you will spend with this order. You will buy from different prices until your order is filled as described above
     * @param integer $totalPrecision Precision of the Total (.001)
     * @param integer $price Price field will be ignored for market orders. Market orders get filled with different prices until your order is completely filled.
     * There is a 5% limit on the difference between the first price and the last price.
     * İ.e. you can't buy at a price more than 5% higher than the best sell at the time of order submission
     * @param integer $pricePrecision  Precision of the price (.001)
     * @return object
     */
    public function getLimitSell($symbol, $total, $totalPrecision, $price, $pricePrecision)
    {
        return $this->get_call('exchange',
            array('OrderMethod' => 0,
                'OrderType' => 1,
                'PairSymbol' => $symbol,
                'amount' => $total,
                'amountPrecision' => $totalPrecision,
                'price' => $price,
                'triggerPrice' => 0,
                'triggerPricePrecision' => 00,
                'pricePrecision' => $pricePrecision,
                'DenominatorPrecision' => 2, ), true, true);

        //getLimitSell("BTCTRY","0","001","42000","00")
    }


    /**
     * Stop Buy
     * @param string $symbol literal for the currency (ex: BTCTRY)
     * @param integer $total The total amount you will spend with this order. You will buy from different prices until your order is filled as described above
     * @param integer $totalPrecision Precision of the Total (.001)
     * @param integer $price Price field will be ignored for market orders. Market orders get filled with different prices until your order is completely filled.
     * There is a 5% limit on the difference between the first price and the last price.
     * İ.e. you can't buy at a price more than 5% higher than the best sell at the time of order submission
     * @param integer $pricePrecision  Precision of the price (.001)
     * @param integer $triggerPrice For stop orders
     * @param integer $triggerPricePrecision Precision of the TriggerPrice (.001)
     * @return object
     */
    public function getStopBuy($symbol, $total, $totalPrecision, $price, $pricePrecision, $triggerPrice, $triggerPricePrecision)
    {
        return $this->get_call('exchange',
            array('OrderMethod' => 2,
                'OrderType' => 0,
                'PairSymbol' => $symbol,
                'amount' => $total,
                'amountPrecision' => $totalPrecision,
                'price' => $price,
                'pricePrecision' => $pricePrecision,
                'triggerPrice' => $triggerPrice,
                'triggerPricePrecision' => $triggerPricePrecision,
                'DenominatorPrecision' => 2, ), true, true);
        //getStopBuy("BTCTRY","0","001","45000","00","44400","00")
    }


    /**
     * Stop Sell
     * @param string $symbol literal for the currency (ex: BTCTRY)
     * @param integer $total The total amount you will spend with this order. You will buy from different prices until your order is filled as described above
     * @param integer $totalPrecision Precision of the Total (.001)
     * @param integer $price Price field will be ignored for market orders. Market orders get filled with different prices until your order is completely filled.
     * There is a 5% limit on the difference between the first price and the last price.
     * İ.e. you can't buy at a price more than 5% higher than the best sell at the time of order submission
     * @param integer $pricePrecision  Precision of the price (.001)
     * @param integer $triggerPrice For stop orders
     * @param integer $triggerPricePrecision Precision of the TriggerPrice (.001)
     * @return object
     */
    public function getStopSell($symbol, $total, $totalPrecision, $price, $pricePrecision, $triggerPrice, $triggerPricePrecision)
    {
        return $this->get_call('exchange',
            array('OrderMethod' => 2,
                'OrderType' => 1,
                'PairSymbol' => $symbol,
                'amount' => $total,
                'amountPrecision' => $totalPrecision,
                'price' => $price,
                'pricePrecision' => $pricePrecision,
                'triggerPrice' => $triggerPrice,
                'triggerPricePrecision' => $triggerPricePrecision,
                'DenominatorPrecision' => 2, ), true, true);
        //getStopSell("BTCTRY","0","001","39800","00","40000","00")
    }

	public function getStopMarketSell($symbol, $total, $totalPrecision, $price, $pricePrecision, $triggerPrice, $triggerPricePrecision)
	{
		return $this->get_call('exchange',
			array('OrderMethod' => 3,
			      'OrderType' => 1,
			      'PairSymbol' => $symbol,
			      'amount' => $total,
			      'amountPrecision' => $totalPrecision,
			      'price' => $price,
			      'pricePrecision' => $pricePrecision,
			      'triggerPrice' => $triggerPrice,
			      'triggerPricePrecision' => $triggerPricePrecision,
			      'DenominatorPrecision' => 2, ), true, true);
		//getStopSell("BTCTRY","0","001","39800","00","40000","00")
	}
}
