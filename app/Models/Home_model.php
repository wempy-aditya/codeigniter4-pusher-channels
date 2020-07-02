<?php namespace App\Models;
use CodeIgniter\Model;

class Home_model extends Model 
{
    public function get_product()
    {
        return $this->db->table('product')->get()->getResultArray();
    }
    public function insert_product($dataa) 
    {
        return $this->db->table('product')->insert($dataa);
    }
    public function update_product($dataa, $product_id)
    {
        return $this->db->table('product')->update($dataa,array('product_id'=>$product_id));
    }
    public function delete_product($product_id)
    {
        return $this->db->table('product')->delete(array('product_id'=>$product_id));
    }

}
