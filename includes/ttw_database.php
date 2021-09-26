<?php

class DatabaseHelper{
    

    public function create($destination, $userID){
        global $table_prefix, $wpdb;
        $wp_track_table = $table_prefix. 'ttw_link';

        $token = $this->encrypt($destination);
        
        $wpdb->insert(
            $wp_track_table,
            array(
                'token' => $token,
                'destination' => $destination,
                'user_id' => $userID,
                'visitedcount' => null,
                'visitedmax' => null,
            ),
        );
        return $token;
    }

    public function get_destination($token){
        global $table_prefix, $wpdb;
        $wp_track_table = $table_prefix. 'ttw_link';

        // $record = $wpdb->query(
        //    $wpdb->prepare("SELECT * FROM %s WHERE token = %s",$wp_track_table,$token)
        // );

        $record = $wpdb->get_row(
            "SELECT * FROM $wp_track_table WHERE token = '$token'"
        );

        return $record;
    }

    public function increment_visitedcount($id, $visitedcount){
        global $table_prefix, $wpdb;
        $wp_track_table = $table_prefix. 'ttw_link';
        $wpdb->update(
            $wp_track_table,
            array('visitedcount' => $visitedcount),
            array('ID' => $id)
        );

    }



    public function encrypt($destination){
        $token = md5($destination);
        $token_len = strlen($token);
        $len = 6;
        $offset = 0;
        $take = substr($token,$offset, $offset + $len);
        $exist = $this->get_destination($take);
        while ($exist != null){
            $offset +=1;
            if($offset + $len == $token_len){
                $take = uniqid();
                $offset = 0;
            }
            $take = substr($token,$offset, $offset + $len);
            $exist = $this->get_destination($take);
        }
        
        return $take;
    }


}