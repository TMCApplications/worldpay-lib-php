<?php
namespace Worldpay;

class OrderService
{
    /**
     * @param $order
     * @return bool|mixed
     */
    public static function createOrder($order)
    {
        return Connection::getInstance()->sendRequest('orders', json_encode($order->toArray()), true);
    }

    /**
     * @param $orderCode
     * @param $responseCode
     * @return bool|mixed
     */
    public static function authorize3DSOrder($orderCode, $responseCode)
    {
        $obj = array_merge(array("threeDSResponseCode" => $responseCode), Utils::getThreeDSShopperObject());
        $json = json_encode($obj);
        return Connection::getInstance()->sendRequest('orders/' . $orderCode, $json, true, 'PUT');
    }

    /**
     * @param $orderCode
     * @param $amount
     * @return bool|mixed
     */
    public static function captureAuthorizedOrder($orderCode, $amount)
    {
        if (!empty($amount) && is_numeric($amount)) {
            $json = json_encode(array('captureAmount'=>"{$amount}"));
        } else {
            $json = false;
        }
        return Connection::getInstance()->sendRequest('orders/' . $orderCode . '/capture', $json, !!$json);
    }

    /**
     * @param $orderCode
     * @return bool|mixed
     */
    public static function cancelAuthorizedOrder($orderCode)
    {
        return Connection::getInstance()->sendRequest('orders/' . $orderCode, false, false, 'DELETE');
    }

    /**
     * @param $orderCode
     * @return bool|mixed
     */
    public static function getOrder($orderCode)
    {
        return Connection::getInstance()->sendRequest('orders/' . $orderCode, false, true, 'GET');
    }

    /**
     * @param $orderCode
     * @param $amount
     * @return bool|mixed
     */
    public static function refundOrder($orderCode, $amount)
    {
        if (!empty($amount) && is_numeric($amount)) {
            $json = json_encode(array('refundAmount'=>"{$amount}"));
        } else {
            $json = false;
        }
        return Connection::getInstance()->sendRequest('orders/' . $orderCode . '/refund', $json, false);
    }
}
