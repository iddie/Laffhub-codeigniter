<?php
class Opera extends CI_Controller
{
    public function detect()
    {
        $is_on_extreme_mode = false;
        $extreme_ua = '';
        $is_data_savings_enabled = false;
        $is_user_agent_operamini = false;
        //check if extreme mode is activated
        if(array_key_exists('HTTP_X_OPERAMINI_PHONE_UA',$_SERVER))
        {
            $extreme_ua = $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'];
            $is_on_extreme_mode = true;
        }
        //check if data savings is enabled
        if(array_key_exists('HTTP_X_OPERAMINI_FEATURES',$_SERVER))
        {
            $is_data_savings_enabled = true;
        }
        //check if user agent is operamini
        if(array_key_exists('HTTP_USER_AGENT',$_SERVER))
        {
            $agent = $_SERVER['HTTP_USER_AGENT'];
            if(strpos($agent,'Opera') !== false)
            {
                $is_user_agent_operamini = true;
            }
        }
        echo json_encode([
            'status'=>'success',
            'message'=>'Detect data-savings mode for Operamini requests',
            'is_on_extreme_mode'=>$is_on_extreme_mode,
            'extreme_ua'=>$extreme_ua,
            'is_data_savings_enabled'=> $is_data_savings_enabled,
            'is_user_agent_operamini'=> $is_user_agent_operamini
        ]);
    }
}
