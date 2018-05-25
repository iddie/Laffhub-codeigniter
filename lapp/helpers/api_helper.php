<?php

    function content_deactivate_actions($instance)
    {
        $data = null;
        if($instance->input->post('customActionName'))
        {
            $custion_action_name = $instance->input->post('customActionName');
            switch($custion_action_name){
                case 'deactivate':
                    $id_array = $instance->input->post('id');
                    $data = ['play_status'=>false];
                    $instance->db->or_where_in('id', $id_array);
                    $instance->db->update('videos', $data);
                    $data['customActionMessage'] = 'Selected videos deactivated successfully';
                    $data['customActionStatus']  = 'OK';
                    break;
                case 'activate':
                    $id_array = $instance->input->post('id');
                    $data = ['play_status'=>true];
                    $instance->db->or_where_in('id', $id_array);
                    $instance->db->update('videos', $data);
                    $data['customActionMessage'] = 'Selected videos activated successfully';
                    $data['customActionStatus']  = 'OK';
                    break;
                case 'feature':
                    $id_array = $instance->input->post('id');
                    $data = ['featured'=>'YES'];
                    $instance->db->or_where_in('id', $id_array);
                    $instance->db->update('videos', $data);
                    $data['customActionMessage'] = 'Selected videos featured successfully';
                    $data['customActionStatus']  = 'OK';
                    break;
                case 'undo_feature':
                    $id_array = $instance->input->post('id');
                    $data = ['featured'=>'NO'];
                    $instance->db->or_where_in('id', $id_array);
                    $instance->db->update('videos', $data);
                    $data['customActionMessage'] = 'Feature Undo successfully';
                    $data['customActionStatus']  = 'OK';
                    break;
                default:
                    break;
            }
        }
        return $data;
    }
    function merge($base, $source)
    {
        if (count($source)>0) {
            foreach ($source as $key=>$value) {
                $base[$key] = $value;
            }
        }
        return $base;
    }

?>