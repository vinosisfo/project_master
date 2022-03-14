<?php
class get_support_lib_new{
    function get_minggu_sekarang($date=''){ 
        $hari     = date("d");
        $date_set = substr($date,0,7)."-".$hari;
        $ddate    = (empty($date)) ? (date("Y-m-d")) : (date("Y-m-d",strtotime($date)));
        // var_dump($ddate,$date_set);
        $duedt = explode("-", $ddate);
        $date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
        $week  = (int)date('W', $date);
        return $week;
    }

    function get_total_minggu($year='') {
        $year = (empty($year)) ? (date('Y')) : (date("Y",strtotime($year)));
        $date = new DateTime();
        $week = date('W', strtotime(date('Y-m-d', strtotime($date->setISODate($year, 1, "1")->format('Y-m-d') . "-1day"))));
        return $week;
    }

    function get_menu_head(){
        $ci  = & get_instance();
        $sql = $ci->db->query("SELECT DISTINCT A.KodeMenu,A.NamaMenu,A.SubMenu 
                            FROM menu A 
                            WHERE A.JenisMenu='head'
                            AND A.Aktif=1 
                            ORDER BY A.NoUrut");
        return $sql;
    }

    function get_sub_menu($kode_menu){
        $ci  = & get_instance();
        $sql = $ci->db->query("SELECT DISTINCT A.KodeMenu,A.NamaMenu,A.SubMenu,A.UrlMenu,A.JenisMenu 
                            FROM menu A 
                            WHERE 1=1
                            AND A.Aktif=1
                            AND IFNULL(A.DetailMenu,'')=''
                            AND A.SubMenu='$kode_menu'
                            ORDER BY A.NoUrut");
        return $sql;
    }

    function get_url_set(){
        $ci        = & get_instance();
        $delimiter = strpos($_SERVER['PHP_SELF'], '\\') ? '\\' : '/';
        $projectFolderName = explode($delimiter, $_SERVER['PHP_SELF']);
        $last_index        = array_key_last($projectFolderName);
        $last_index_1      = $last_index-1;

        $hasil = $projectFolderName[$last_index_1]."/".$projectFolderName[$last_index];

        $get_back_menu = $ci->db->query("SELECT DISTINCT A.KodeMenu,A.NamaMenu,A.DetailMenu
                                        ,(SELECT X.UrlMenu FROM menu X where X.KodeMenu=A.DetailMenu) AS URL_BACK
                                        FROM menu A WHERE A.UrlMenu='$hasil'");
        $url_back  = ($get_back_menu->num_rows() > 0) ? ($get_back_menu->row()->URL_BACK."/".$get_back_menu->row()->DetailMenu) : "";
        return $url_back;
    }
    
}