<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Global_model extends CI_Model {

    public function insert($table, $data) {
        if ($this->db->insert($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function inserted_id() {
        return $this->db->insert_id();
    }

    public function insert_batch($table, $data) {
        if ($this->db->insert_batch($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update($table, $data, $where) {
        $this->db->where($where);
        if ($this->db->update($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_batch($table, $data, $where) {
        // $this->db->where($where);
        if ($this->db->update_batch($table, $data, $where)) {
            return true;
        } else {
            return false;
        }
    }

    public function select($table) {
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_query($query) {

        if (!$this->db->simple_query($query)) {
            return FALSE;
        } else {
            $get = $this->db->query($query);
            if ($get->num_rows() > 0) {
                return $get->result_array();
            } else {
                return FALSE;
            }
        }
        
    }

    public function select_query_row($query) {
        $get = $this->db->query($query);
        if ($get->num_rows() > 0) {
            return $get->num_rows();
        } else {
            return 0;
        }
    }

    public function select_where($table, $where) {
        $this->db->where($where);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function choose_select_where($select,$table, $where) {
        $this->db->select($select);
        $this->db->where($where);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_where_($table, $where, $id) {
        $this->db->where($where,$id);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }
    
    public function select_where_limit($table, $where, $limit) {
        $this->db->where($where);
        $this->db->limit($limit);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_limit($table, $limit) {
        $this->db->limit($limit);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_like($table, $like) {
        $this->db->like($like);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_like_limit($table, $like, $limit) {
        $this->db->like($like);
        $this->db->limit($limit);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_order($table, $order, $order_by) {
        $this->db->order_by($order, $order_by);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }
    
    public function select_order_limit($table, $order, $order_by, $limit) {
        $this->db->order_by($order, $order_by);
        $this->db->limit($limit);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_where_order($table, $where, $order, $order_by) {
        $this->db->where($where);
        $this->db->order_by($order, $order_by);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }
    
    public function select_where_order_limit($table, $where, $order, $order_by, $limit) {
        $this->db->where($where);
        $this->db->order_by($order, $order_by);
        $this->db->limit($limit);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_join_where_product($table, $where){
        $this->db->where($where);
        $this->db->join('category_product','category_product.Id = product.category_product_id');
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }

    public function select_join_where_process($table, $where){
        $this->db->where($where);
        $this->db->join('category_product','category_product.Id = process.category_product_id');
        $this->db->order_by('title','asc');
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }
    
    public function select_order_group($table, $order, $order_by, $group) {
        $this->db->order_by($order, $order_by);
        $this->db->group_by($group);
        $get = $this->db->get($table);
        if ($get) {
            return $get->result_array();
        } else {
            return FALSE;
        }
    }
    
    public function select_where_order_group($table, $where, $order, $order_by, $group) {
        $this->db->where($where);
        $this->db->order_by($order, $order_by);
        //$this->db->group_by($group);
        $get = $this->db->get($table);

        if($get->result() == TRUE)
        {
            $result = array();
            foreach($get->result_array() as $index => $value)
            {
              $result[$value[$group]][] = $value;
          }
          return $result;
      }
  }

  public function delete($table, $where) {
    $this->db->where($where);
    $flag = $this->db->delete($table);
    if ($flag) {
        return TRUE;
    } else {
        return FALSE;
    }
}

public function sort($table, $sort, $sort_by, $offset, $dataPerPage) {
    $query = $this->db->query("SELECT * FROM $table ORDER BY $sort $sort_by LIMIT $offset, $dataPerPage");
    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return false;
    }
}

public function sort_no_order($table, $offset, $dataPerPage) {
    $query = $this->db->query("SELECT * FROM $table LIMIT $offset, $dataPerPage");
    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return false;
    }
}

public function total_row($table) {
    $get = $this->db->get($table);

    if ($get->num_rows() > 0) {
        return $get->num_rows();
    } else {
        return 0;
    }
}

public function total_row_where($table, $where) {
    $this->db->where($where);
    $get = $this->db->get($table);

    if ($get->num_rows() > 0) {
        return $get->num_rows();
    } else {
        return 0;
    }
}

public function total_row_query($query) {
    $get = $this->db->query($query);

    if ($get->num_rows() > 0) {
        return $get->num_rows();
    } else {
        return 0;
    }
}

public function check_duplicate($table, $where) {
    $this->db->where($where);
    $get = $this->db->get($table);

    if ($get->num_rows() > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

public function last_query() {
    return $this->db->last_query();
}

public function checkToken() {
    $now = date('Y-m-d H:i:s');

    $query = $this->db->query("SELECT * FROM `temp_forgot` WHERE expired < '$now'");
    if ($query->num_rows() > 0) {
        foreach ($query->result_array() as $row) {
            $this->db->where('id', $row['id']);
            $this->db->delete('temp_forgot');
        }
    }
}

/* TAMBAHAN FATIH */

public function fieldlist($table){

    $query = $this->db->list_fields($table);

    return $query;

}

public function getDataWhereOrder($table,$where_query = array(),$order = array()){

    if(!empty($where_query)){

        foreach($where_query as $index => $value){
            $this->db->where($index,$value);
        }
    }

    if(!empty($order)){

        foreach($order as $index => $value){
            $this->db->order_by($index,$value);
        }   

    }

    return $this->db->get($table);

}

public function getDataWhereOrderWithLimit($table,$where_query = array(),$order = array(),$limit = 0){
    if(!empty($where_query)){
        foreach($where_query as $index => $value){
            $this->db->where($index,$value);
        }
    }

    if(!empty($order)){
        foreach($order as $index => $value){
            $this->db->order_by($index,$value);
        }   
    }

    if($limit > 0){
        $this->db->limit($limit);
    }
    return $this->db->get($table);
}

}
