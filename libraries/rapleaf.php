<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rapleaf{
    private $ci;

    public function __construct()
    {
        $this->ci = & get_instance();
    }

    /**
     * Request personalized data for a single person
     * @param string|array $data - email address, or associative array of data
     * @return bool
     */
    public function personalize($data)
    {
        if(is_string($data))
        {
            $params['email'] = $data;
            $full_query = $this->_set_parameters($params);
        }
        elseif(is_array($data))
        {
            $full_query = $this->_set_parameters($data);
        }
        else
        {
            return FALSE;
        }

        $request_url = $this->_prepare_single_request($full_query);

        try
        {
            $rapleaf = $this->_make_request($request_url);
        }
        catch(RapleafException $e)
        {
            if($this->ci->config->item('rapleaf_debug_mode'))
            {
                echo $e;
            }
        }
        return $rapleaf;
    }

    /**
     * Request personalized data for a single person
     * @param array $data
     * @return array
     */
    public function personalize_bulk(array $data)
    {
        foreach($data as $query)
        {
            $full_query[] = $this->_set_parameters($query);
        }
        $request_url = $this->ci->config->item('rapleaf_base_uri_bulk').'?api_key='.$this->ci->config->item('rapleaf_api_key');

        try
        {
            $rapleaf = $this->_make_request($request_url, json_encode($full_query));
        }
        catch(RapleafException $e)
        {
            if($this->ci->config->item('rapleaf_debug_mode'))
            {
                echo $e;
            }
        }
        return $rapleaf;
    }

    /**
     * Convert query parameters into a rapleaf URL
     * @param $search_params - associate array of parameters/values
     * @return string
     */
    private function _prepare_single_request($search_params)
    {
        // encode the email if it was a parameter
        if(array_key_exists('email', $search_params))
        {
            if($this->ci->config->item('rapleaf_email_encoding') == 'md5')
            {
                $email = preg_replace('/ /', '', strtolower($search_params['email']));
                $search_params['md5_email'] = md5($email);
                unset($search_params['email']);
            }
            elseif($this->ci->config->item('rapleaf_email_encoding') == 'sha1')
            {
                $email = preg_replace('/ /', '', strtolower($search_params['email']));
                $search_params['sha1_email'] = sha1($email);
                unset($search_params['email']);
            }
            else
            {
                $search_params['email'] = urlencode($search_params['email']);
            }
        }

        // add the api key
        $search_params['api_key'] = $this->ci->config->item('rapleaf_api_key');

        $full_url= '?';
        foreach($search_params as $k => $v)
        {
            $full_url .= "{$k}={$v}&";
        }
        $full_url = substr($full_url, 0, -1);
        return $this->ci->config->item('rapleaf_base_uri').$full_url;
    }

    /**
     * Set only supported parameters
     * @param array $params
     * @return array
     */
    private function _set_parameters(array $params)
    {
        $parameters = array();
        foreach($params as $key => $field)
        {
            // Check that parameter names are legit
            if(in_array($key, $this->ci->config->item('rapleaf_query_fields')))
            {
                $parameters[$key] = $field;
            }
        }
        return $parameters;
    }

    /**
     * Make the HTP Request to Rapleaf and return the data
     * @param $url - full rapleaf url
     * @param $body - request body (only needed for bulk requests)
     * @return mixed|null
     */
    private function _make_request($url, $body = NULL)
    {
        if($this->ci->config->item('rapleaf_show_available'))
        {
            $url .= '&show_available';
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json", "Content-Type: application/json"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        if($body)
        {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }
        else
        {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        }

        $return['body'] = curl_exec($curl);
        $return['info'] = curl_getinfo($curl);
        $return['error'] = curl_error($curl);
        curl_close($curl);

        if($return['info']['http_code'] != 200)
        {
            $error = "Rapleaf request returned ".$return['info']['http_code']." when requesting ".$url." [Request Failure]";
            throw new RapleafException($error);
        }
        return json_decode($return['body'], TRUE);
    }
}

class RapleafException extends Exception
{
    function __construct($string)
    {
        parent::__construct($string);
    }
    
    public function __toString() {
        return "exception '".__CLASS__ ."' with message '".$this->getMessage()."' in ".$this->getFile().":".$this->getLine()."\nStack trace:\n".$this->getTraceAsString();
    }
}