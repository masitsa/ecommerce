<?php

class Administration_model extends CI_Model {
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve all data from a table in the database
		-----------------------------------------------------------------------------------------
	*/
	 function select($table)
    {
        $query = $this->db->get($table);
		return $query->result();
    }
	
	 function select_order($table)
    {
        $this->db->select("*");
        $this->db->from($table);
        $this->db->order_by($table."_name", "asc");
       
        $query = $this->db->get();
		return $query->result();
    }
	
	function select_pagination($limit, $start, $table, $where, $items, $order)
	{
		$this->db->limit($limit, $start);
        
        $this->db->select($items);
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve particular data from multiple tables in the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_entries_where($table, $where, $items, $order)
    {
        $this->db->select($items);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by($order, "asc");
       
        $query = $this->db->get();
       
        return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Retrieve particular data from multiple tables in the database
		-----------------------------------------------------------------------------------------
	*/
	 function select_entries_where2($table, $where, $items, $order)
    {
        $this->db->select($items);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by($order, "desc");
       
        $query = $this->db->get();
       
        return $query->result();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Save data to the database
		-----------------------------------------------------------------------------------------
	*/
	 function insert($table, $items)
    {
        $this->db->insert($table, $items);
		
		return $this->db->insert_id();
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Updates data in the database
		-----------------------------------------------------------------------------------------
	*/
	 function update($table, $items, $field, $key)
    {
		$this->db->where($field, $key);
        $this->db->update($table, $items);
    }
	
	/*
		-----------------------------------------------------------------------------------------
		Deletes data in the database
		-----------------------------------------------------------------------------------------
	*/
	 function delete($table, $field, $key)
    {
		$this->db->where($field, $key);
        $this->db->delete($table);
    }  
	
	/*
		-----------------------------------------------------------------------------------------
		Deletes data in the database with multiple conditions
		-----------------------------------------------------------------------------------------
	*/
	 function delete2($table, $where)
    {
		$this->db->where($where);
        $this->db->delete($table);
    }  
    
	public function items_count($table, $where) {
        $this->db->where($where);
		$this->db->from($table);
        return $this->db->count_all_results();
    } 
	
	/*
		-----------------------------------------------------------------------------------------
		Select a number of items from a particluar database table
		-----------------------------------------------------------------------------------------
	*/
	function select_limit($limit, $table, $where, $items, $order)
	{
		$this->db->limit($limit);
        
        $this->db->select($items);
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
}