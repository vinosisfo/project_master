<?php
class support_lib{
    function get_menu_head(){
        $ci  = & get_instance();
        $sql = $this->db->query("SELECT DISTINCT A.KodeMenu,A.NamaMenu FROM menu A WHERE A.JenisMenu='head' ORDER BY A.NoUrut");
        return $sql;
    }
}
?>