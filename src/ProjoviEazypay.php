<?php
namespace Projovi\ProjoviEazypay;

class ProjoviEazypay
{
    public $merchant_id;
    public $encryption_key;
    public $sub_merchant_id;
    public $reference_no;
    public $paymode;
    public $return_url;

    const DEFAULT_BASE_URL = 'https://eazypay.icicibank.com/EazyPG?';

    public function __construct()
    {
        $this->merchant_id              =    config('projovieazypay.merchant_id');
        $this->encryption_key           =    config('projovieazypay.encryption_key');
        $this->sub_merchant_id          =    config('projovieazypay.sub_merchant_id');
        $this->merchant_reference_no    =    config('projovieazypay.merchant_reference_no');
        $this->paymode                  =    config('projovieazypay.paymode');
        $this->return_url               =    config('projovieazypay.return_url');
    }

    public function getPaymentUrl($amount, $reference_no, $optionalField=null)
    {
        $mandatoryField   =    $this->getMandatoryField($amount, $reference_no);
        $optionalField    =    $this->getOptionalField($optionalField);
        $amount           =    $this->getAmount($amount);
        $reference_no     =    $this->getReferenceNo($reference_no);

        $paymentUrl = $this->generatePaymentUrl($mandatoryField, $optionalField, $amount, $reference_no);
        return $paymentUrl;
        // return redirect()->to($paymentUrl);
    }

    protected function generatePaymentUrl($mandatoryField, $optionalField, $amount, $reference_no)
    {
        $encryptedUrl = self::DEFAULT_BASE_URL."merchantid=".$this->merchant_id."&mandatory fields=".$mandatoryField."&optional fields=".$optionalField."&returnurl=".$this->getReturnUrl()."&Reference No=".$reference_no."&submerchantid=".$this->getSubMerchantId()."&transaction amount=".$amount."&paymode=".$this->getPaymode();

        return $encryptedUrl;
    }

    protected function getMandatoryField($amount, $reference_no)
    {
        return $this->getEncryptValue($reference_no.'|'.$this->sub_merchant_id.'|'.$amount);
    }

    // optional field must be seperated with | eg. (20|20|20|20)
    protected function getOptionalField($optionalField=null)
    {
        if (!is_null($optionalField)) {
            return $this->getEncryptValue($optionalField);
        }
        return null;
    }

    protected function getAmount($amount)
    {
        return $this->getEncryptValue($amount);
    }

    protected function getReturnUrl()
    {
        return $this->getEncryptValue($this->return_url);
    }

    protected function getReferenceNo($reference_no)
    {
        return $this->getEncryptValue($reference_no);
    }

    protected function getSubMerchantId()
    {
        return $this->getEncryptValue($this->sub_merchant_id);
    }

    protected function getPaymode()
    {
        return $this->getEncryptValue($this->paymode);
    }

    protected function getEncryptValue($str)
    {
      return base64_encode(openssl_encrypt($str, 'aes-128-ecb',$this->encryption_key, OPENSSL_RAW_DATA));
    }
}
